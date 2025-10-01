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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->uuid("Nomor_SKU")->unique();
            $table->string("Nama");
            $table->boolean('is_bundling')->default(false);
            $table->decimal('harga_bundling', 15, 0)->nullable()->comment('Harga khusus untuk bundling');
            $table->decimal("Harga", 15, 0)->default(0);
            $table->integer("Stok")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
        // Schema::table('produks', function (Blueprint $table) {
        //     $table->dropColumn(['is_bundling', 'harga_bundling']);
        // });
    }
};
