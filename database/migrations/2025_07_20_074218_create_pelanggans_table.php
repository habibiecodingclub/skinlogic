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
            $table->string("Pekerjaan")->nullable();
            $table->string("Nomor_Telepon")->nullable();
            $table->date("Tanggal_Lahir")->nullable();
            $table->string("Email")->nullable();
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
