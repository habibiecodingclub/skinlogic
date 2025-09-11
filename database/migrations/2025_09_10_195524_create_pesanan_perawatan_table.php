<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_perawatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('perawatan_id')->constrained()->cascadeOnDelete();
            $table->integer('qty')->default(1);
            $table->decimal('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_perawatan');
    }
};
