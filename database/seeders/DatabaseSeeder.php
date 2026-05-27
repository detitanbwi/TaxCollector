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
        // Reset cache if needed
        // \Illuminate\Support\Facades\Artisan::call('cache:clear');

        // Create default WhatsApp setting
        \App\Models\Setting::firstOrCreate(
            ['key' => 'whatsapp_format'],
            ['value' => "Selamat pagi/siang Bapak/Ibu {nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: {nopol}\n• Kendaraan: {merek} {type} ({th})\n• PKB: Rp {pkb}\n• Opsen: Rp {opsen}\n---------------------------\n• Total Tagihan: Rp {nominal}\n• Masa Laku s.d: {masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\nhttps://namainstansi.go.id/pajak/tagihan/{nopol}"]
        );

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
