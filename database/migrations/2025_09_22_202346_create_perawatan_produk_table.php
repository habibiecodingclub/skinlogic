<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perawatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('produk_id')->constrained()->onDelete('cascade');
            $table->integer('qty_digunakan')->default(1)->comment('Jumlah produk yang digunakan per perawatan');
            $table->text('keterangan')->nullable()->comment('Cara penggunaan, dll');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan_produk');
    }
};
