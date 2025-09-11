<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Produk::class;
    public function definition(): array
    {
        return [
            "Nomor_SKU" => fake()->uuid(),
            "Nama" => fake()->word(),
            "Harga" => fake()->randomNumber(5, true),
            "Stok" => fake()->randomDigit()
        ];
    }
}
