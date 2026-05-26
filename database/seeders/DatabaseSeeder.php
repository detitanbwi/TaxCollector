<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Penagih Account
        User::create([
            'name' => 'Petugas Penagih 1',
            'username' => 'penagih1',
            'password' => Hash::make('password'),
            'role' => 'penagih',
        ]);

        // Sample Pajak Tagihan (matching Excel screenshot)
        \App\Models\PajakTagihan::create([
            'nopol' => 'S-8207-AH',
            'nama_pemilik' => 'BUDI',
            'jenis_kendaraan' => 'TRUCK',
            'merek_nama' => 'NISSAN',
            'merek_type' => 'PK 260L',
            'th_buat' => 2009,
            'pkb' => 3378000,
            'opsen' => 2229500,
            'nominal' => 5607500,
            'masa_laku' => '30/08/2025',
            'masa_stnk' => '30/08/2029',
            'nomor_hp' => '085655476262',
            'is_ditagih' => false,
        ]);
    }
}
