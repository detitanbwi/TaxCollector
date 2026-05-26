@extends('layouts.penagih')

@section('content')

<!-- Search Card -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-slate-900 mb-4 text-center">Pencarian Tagihan</h2>
    <form action="{{ route('penagih.search') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        <div>
            <label for="nopol" class="sr-only">Nomor Polisi</label>
            <input type="text" name="nopol" id="nopol" value="{{ old('nopol', $nopol ?? '') }}" 
                placeholder="Masukkan Nopol (Misal: B1234XYZ)" 
                class="w-full text-center text-xl font-bold uppercase tracking-wider py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-0 transition-colors placeholder:text-slate-300 placeholder:font-normal placeholder:text-base placeholder:tracking-normal"
                required autocomplete="off">
        </div>
        <button type="submit" class="w-full bg-indigo-600 active:bg-indigo-700 text-white font-bold text-lg py-4 rounded-xl shadow-md transition-transform active:scale-[0.98]">
            CARI TAGIHAN
        </button>
    </form>
</div>

<!-- Result Card -->
@if(isset($pajak))
<div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden relative" x-data="twinAction({{ $pajak->id }}, '{{ $pajak->nopol }}', '{{ addslashes($pajak->nama_pemilik) }}', {{ $pajak->nominal }}, {{ $pajak->pkb }}, {{ $pajak->opsen }}, '{{ addslashes($pajak->jenis_kendaraan) }}', '{{ addslashes($pajak->merek_nama) }}', '{{ addslashes($pajak->merek_type) }}', '{{ $pajak->th_buat }}', '{{ $pajak->masa_laku }}')">
    
    <div class="absolute top-0 right-0 left-0 h-2 bg-indigo-600"></div>
    
    <div class="p-6 pt-8 space-y-4">
        
        <div class="flex justify-between items-start border-b border-slate-100 pb-4">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Nopol</p>
                <p class="text-2xl font-black text-slate-900">{{ $pajak->nopol }}</p>
            </div>
            <div>
                @if($pajak->is_ditagih)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        SUDAH DITAGIH
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200" id="status-badge-{{ $pajak->id }}">
                        BELUM DITAGIH
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 py-3 border-b border-slate-100 text-sm">
            <div class="col-span-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nama Pemilik</p>
                <p class="text-base font-black text-slate-800">{{ $pajak->nama_pemilik }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jenis Kendaraan</p>
                <p class="text-sm font-bold text-slate-700 uppercase">{{ $pajak->jenis_kendaraan ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Merek & Tipe</p>
                <p class="text-sm font-bold text-slate-700">{{ $pajak->merek_nama ?: '-' }} {{ $pajak->merek_type ?: '' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tahun Buat</p>
                <p class="text-sm font-bold text-slate-700">{{ $pajak->th_buat ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor HP Warga</p>
                <p class="text-sm font-bold text-slate-700">{{ $pajak->nomor_hp ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Masa Berlaku STNK</p>
                <p class="text-sm font-bold text-slate-700">{{ $pajak->masa_stnk ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Masa Laku Pajak</p>
                <p class="text-sm font-bold text-slate-700 text-indigo-600">{{ $pajak->masa_laku ?: '-' }}</p>
            </div>
        </div>

        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex flex-col gap-2 shadow-inner">
            <div class="flex justify-between items-center text-xs text-slate-500 font-bold">
                <span>Pajak Kendaraan Bermotor (PKB):</span>
                <span>Rp {{ number_format($pajak->pkb, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-500 font-bold border-b border-slate-200 pb-2">
                <span>Opsen PKB:</span>
                <span>Rp {{ number_format($pajak->opsen, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center pt-1">
                <span class="text-sm font-black text-slate-900">Total Tagihan Pajak:</span>
                <span class="text-xl font-black text-indigo-650">Rp {{ number_format($pajak->nominal, 0, ',', '.') }}</span>
            </div>
        </div>

        <button @click="sendWhatsApp()" type="button" class="w-full flex items-center justify-center gap-2 bg-[#25D366] active:bg-[#128C7E] text-white font-bold text-lg py-4 rounded-xl shadow-lg transition-transform active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            Kirim Tagihan ke WhatsApp
        </button>

    </div>
</div>
@endif

<!-- Alpine.js logic -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function twinAction(id, nopol, nama, nominal, pkb, opsen, jenis, merek, type, th, masa_laku) {
        return {
            async sendWhatsApp() {
                const formattedNominal = new Intl.NumberFormat('id-ID').format(nominal);
                const formattedPkb = new Intl.NumberFormat('id-ID').format(pkb);
                const formattedOpsen = new Intl.NumberFormat('id-ID').format(opsen);
                const baseUrl = 'https://namainstansi.go.id/pajak/tagihan/'; 
                
                const message = `Selamat pagi/siang Bapak/Ibu ${nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: ${nopol}\n• Kendaraan: ${merek} ${type} (${th})\n• PKB: Rp ${formattedPkb}\n• Opsen: Rp ${formattedOpsen}\n---------------------------\n• Total Tagihan: Rp ${formattedNominal}\n• Masa Laku s.d: ${masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\n${baseUrl}${nopol}`;
                
                const waUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;

                // Perform AJAX request to update status
                try {
                    const response = await fetch(`/penagih/update-status/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Update UI Optimistically
                        const badge = document.getElementById(`status-badge-${id}`);
                        if (badge) {
                            badge.className = "inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200";
                            badge.textContent = "SUDAH DITAGIH";
                        }
                    }
                } catch (error) {
                    console.error("Failed to update status:", error);
                }

                // Open WA Link in new tab
                window.open(waUrl, '_blank');
            }
        }
    }
</script>
@endsection
