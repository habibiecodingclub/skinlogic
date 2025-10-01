<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_bundling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_bundling_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('qty')->default(1)->comment('Jumlah produk dalam bundling');
            $table->decimal('harga_satuan', 15, 0)->default(0)->comment('Harga saat ditambahkan ke bundling');
            $table->text('keterangan')->nullable()->comment('Keterangan penggunaan produk dalam bundling');
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['produk_bundling_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_bundling');
    }
};
