<?php

namespace App\Imports;

use App\Models\PajakTagihan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PajakTagihanImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PajakTagihan([
            'nopol' => $row['nopol'],
            'nama_pemilik' => $row['nama_pemilik'],
            'alamat' => $row['alamat'],
            'nominal' => $row['nominal'],
            'is_ditagih' => false,
        ]);
    }

    public function uniqueBy()
    {
        return 'nopol';
    }
}
