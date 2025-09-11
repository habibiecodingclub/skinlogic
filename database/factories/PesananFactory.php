<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pelanggan;

class PesananFactory extends Factory
{
    public function definition(): array
    {
        // Pastikan pelanggan ada atau buat baru jika tidak ada
        $pelanggan = Pelanggan::inRandomOrder()->first() ?? Pelanggan::factory()->create();

        return [
            'pelanggan_id' => $pelanggan->id,
            'Metode_Pembayaran' => $this->faker->randomElement(['Cash', 'QRIS', 'Debit']),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($pesanan) {
            // Buat 1-5 produk untuk setiap pesanan
            $jumlahProduk = rand(1, 5);

            \App\Models\PesananProduk::factory()
                ->count($jumlahProduk)
                ->create(['pesanan_id' => $pesanan->id]);
        });
    }
}
