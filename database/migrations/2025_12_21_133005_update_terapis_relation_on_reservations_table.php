<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- JANGAN LUPA TAMBAHKAN INI

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 1. Hapus Foreign Key lama yang nyambung ke users
            // Cek nama constraint di database kamu, biasanya: reservations_terapis_id_foreign
            $table->dropForeign(['terapis_id']);
        });

        // 2. PENTING: Ubah data terapis_id lama jadi NULL dulu
        // Supaya tidak error "Integrity constraint violation"
        DB::table('reservations')->update(['terapis_id' => null]);

        Schema::table('reservations', function (Blueprint $table) {
            // 3. Sekarang aman untuk membuat relasi baru ke tabel terapis
            $table->foreign('terapis_id')
                  ->references('id')
                  ->on('terapis')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Hapus relasi ke terapis
            $table->dropForeign(['terapis_id']);
        });

        // Kosongkan lagi sebelum balik ke users (untuk jaga-jaga)
        DB::table('reservations')->update(['terapis_id' => null]);

        Schema::table('reservations', function (Blueprint $table) {
            // Kembalikan relasi ke users
            $table->foreign('terapis_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};