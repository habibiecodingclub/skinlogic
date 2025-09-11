<?php

namespace Database\Factories;

use App\Models\Perawatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perawatan>
 */
class PerawatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Perawatan::class;
    public function definition(): array
    {
        return [
            //
            "Nama_Perawatan" => fake()->word(),
            "Harga" => fake()->randomNumber(5, true)
        ];
    }
}
