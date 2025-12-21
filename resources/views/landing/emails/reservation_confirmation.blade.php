<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi</title>
    <style>
        /* Reset CSS dasar untuk email client */
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; color: #51545E; }
        table { border-spacing: 0; width: 100%; }
        td { padding: 0; }
        img { border: 0; }
        
        /* Container Utama */
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f4f7; padding-bottom: 40px; }
        .content { max-width: 600px; background-color: #ffffff; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        /* Header */
        .header { background-color: #001a4d; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .header p { color: #c5a365; margin: 5px 0 0 0; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; }

        /* Body */
        .body { padding: 40px; }
        .greeting { font-size: 20px; font-weight: bold; color: #333333; margin-bottom: 20px; }
        .text { font-size: 16px; line-height: 1.6; color: #51545E; margin-bottom: 20px; }
        
        /* Booking Box */
        .booking-box { background-color: #f8fafc; border: 2px dashed #c5a365; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; }
        .booking-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .booking-code { font-size: 32px; font-weight: bold; color: #001a4d; margin: 10px 0; letter-spacing: 2px; font-family: monospace; }
        
        /* Detail Table */
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .detail-table th { text-align: left; padding: 12px; border-bottom: 1px solid #eee; color: #888; font-size: 12px; text-transform: uppercase; width: 40%; }
        .detail-table td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-weight: 600; }

        /* Footer */
        .footer { background-color: #f4f4f7; padding: 30px; text-align: center; font-size: 12px; color: #a8aaaf; }
        .btn { display: inline-block; background-color: #001a4d; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="wrapper">
        <table role="presentation">
            <tr>
                <td>
                    <div class="content">
                        
                        <div class="header">
                            <h1>SkinLogic</h1>
                            <p>Beauty Clinic</p>
                        </div>

                        <div class="body">
                            <div class="greeting">Halo, {{ $reservation->pelanggan->Nama }}!</div>
                            
                            <p class="text">
                                Terima kasih telah melakukan reservasi di SkinLogic. Reservasi Anda telah kami terima dan saat ini berstatus 
                                <strong style="color: #eab308;">{{ ucfirst($reservation->status) }}</strong>.
                            </p>
                            <p class="text">
                                Silakan simpan kode booking di bawah ini untuk melakukan Check-in saat kedatangan:
                            </p>

                            <div class="booking-box">
                                <div class="booking-label">Kode Booking Anda</div>
                                <div class="booking-code">
                                    RES-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                                <div style="font-size: 12px; color: #666;">Tunjukkan kepada resepsionis</div>
                            </div>

                            <p style="font-weight: bold; margin-bottom: 10px;">Rincian Jadwal:</p>
                            <table class="detail-table">
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->tanggal_reservasi)->translatedFormat('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jam</th>
                                    <td>{{ $reservation->jam_reservasi }} WIB</td>
                                </tr>
                                <tr>
                                    <th>Terapis</th>
                                    <td>{{ $reservation->terapis->name ?? '-' }}</td>
                                </tr>
                            </table>

                            <p style="font-weight: bold; margin-bottom: 10px; margin-top: 30px;">Layanan Dipilih:</p>
                            <table style="width: 100%; margin-bottom: 20px;">
                                @foreach($reservation->perawatans as $perawatan)
                                <tr>
                                    <td style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                        {{ $perawatan->Nama_Perawatan }}
                                        <div style="font-size: 11px; color: #888;">Qty: {{ $perawatan->pivot->qty }}</div>
                                    </td>
                                    <td style="padding: 8px 0; border-bottom: 1px solid #f0f0f0; text-align: right;">
                                        Rp {{ number_format($perawatan->pivot->harga * $perawatan->pivot->qty, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="padding: 15px 0; font-weight: bold; color: #001a4d;">TOTAL ESTIMASI</td>
                                    <td style="padding: 15px 0; font-weight: bold; text-align: right; color: #001a4d; font-size: 18px;">
                                        Rp {{ number_format($reservation->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>

                            <p class="text" style="font-size: 13px; margin-top: 30px;">
                                *Harap datang 15 menit sebelum jadwal perawatan.<br>
                                *Pembatalan maksimal H-1 sebelum jadwal.
                            </p>

                            <center>
                                <a href="{{ route('home') }}" class="btn">Kunjungi Website Kami</a>
                            </center>
                        </div>

                        <div class="footer">
                            <p>&copy; {{ date('Y') }} SkinLogic Beauty Clinic. All rights reserved.</p>
                            <p>Jl. Contoh No. 123, Kota Anda, Indonesia</p>
                            <p style="margin-top: 10px; font-size: 10px;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>