<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PajakTagihan extends Model
{
    protected $fillable = [
        'nopol',
        'nama_pemilik',
        'jenis_kendaraan',
        'merek_nama',
        'merek_type',
        'th_buat',
        'pkb',
        'opsen',
        'nominal',
        'masa_laku',
        'masa_stnk',
        'nomor_hp',
        'is_ditagih',
    ];

    protected function casts(): array
    {
        return [
            'is_ditagih' => 'boolean',
        ];
    }
}
