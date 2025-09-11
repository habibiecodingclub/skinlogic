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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string("Nama");
            $table->string("Pekerjaan");
            $table->string("Nomor_Telepon");
            $table->date("Tanggal_Lahir");
            $table->string("Email");
            $table->enum("Status", ["Member", "Non Member"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
