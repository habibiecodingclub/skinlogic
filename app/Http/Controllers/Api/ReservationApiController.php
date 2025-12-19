<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReservationApiController extends Controller
{
    /**
     * Get available time slots for a date
     */
    public function getAvailableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $tanggal = $request->tanggal;

        // Define business hours (9 AM to 6 PM)
        $businessHours = [];
        for ($hour = 9; $hour < 18; $hour++) {
            $businessHours[] = sprintf('%02d:00', $hour);
            $businessHours[] = sprintf('%02d:30', $hour);
        }

        // Get existing reservations for the date
        $existingReservations = Reservation::whereDate('tanggal_reservasi', $tanggal)
            ->whereNotIn('status', ['batal'])
            ->pluck('jam_reservasi')
            ->toArray();

        // Filter out taken slots
        $availableSlots = array_values(array_diff($businessHours, $existingReservations));

        return response()->json([
            'success' => true,
            'tanggal' => $tanggal,
            'available_slots' => $availableSlots,
            'total_available' => count($availableSlots)
        ]);
    }

    /**
     * Get available therapists/staff
     */
    public function getAvailableTherapists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get therapists who are not busy at that time
        $busyTherapists = Reservation::whereDate('tanggal_reservasi', $request->tanggal)
            ->where('jam_reservasi', $request->jam)
            ->whereNotIn('status', ['batal'])
            ->whereNotNull('terapis_id')
            ->pluck('terapis_id')
            ->toArray();

        $availableTherapists = User::role('terapis')
            ->whereNotIn('id', $busyTherapists)
            ->select('id', 'name', 'email')
            ->get();

        return response()->json([
            'success' => true,
            'available_therapists' => $availableTherapists,
            'total_available' => $availableTherapists->count()
        ]);
    }

    /**
     * Get all perawatan for booking
     */
    public function getPerawatans()
    {
        $perawatans = Perawatan::select('id', 'Nama_Perawatan', 'Harga')
            ->orderBy('Nama_Perawatan')
            ->get();

        return response()->json([
            'success' => true,
            'perawatans' => $perawatans
        ]);
    }

    /**
     * Create new reservation from frontend
     */
    public function store(Request $request)
    {
        Log::info('API Reservation Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'pelanggan.nama' => 'required|string|max:255',
            'pelanggan.email' => 'required|email|max:255',
            'pelanggan.nomor_telepon' => 'required|string|max:15',
            'pelanggan.status' => 'required|in:Member,Non Member',
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'jam_reservasi' => 'required|date_format:H:i',
            'perawatans' => 'required|array|min:1',
            'perawatans.*.id' => 'required|exists:perawatans,id',
            'perawatans.*.qty' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
            'terapis_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if customer exists, if not create new
            $pelanggan = Pelanggan::where('Email', $request->pelanggan['email'])->first();

            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'Nama' => $request->pelanggan['nama'],
                    'Email' => $request->pelanggan['email'],
                    'Nomor_Telepon' => $request->pelanggan['nomor_telepon'],
                    'Status' => $request->pelanggan['status'],
                    'Pekerjaan' => $request->pelanggan['pekerjaan'] ?? null,
                    'Tanggal_Lahir' => $request->pelanggan['tanggal_lahir'] ?? null,
                ]);
            }

            // Check time slot availability
            $existingReservation = Reservation::whereDate('tanggal_reservasi', $request->tanggal_reservasi)
                ->where('jam_reservasi', $request->jam_reservasi)
                ->whereNotIn('status', ['batal'])
                ->exists();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.'
                ], 409);
            }

            // Create reservation
            $reservation = Reservation::create([
                'pelanggan_id' => $pelanggan->id,
                'tanggal_reservasi' => $request->tanggal_reservasi,
                'jam_reservasi' => $request->jam_reservasi,
                'catatan' => $request->catatan,
                'terapis_id' => $request->terapis_id,
                'status' => Reservation::STATUS_MENUNGGU,
            ]);

            // Attach perawatans with prices
            $perawatanData = [];
            foreach ($request->perawatans as $item) {
                $perawatan = Perawatan::find($item['id']);
                $perawatanData[$perawatan->id] = [
                    'qty' => $item['qty'],
                    'harga' => $perawatan->Harga,
                ];
            }

            $reservation->perawatans()->sync($perawatanData);

            // Log the successful reservation
            Log::info('Reservation created successfully:', [
                'reservation_id' => $reservation->id,
                'pelanggan_id' => $pelanggan->id,
                'total_perawatans' => count($request->perawatans)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat!',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'reservation_code' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'tanggal' => $reservation->tanggal_reservasi->format('d M Y'),
                    'jam' => $reservation->jam_reservasi,
                    'total_harga' => $reservation->total_harga,
                    'status' => $reservation->status,
                    'status_label' => Reservation::getStatusOptions()[$reservation->status],
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating reservation:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat reservasi.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get reservation details by ID or code
     */
    public function show($identifier)
    {
        try {
            // Check if identifier is ID or code
            if (strpos($identifier, 'RES-') === 0) {
                $id = (int) str_replace('RES-', '', $identifier);
                $reservation = Reservation::findOrFail($id);
            } else {
                $reservation = Reservation::findOrFail($identifier);
            }

            $reservation->load(['pelanggan', 'perawatans', 'terapis']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $reservation->id,
                    'reservation_code' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'tanggal' => $reservation->tanggal_reservasi->format('d M Y'),
                    'jam' => $reservation->jam_reservasi,
                    'status' => $reservation->status,
                    'status_label' => Reservation::getStatusOptions()[$reservation->status],
                    'catatan' => $reservation->catatan,
                    'total_harga' => $reservation->total_harga,
                    'created_at' => $reservation->created_at->format('d M Y H:i'),
                    'pelanggan' => [
                        'nama' => $reservation->pelanggan->Nama,
                        'email' => $reservation->pelanggan->Email,
                        'nomor_telepon' => $reservation->pelanggan->Nomor_Telepon,
                        'status' => $reservation->pelanggan->Status,
                    ],
                    'terapis' => $reservation->terapis ? [
                        'id' => $reservation->terapis->id,
                        'nama' => $reservation->terapis->name,
                    ] : null,
                    'perawatans' => $reservation->perawatans->map(function ($perawatan) {
                        return [
                            'id' => $perawatan->id,
                            'nama' => $perawatan->Nama_Perawatan,
                            'harga' => $perawatan->pivot->harga,
                            'qty' => $perawatan->pivot->qty,
                            'subtotal' => $perawatan->pivot->harga * $perawatan->pivot->qty,
                        ];
                    }),
                    'pesanan' => $reservation->pesanan ? [
                        'id' => $reservation->pesanan->id,
                        'metode_pembayaran' => $reservation->pesanan->Metode_Pembayaran,
                        'status' => $reservation->pesanan->status,
                    ] : null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Cancel reservation
     */
    public function cancel(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);

            // Only allow cancellation for certain statuses
            if (!in_array($reservation->status, [
                Reservation::STATUS_MENUNGGU,
                Reservation::STATUS_DIKONFIRMASI
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak dapat dibatalkan karena sudah ' .
                                 Reservation::getStatusOptions()[$reservation->status]
                ], 400);
            }

            $reservation->update(['status' => Reservation::STATUS_BATAL]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan.',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'status' => $reservation->status,
                    'status_label' => Reservation::getStatusOptions()[$reservation->status],
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan reservasi.'
            ], 500);
        }
    }

    /**
     * Get customer's reservation history
     */
    public function history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email harus diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        $pelanggan = Pelanggan::where('Email', $request->email)->first();

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada reservasi untuk email ini.'
            ], 404);
        }

        $reservations = Reservation::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_reservasi', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'pelanggan' => [
                    'nama' => $pelanggan->Nama,
                    'email' => $pelanggan->Email,
                    'status' => $pelanggan->Status,
                ],
                'reservations' => $reservations->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'reservation_code' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                        'tanggal' => $reservation->tanggal_reservasi->format('d M Y'),
                        'jam' => $reservation->jam_reservasi,
                        'status' => $reservation->status,
                        'status_label' => Reservation::getStatusOptions()[$reservation->status],
                        'total_harga' => $reservation->total_harga,
                        'created_at' => $reservation->created_at->format('d M Y H:i'),
                        'perawatan_count' => $reservation->perawatans->count(),
                    ];
                }),
                'pagination' => [
                    'total' => $reservations->total(),
                    'per_page' => $reservations->perPage(),
                    'current_page' => $reservations->currentPage(),
                    'last_page' => $reservations->lastPage(),
                ]
            ]
        ]);
    }
}
