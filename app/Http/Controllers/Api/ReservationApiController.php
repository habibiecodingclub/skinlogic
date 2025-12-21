<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\User;
use App\Models\Terapis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation; // Pastikan Anda membuat file Mail ini (instruksi di bawah)

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

        // DEFINISI JAM OPERASIONAL (Bisa disesuaikan)
        // Format: H:i
        $businessHours = [
            '10:00', '10:30', '11:00', '11:30',
            '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30'
        ];

        // INFO:
        // Karena sekarang kita menggunakan sistem "Pilih Terapis", 
        // Slot waktu ditampilkan semua dulu. Validasi penuh/tidaknya nanti saat user memilih Terapis.
        // Jika Terapis A sibuk jam 10:00, user masih bisa pilih Terapis B di jam 10:00.
        
        return response()->json([
            'success' => true,
            'tanggal' => $tanggal,
            'available_slots' => $businessHours,
            'total_available' => count($businessHours)
        ]);
    }

    /**
     * Get available therapists/staff at specific date & time
     */
    public function getAvailableTherapists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal'], 422);
        }

        // 1. Ambil ID Terapis yang SIBUK di tabel Reservasi
        $busyTherapistsIds = Reservation::whereDate('tanggal_reservasi', $request->tanggal)
            ->where('jam_reservasi', $request->jam)
            ->whereNotIn('status', ['batal'])
            ->whereNotNull('terapis_id')
            ->pluck('terapis_id')
            ->toArray();

        // 2. QUERY KE TABEL TERAPIS (Bukan User lagi)
        $availableTherapists = Terapis::where('is_active', true) // Hanya ambil yang aktif
            ->whereNotIn('id', $busyTherapistsIds)
            ->select('id', 'nama as name', 'spesialisasi') // Frontend minta 'name', jadi kita alias-kan
            ->get();

        return response()->json([
            'success' => true,
            'available_therapists' => $availableTherapists,
            'total_available' => $availableTherapists->count()
        ]);
    }

    /**
     * Get all perawatan list
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
     * CREATE NEW RESERVATION
     * (Core Function with Security & Logic)
     */
    public function store(Request $request)
    {
        Log::info('New Reservation Request:', $request->all());

        // 1. VALIDASI INPUT (Server Side Gatekeeper)
        $validator = Validator::make($request->all(), [
            'pelanggan.nama' => 'required|string|max:255',
            'pelanggan.email' => 'required|email|max:255',
            'pelanggan.nomor_telepon' => 'required|string|max:20',
            
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'jam_reservasi' => 'required|date_format:H:i',
            
            'terapis_id' => 'required|exists:users,id', // Wajib pilih terapis
            
            'perawatans' => 'required|array|min:1',
            'perawatans.*.id' => 'required|exists:perawatans,id',
            'perawatans.*.qty' => 'required|integer|min:1|max:3', // Security: Max 3 qty per item
            
            'catatan' => 'nullable|string|max:500',
        ], [
            'terapis_id.required' => 'Silakan pilih terapis terlebih dahulu.',
            'perawatans.*.qty.max' => 'Jumlah perawatan maksimal 3 per jenis.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Data Gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. CEK RACE CONDITION (Cek apakah baru saja diambil orang lain)
        $isBooked = Reservation::where('tanggal_reservasi', $request->tanggal_reservasi)
            ->where('jam_reservasi', $request->jam_reservasi)
            ->where('terapis_id', $request->terapis_id)
            ->where('status', '!=', 'batal')
            ->exists();

        if ($isBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon maaf! Slot waktu dengan Terapis tersebut baru saja dipesan orang lain. Silakan pilih Terapis atau Jam lain.'
            ], 409); // 409 Conflict
        }

        try {
            // 3. DATABASE TRANSACTION (Agar data aman)
            $reservation = DB::transaction(function () use ($request) {
                
                // A. Handle Pelanggan (Cari by Email, kalau gak ada Buat Baru)
                $pelanggan = Pelanggan::firstOrCreate(
                    ['Email' => $request->pelanggan['email']], 
                    [
                        'Nama' => $request->pelanggan['nama'],
                        'Nomor_Telepon' => $request->pelanggan['nomor_telepon'],
                        'Status' => 'Non Member',
                        // Tambahkan field lain jika ada default
                    ]
                );

                // Update data pelanggan jika nama/no hp berubah (Opsional)
                $pelanggan->update([
                    'Nama' => $request->pelanggan['nama'],
                    'Nomor_Telepon' => $request->pelanggan['nomor_telepon']
                ]);

                // B. Buat Header Reservasi
                $newReservation = Reservation::create([
                    'pelanggan_id' => $pelanggan->id,
                    'tanggal_reservasi' => $request->tanggal_reservasi,
                    'jam_reservasi' => $request->jam_reservasi,
                    'terapis_id' => $request->terapis_id,
                    'catatan' => $request->catatan,
                    'status' => 'menunggu', // Default status
                ]);

                // C. Simpan Detail Perawatan (Pivot Table)
                $perawatanData = [];
                foreach ($request->perawatans as $item) {
                    $perawatanDB = Perawatan::find($item['id']);
                    if($perawatanDB) {
                        $perawatanData[$item['id']] = [
                            'qty' => $item['qty'],
                            'harga' => $perawatanDB->Harga // Simpan harga saat transaksi terjadi
                        ];
                    }
                }
                
                $newReservation->perawatans()->sync($perawatanData);
                
                return $newReservation;
            });

            // 4. KIRIM EMAIL KONFIRMASI (Di luar transaction agar tidak membatalkan booking jika email error)
            try {
                // Pastikan class Mailable sudah dibuat: php artisan make:mail ReservationConfirmation
                Mail::to($request->pelanggan['email'])->send(new ReservationConfirmation($reservation));
            } catch (\Exception $e) {
                Log::error("Gagal kirim email: " . $e->getMessage());
                // Tidak return error, biarkan booking tetap sukses.
            }

            // 5. RESPONSE SUKSES
            return response()->json([
                'success' => true,
                'message' => 'Reservasi Berhasil! Kode Booking telah dikirim ke email Anda.',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'reservation_code' => 'RES-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'tanggal' => $reservation->tanggal_reservasi->format('d M Y'),
                    'jam' => $reservation->jam_reservasi,
                    'total_harga' => $reservation->total_harga, // Pastikan ada accessor getTotalHargaAttribute di Model
                    'status' => $reservation->status,
                    'status_label' => ucfirst($reservation->status),
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Reservation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'debug_error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get reservation detail by Code (RES-XXXX) or ID
     */
    public function show($identifier)
    {
        try {
            // Deteksi input: Kode Booking (RES-...) atau ID biasa
            if (strpos($identifier, 'RES-') === 0) {
                $id = (int) str_replace('RES-', '', $identifier);
                $reservation = Reservation::where('id', $id)->firstOrFail();
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
                    'status_label' => ucfirst($reservation->status),
                    'total_harga' => $reservation->total_harga,
                    'catatan' => $reservation->catatan,
                    
                    'pelanggan' => [
                        'nama' => $reservation->pelanggan->Nama,
                        'email' => $reservation->pelanggan->Email,
                        'nomor_telepon' => $reservation->pelanggan->Nomor_Telepon,
                    ],
                    
                    'terapis' => $reservation->terapis ? [
                        'nama' => $reservation->terapis->name
                    ] : null,

                    'perawatans' => $reservation->perawatans->map(function ($p) {
                        return [
                            'nama' => $p->Nama_Perawatan,
                            'qty' => $p->pivot->qty,
                            'harga' => $p->pivot->harga,
                            'subtotal' => $p->pivot->harga * $p->pivot->qty
                        ];
                    }),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data reservasi tidak ditemukan. Periksa kembali Kode Booking Anda.'
            ], 404);
        }
    }

    // Method cancel (Opsional jika diperlukan frontend)
    public function cancel(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            if ($reservation->status !== 'menunggu') {
                return response()->json(['success'=>false, 'message'=>'Hanya reservasi status Menunggu yang bisa dibatalkan.'], 400);
            }
            $reservation->update(['status' => 'batal']);
            return response()->json(['success'=>true, 'message'=>'Reservasi berhasil dibatalkan.']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Error system.'], 500);
        }
    }

    // Method history (Opsional)
    // public function history(Request $request)
    // {
    //     // ... kode history lama Anda ...
    // }
}