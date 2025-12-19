<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Informasi pelanggan
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');

            // Informasi reservasi
            $table->date('tanggal_reservasi');
            $table->time('jam_reservasi');
            $table->text('catatan')->nullable();

            // Status reservasi
            $table->enum('status', [
                'menunggu',      // Baru dibuat, menunggu konfirmasi
                'dikonfirmasi',  // Sudah dikonfirmasi staff
                'dikerjakan',    // Sedang dalam proses perawatan
                'selesai',       // Perawatan selesai
                'batal'         // Reservasi dibatalkan
            ])->default('menunggu');

            // Informasi terapis/staff
            $table->foreignId('terapis_id')->nullable()->constrained('users')->onDelete('set null');

            // Link ke pesanan jika sudah selesai
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->onDelete('set null');

            $table->timestamps();
        });

        // Tabel pivot untuk relasi many-to-many reservasi-perawatan
        Schema::create('reservation_perawatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('perawatan_id')->constrained('perawatans')->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->decimal('harga', 15, 0); // Harga saat reservasi
            $table->timestamps();

            $table->unique(['reservation_id', 'perawatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_perawatan');
        Schema::dropIfExists('reservations');
    }
};
