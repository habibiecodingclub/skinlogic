<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;

class PesananProdukFactory extends Factory
{
    public function definition(): array
    {
        $produk = Produk::inRandomOrder()->first();

        // Jika tidak ada produk, buat satu
        if (!$produk) {
            $produk = Produk::factory()->create([
                'Harga' => $this->faker->numberBetween(10000, 1000000)
            ]);
        }

        return [
            'pesanan_id' => null,
            'produk_id' => $produk->id,
            'qty' => $this->faker->numberBetween(1, 10),
            'harga' => $produk->Harga,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}

