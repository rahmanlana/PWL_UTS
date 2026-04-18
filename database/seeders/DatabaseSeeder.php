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
        // Level
        $adminLevel = Level::create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        Level::create(['level_kode' => 'KSR', 'level_nama' => 'Kasir']);
        Level::create(['level_kode' => 'MGR', 'level_nama' => 'Manager']);

        // User Admin
        User::create([
            'level_id' => $adminLevel->level_id,
            'username' => 'admin',
            'nama'     => 'Administrator',
            'password' => Hash::make('admin123'),
        ]);

        // Kategori
        Kategori::create(['kategori_kode' => 'MKN', 'kategori_nama' => 'Makanan']);
        Kategori::create(['kategori_kode' => 'MNM', 'kategori_nama' => 'Minuman']);

        // Supplier
        Supplier::create([
            'supplier_kode'   => 'SUP001',
            'supplier_nama'   => 'Supplier Utama',
            'supplier_alamat' => 'Jl. Contoh No. 1',
        ]);
    }
}
