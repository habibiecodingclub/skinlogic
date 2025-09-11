<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Pelanggan::class;
    public function definition(): array
    {
        return [
            "Nama" => $this->faker->name(),
            "Pekerjaan" => $this->faker->jobTitle(),
            "Nomor_Telepon" => $this->faker->phoneNumber(),
            "Tanggal_lahir" => $this->faker->date(),
            "Email" => $this->faker->email(),
            "Status" => $this->faker->randomElement(["Member" ,"Non Member"])
        ];
    }
}
