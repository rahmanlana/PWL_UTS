<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Level;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Supplier;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Level::create(['level_kode' => 'SAD', 'level_nama' => 'Super Admin']);
        $admin      = Level::create(['level_kode' => 'ADM', 'level_nama' => 'Admin']);

        User::create([
            'level_id' => $superAdmin->level_id,
            'username' => 'superadmin',
            'nama'     => 'Super Admin',
            'password' => Hash::make('superadmin123'),
        ]);

        User::create([
            'level_id' => $admin->level_id,
            'username' => 'admin',
            'nama'     => 'Admin',
            'password' => Hash::make('admin123'),
        ]);

        Kategori::create(['kategori_kode' => 'MKN', 'kategori_nama' => 'Makanan']);
        Kategori::create(['kategori_kode' => 'MNM', 'kategori_nama' => 'Minuman']);

        Supplier::create([
            'supplier_kode'   => 'SUP001',
            'supplier_nama'   => 'Supplier Utama',
            'supplier_alamat' => 'Jl. Contoh No. 1',
        ]);
    }
}
