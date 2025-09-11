<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ]);

        Pelanggan::factory()->count(10)->create();

        Produk::factory()->count(10)->create();

        Perawatan::factory()->count(5)->create();

        Pesanan::factory()->count(30)->create();
    }
}
