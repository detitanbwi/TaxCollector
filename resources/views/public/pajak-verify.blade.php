<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Verifikasi Keabsahan Pajak - Bapenda Digital</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- TailwindCSS / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Secure Header -->
    <header class="bg-indigo-600 text-white shadow-sm sticky top-0 z-50 py-3.5 border-b border-indigo-700/30">
        <div class="px-4 max-w-lg mx-auto w-full flex items-center justify-center">
            <div class="flex items-center gap-2">
                <!-- App Logo -->
                <img src="{{ asset('logo-pajak.png') }}" alt="Logo" class="h-8 w-auto rounded-full object-cover bg-white">
                <div>
                    <div class="font-black text-xs uppercase tracking-wider leading-none">BAPENDA DIGITAL</div>
                    <div class="text-[9px] text-indigo-200 font-medium tracking-tight">Sistem Verifikasi Keabsahan Tagihan</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 w-full max-w-lg mx-auto p-4 sm:p-6 flex flex-col justify-center">

        @if($pajak)
            <!-- Skenario: Data Ditemukan & Terverifikasi -->
            
            <!-- Glowing status card -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                
                <!-- Status Header Banner -->
                <div class="p-6 bg-emerald-50/50 border-b border-emerald-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100/70 text-emerald-600 flex items-center justify-center border-4 border-white shadow-sm mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-base font-black text-emerald-800 uppercase tracking-wider">DOKUMEN RESMI TERVERIFIKASI</h2>
                    <p class="text-[11px] text-emerald-600 font-medium mt-1 leading-relaxed max-w-[280px]">
                        Sistem mencatat data penagihan di bawah ini adalah sah dan diterbitkan secara resmi oleh Badan Pendapatan Daerah.
                    </p>
                </div>

                <!-- Detail Tagihan Content -->
                <div class="p-6 flex flex-col gap-5">
                    
                    <!-- Section 1: Identitas & Kendaraan -->
                    <div>
                        <h3 class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Data Kendaraan Terdaftar</h3>
                        
                        <div class="grid grid-cols-2 gap-x-4 gap-y-3.5 bg-slate-50/60 border border-slate-100/70 rounded-2xl p-4">
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Nama Pemilik (Disamarkan)</span>
                                @php
                                    $maskedName = '-';
                                    if ($pajak->nama_pemilik) {
                                        $len = strlen($pajak->nama_pemilik);
                                        if ($len <= 2) {
                                            $maskedName = str_repeat('*', $len);
                                        } else {
                                            $maskedName = substr($pajak->nama_pemilik, 0, 1) . str_repeat('*', $len - 2) . substr($pajak->nama_pemilik, -1);
                                        }
                                    }
                                @endphp
                                <span class="text-xs font-black text-slate-800 uppercase">{{ $maskedName }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Nomor Polisi</span>
                                <span class="text-xs font-black text-slate-900 uppercase">{{ $pajak->nopol }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Jenis Kendaraan</span>
                                <span class="text-xs font-bold text-slate-800 capitalize">{{ $pajak->jenis_kendaraan ?: '-' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Merek / Tipe</span>
                                <span class="text-xs font-bold text-slate-800">{{ $pajak->merek_nama ?: '-' }} {{ $pajak->merek_type ?: '' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Tahun Buat</span>
                                <span class="text-xs font-bold text-slate-800">{{ $pajak->th_buat ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Jatuh Tempo Pajak</span>
                                <span class="text-xs font-black text-indigo-600">{{ $pajak->masa_laku ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Rincian Tunggakan Tagihan -->
                    <div>
                        <h3 class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Detail Kewajiban Nominal</h3>
                        
                        <div class="bg-indigo-50/40 border border-indigo-100/50 rounded-2xl p-4 flex flex-col gap-3">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-600 font-medium">Pajak Kendaraan Bermotor (PKB)</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($pajak->pkb, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-600 font-medium">Opsen PKB</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($pajak->opsen, 0, ',', '.') }}</span>
                            </div>
                            <hr class="border-indigo-100/60 my-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-xs font-black text-indigo-950 uppercase tracking-wider block">Total Tagihan Resmi</span>
                                    <span class="text-[10px] text-slate-400 font-semibold block">Sudah termasuk denda/biaya</span>
                                </div>
                                <span class="text-xl font-black text-indigo-600">Rp {{ number_format($pajak->nominal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Status Tagihan -->
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <span class="text-xs text-slate-500 font-medium">Status Dokumen</span>
                        <div class="flex items-center gap-1.5">
                            @if($pajak->is_ditagih)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">
                                    PENAGIHAN AKTIF
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/60 uppercase tracking-wider">
                                    MENUNGGU VERIFIKASI
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer disclaimer -->
                <div class="p-5 bg-slate-50/50 border-t border-slate-100 text-center text-[10px] text-slate-400 leading-relaxed font-medium">
                    Halaman verifikasi ini sah dan terlindungi enkripsi SSL aman. Dibuat otomatis oleh Sistem Pajak PBB/PKB Digital Samsat Pemerintah Daerah.
                </div>
            </div>
            
        @else
            <!-- Skenario: Data Tidak Ditemukan -->
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6 p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center border-4 border-white shadow-sm mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h2 class="text-base font-black text-red-800 uppercase tracking-wider">DOKUMEN TIDAK VALID / DATA TIDAK DITEMUKAN</h2>
                
                <div class="my-4 bg-slate-50 border border-slate-100 rounded-2xl p-4 w-full">
                    <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Nomor Polisi Yang Dicari</span>
                    <span class="text-sm font-black text-slate-800 uppercase">{{ $nopol }}</span>
                </div>

                <p class="text-xs text-slate-500 leading-relaxed max-w-[300px]">
                    Kami tidak dapat menemukan data penagihan pajak aktif untuk Nomor Polisi tersebut di sistem kami.
                </p>
                <p class="text-xs text-slate-400 mt-3 leading-relaxed max-w-[300px]">
                    Pastikan format penulisan nomor polisi sudah benar atau silakan hubungi kantor pelayanan Samsat terdekat untuk verifikasi manual.
                </p>

                <div class="mt-6 w-full">
                    <a href="https://namainstansi.go.id" class="inline-flex items-center justify-center w-full h-11 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                        Kunjungi Portal Resmi
                    </a>
                </div>
            </div>

        @endif

    </main>

    <!-- Bottom Footer -->
    <footer class="bg-white border-t border-slate-100 py-4 text-center text-xxs text-slate-400 tracking-tight">
        &copy; {{ date('Y') }} Badan Pendapatan Daerah - Dinas Pelayanan Pajak Daerah Terintegrasi
    </footer>

</body>
</html>
