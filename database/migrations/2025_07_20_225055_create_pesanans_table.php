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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pelanggan_id")->constrained()->cascadeOnDelete();
            $table->enum('Metode_Pembayaran', ['Cash', 'QRIS', 'Debit']);
            $table->enum('status', ['Berhasil', 'Dibatalkan'])->default('Berhasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
