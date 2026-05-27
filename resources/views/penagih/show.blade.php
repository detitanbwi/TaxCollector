@extends('layouts.penagih')

@section('content')

<!-- Back Button -->
<div class="mb-5">
    <a href="{{ route('penagih.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-650 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Kembali ke Daftar</span>
    </a>
</div>

<!-- Main Detail Card -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
    <!-- Header Section -->
    <div class="p-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Nomor Polisi</span>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-wide">{{ $pajak->nopol }}</h1>
        </div>
        <div id="status-container">
            @if($pajak->is_ditagih)
                <span id="status-badge" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">
                    SUDAH DITAGIH
                </span>
            @else
                <span id="status-badge" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/60 uppercase tracking-wider">
                    BELUM DITAGIH
                </span>
            @endif
        </div>
    </div>

    <!-- Data Details Section -->
    <div class="p-6 flex flex-col gap-6">
        <!-- Section: Pemilik -->
        <div>
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Warga & Kontak</h3>
            <div class="space-y-3.5">
                <div class="flex justify-between items-start gap-4">
                    <span class="text-xs text-slate-500 font-medium">Nama Pemilik</span>
                    <span class="text-xs font-bold text-slate-800 text-right">{{ $pajak->nama_pemilik }}</span>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <span class="text-xs text-slate-500 font-medium">Nomor HP</span>
                    @if($pajak->nomor_hp)
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-800">{{ $pajak->nomor_hp }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $pajak->nomor_hp }}'); alert('Nomor HP berhasil disalin!');" class="p-1 text-slate-400 hover:text-indigo-600 transition-colors" title="Salin Nomor HP">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <span class="text-xs font-semibold text-slate-400 italic">Belum diset</span>
                    @endif
                </div>
                @if($pajak->alamat)
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-xs text-slate-500 font-medium">Alamat</span>
                        <span class="text-xs font-semibold text-slate-700 text-right max-w-[200px] leading-relaxed">{{ $pajak->alamat }}</span>
                    </div>
                @endif
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Section: Kendaraan -->
        <div>
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Spesifikasi Kendaraan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Jenis</span>
                    <span class="text-xs font-bold text-slate-800 capitalize">{{ $pajak->jenis_kendaraan ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Merek / Tipe</span>
                    <span class="text-xs font-bold text-slate-800">{{ $pajak->merek_nama ?: '-' }} {{ $pajak->merek_type ?: '' }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Tahun Buat</span>
                    <span class="text-xs font-bold text-slate-800">{{ $pajak->th_buat ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold block mb-0.5">Jatuh Tempo Pajak</span>
                    <span class="text-xs font-bold text-indigo-650">{{ $pajak->masa_laku ?: '-' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Section: Tagihan Pajak -->
        <div>
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Rincian Tunggakan Pajak</h3>
            
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
                        <span class="text-xs font-black text-indigo-950 uppercase tracking-wider block">Total Tagihan</span>
                        <span class="text-[10px] text-slate-400 font-semibold block">Sudah termasuk denda/biaya</span>
                    </div>
                    <span class="text-xl font-black text-indigo-650">Rp {{ number_format($pajak->nominal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Action Footer -->
    <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex flex-col gap-3">
        <button onclick="sendWhatsAppDetail({{ $pajak->id }}, '{{ $pajak->nopol }}', '{{ addslashes($pajak->nama_pemilik) }}', {{ $pajak->nominal }}, {{ $pajak->pkb }}, {{ $pajak->opsen }}, '{{ addslashes($pajak->jenis_kendaraan) }}', '{{ addslashes($pajak->merek_nama) }}', '{{ addslashes($pajak->merek_type) }}', '{{ $pajak->th_buat }}', '{{ $pajak->masa_laku }}', '{{ $pajak->nomor_hp }}', {{ json_encode($whatsappFormat ?? '') }})" type="button" class="w-full h-12 rounded-xl bg-[#25D366] hover:bg-[#20ba59] active:bg-[#1caa4f] text-white font-bold text-sm shadow-md shadow-[#25D366]/10 hover:shadow-[#25D366]/25 transition-all flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            <span>Kirim Tagihan via WhatsApp</span>
        </button>
    </div>
</div>

<script>
    async function sendWhatsAppDetail(id, nopol, nama, nominal, pkb, opsen, jenis, merek, type, th, masa_laku, nomor_hp, templateFormat) {
        const formattedNominal = new Intl.NumberFormat('id-ID').format(nominal);
        const formattedPkb = new Intl.NumberFormat('id-ID').format(pkb);
        const formattedOpsen = new Intl.NumberFormat('id-ID').format(opsen);
        
        let message = templateFormat || `Selamat pagi/siang Bapak/Ibu \${nama},\n\nMengingatkan kewajiban Pajak Kendaraan Bermotor (PKB) untuk Kendaraan Anda:\n\n• Nopol: \${nopol}\n• Kendaraan: \${merek} \${type} (\${th})\n• PKB: Rp \${formattedPkb}\n• Opsen: Rp \${formattedOpsen}\n---------------------------\n• Total Tagihan: Rp \${formattedNominal}\n• Masa Laku s.d: \${masa_laku}\n\nMohon untuk segera melakukan pembayaran sebelum jatuh tempo untuk menghindari sanksi administratif.\n\nPembayaran dapat dilakukan di Loket Samsat, Indomaret, Alfamart, atau platform online resmi.\n\nLink keabsahan tagihan:\n\${window.location.origin}/pajak/tagihan/\${nopol}`;
        
        const verificationUrl = window.location.origin + '/pajak/tagihan/' + nopol;

        // Replace variables
        message = message.replace(/https:\/\/namainstansi\.go\.id\/pajak\/tagihan\/{nopol}/g, verificationUrl)
                         .replace(/https:\/\/namainstansi\.go\.id\/pajak\/tagihan\/\{nopol\}/g, verificationUrl)
                         .replace(/{link_keabsahan}/g, verificationUrl)
                         .replace(/{nopol}/g, nopol)
                         .replace(/{nama}/g, nama)
                         .replace(/{merek}/g, merek)
                         .replace(/{type}/g, type)
                         .replace(/{th}/g, th)
                         .replace(/{jenis}/g, jenis)
                         .replace(/{pkb}/g, formattedPkb)
                         .replace(/{opsen}/g, formattedOpsen)
                         .replace(/{nominal}/g, formattedNominal)
                         .replace(/{masa_laku}/g, masa_laku);

        // Format nomor HP penerima ke standar internasional (085xxx -> 6285xxx)
        let cleanPhone = (nomor_hp || '').replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.substring(1);
        }

        const waUrl = cleanPhone 
            ? `https://api.whatsapp.com/send?phone=${cleanPhone}&text=${encodeURIComponent(message)}`
            : `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;

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
                const badge = document.getElementById('status-badge');
                if (badge) {
                    badge.className = "inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider";
                    badge.textContent = "SUDAH DITAGIH";
                }
            }
        } catch (error) {
            console.error("Failed to update status:", error);
        }

        // Open WA Link in new tab
        window.open(waUrl, '_blank');
    }
</script>

@endsection
