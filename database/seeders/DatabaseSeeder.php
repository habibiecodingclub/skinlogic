<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manajer']);
        $kasirRole   = Role::firstOrCreate(['name' => 'kasir']);

        // Buat user + assign role
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'), // gunakan bcrypt
        ]);
        $admin->assignRole($adminRole);

        $manager = User::factory()->create([
            'name' => 'Manajer User',
            'email' => 'manajer@gmail.com',
            'password' => bcrypt('manajer'),
        ]);
        $manager->assignRole($managerRole);
        // Data dummy lain
        Pelanggan::factory()->count(10)->create();
        Produk::factory()->count(10)->create();
        Perawatan::factory()->count(10)->create();
        Pesanan::factory()->count(30)->create();

        $kasir = User::factory()->create([
            'name' => 'Kasir User',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('kasir'),
        ]);
        $kasir->assignRole($kasirRole);

        $terapisRole = Role::firstOrCreate(['name' => 'terapis', 'guard_name' => 'web']);

    // Berikan permission untuk terapis
    // $terapisRole->givePermissionTo([
    //     'view reservation',
    //     'edit reservation',
    //     'create reservation',
    // ]);


    }
}
