<?php

namespace App\Http\Controllers;

use App\Models\PajakTagihan;
use App\Imports\PajakTagihanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PajakTagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = PajakTagihan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            $sort = $request->sort;
            $order = $request->order ?? 'asc';
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Clone query to get all matching IDs (before pagination) and cast to string to match JavaScript types
        $allIds = (clone $query)->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $pajak = $query->paginate(20)->withQueryString();

        return view('admin.pajak.index', compact('pajak', 'allIds'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return [
                    'NOPOL',
                    'NAMA',
                    'JENIS KENDARAAN',
                    'MEREK NAMA',
                    'MEREK TYPE',
                    'TH BUAT',
                    'PKB',
                    'OPSEN',
                    'PKB + OPSEN',
                    'MASA LAKU',
                    'MASA STNK',
                    'NOMOR HP'
                ];
            }
            public function array(): array
            {
                return [
                    [
                        'S-8207-AH',
                        'BUDI',
                        'TRUCK',
                        'NISSAN',
                        'PK 260L',
                        2009,
                        3378000,
                        2229500,
                        5607500,
                        '30/08/2025',
                        '30/08/2029',
                        '085655476262'
                    ]
                ];
            }
        }, 'template_impor_pajak.xlsx');
    }

    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt',
        ]);

        try {
            $array = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\WithHeadingRow, \Maatwebsite\Excel\Concerns\SkipsEmptyRows {
                public function isEmptyWhen(array $row): bool
                {
                    // Hanya lewati baris yang benar-benar kosong (semua kolom bernilai null atau string kosong)
                    return empty(array_filter($row, function ($value) {
                        return $value !== null && trim($value) !== '';
                    }));
                }
            }, $request->file('file'));
            
            $rows = $array[0] ?? [];
            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Berkas Excel/CSV kosong atau tidak dapat dibaca.'
                ], 422);
            }

            $firstRow = $rows[0];
            $required = [
                'nopol', 'nama', 'jenis_kendaraan', 'merek_nama', 'merek_type', 
                'th_buat', 'pkb', 'opsen', 'pkb_opsen', 'masa_laku', 'masa_stnk', 'nomor_hp'
            ];
            $columnStatus = [];
            $hasMissing = false;

            $friendlyNames = [
                'nopol' => 'NOPOL',
                'nama' => 'NAMA',
                'jenis_kendaraan' => 'JENIS KENDARAAN',
                'merek_nama' => 'MEREK NAMA',
                'merek_type' => 'MEREK TYPE',
                'th_buat' => 'TH BUAT',
                'pkb' => 'PKB',
                'opsen' => 'OPSEN',
                'pkb_opsen' => 'PKB + OPSEN',
                'masa_laku' => 'MASA LAKU',
                'masa_stnk' => 'MASA STNK',
                'nomor_hp' => 'NOMOR HP'
            ];

            foreach ($required as $col) {
                $status = array_key_exists($col, $firstRow);
                if (!$status) {
                    $hasMissing = true;
                }
                $columnStatus[] = [
                    'key' => $col,
                    'name' => $friendlyNames[$col],
                    'found' => $status
                ];
            }

            if ($hasMissing) {
                return response()->json([
                    'success' => false,
                    'validation' => true,
                    'columns' => $columnStatus,
                    'message' => 'Format kolom berkas tidak sesuai. Beberapa kolom wajib tidak ditemukan.'
                ], 422);
            }

            $previewData = collect($rows)->map(function ($row) {
                return [
                    'nopol' => trim($row['nopol'] ?? ''),
                    'nama_pemilik' => trim($row['nama'] ?? ''),
                    'jenis_kendaraan' => trim($row['jenis_kendaraan'] ?? ''),
                    'merek_nama' => trim($row['merek_nama'] ?? ''),
                    'merek_type' => trim($row['merek_type'] ?? ''),
                    'th_buat' => isset($row['th_buat']) ? (int) $row['th_buat'] : null,
                    'pkb' => isset($row['pkb']) ? (int) $row['pkb'] : 0,
                    'opsen' => isset($row['opsen']) ? (int) $row['opsen'] : 0,
                    'nominal' => isset($row['pkb_opsen']) ? (int) $row['pkb_opsen'] : 0,
                    'masa_laku' => (function($val) {
                        $val = trim($val ?? '');
                        if (is_numeric($val)) {
                            try {
                                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('d/m/Y');
                            } catch (\Exception $e) {}
                        }
                        return $val;
                    })($row['masa_laku'] ?? ''),
                    'masa_stnk' => (function($val) {
                        $val = trim($val ?? '');
                        if (is_numeric($val)) {
                            try {
                                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('d/m/Y');
                            } catch (\Exception $e) {}
                        }
                        return $val;
                    })($row['masa_stnk'] ?? ''),
                    'nomor_hp' => trim($row['nomor_hp'] ?? ''),
                ];
            })->filter(function ($row) {
                return !empty($row['nopol']) && !empty($row['nama_pemilik']);
            })->values()->all();

            if (empty($previewData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data valid yang dapat diimpor.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $previewData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses berkas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
            'data.*.nopol' => 'required|string',
            'data.*.nama_pemilik' => 'required|string',
            'data.*.jenis_kendaraan' => 'nullable|string',
            'data.*.merek_nama' => 'nullable|string',
            'data.*.merek_type' => 'nullable|string',
            'data.*.th_buat' => 'nullable|numeric',
            'data.*.pkb' => 'nullable|numeric',
            'data.*.opsen' => 'nullable|numeric',
            'data.*.nominal' => 'required|numeric',
            'data.*.masa_laku' => 'nullable|string',
            'data.*.masa_stnk' => 'nullable|string',
            'data.*.nomor_hp' => 'nullable|string',
        ]);

        try {
            $data = collect($request->input('data'))->map(function ($row) {
                return [
                    'nopol' => trim($row['nopol']),
                    'nama_pemilik' => trim($row['nama_pemilik']),
                    'jenis_kendaraan' => trim($row['jenis_kendaraan'] ?? ''),
                    'merek_nama' => trim($row['merek_nama'] ?? ''),
                    'merek_type' => trim($row['merek_type'] ?? ''),
                    'th_buat' => isset($row['th_buat']) ? (int) $row['th_buat'] : null,
                    'pkb' => isset($row['pkb']) ? (int) $row['pkb'] : 0,
                    'opsen' => isset($row['opsen']) ? (int) $row['opsen'] : 0,
                    'nominal' => (int) $row['nominal'],
                    'masa_laku' => trim($row['masa_laku'] ?? ''),
                    'masa_stnk' => trim($row['masa_stnk'] ?? ''),
                    'nomor_hp' => trim($row['nomor_hp'] ?? ''),
                    'is_ditagih' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                PajakTagihan::upsert($chunk, ['nopol'], [
                    'nama_pemilik', 'jenis_kendaraan', 'merek_nama', 'merek_type', 
                    'th_buat', 'pkb', 'opsen', 'nominal', 'masa_laku', 'masa_stnk', 'nomor_hp', 'updated_at'
                ]);
            }

            session()->flash('success', count($data) . ' data pajak berhasil diimpor.');

            return response()->json([
                'success' => true,
                'message' => count($data) . ' data pajak berhasil diimpor.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        PajakTagihan::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.pajak.index')->with('success', 'Data terpilih berhasil dihapus.');
    }

    public function verifyValidity($nopol)
    {
        // Strip spaces to ensure robust search regardless of URL formatting or spaces
        $cleanNopol = str_replace(' ', '', $nopol);
        
        $pajak = PajakTagihan::whereRaw("REPLACE(nopol, ' ', '') LIKE ?", [$cleanNopol])->first();

        return view('public.pajak-verify', compact('pajak', 'nopol'));
    }
}
