@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Pengaturan WhatsApp</h1>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Format Pesan WhatsApp</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="whatsapp_format" class="block text-sm font-medium text-slate-700 mb-2">Pesan (Gunakan variabel untuk data dinamis)</label>
                    <textarea name="whatsapp_format" id="whatsapp_format" rows="15" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm p-4" required>{{ old('whatsapp_format', $whatsappFormat) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-sm transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
        <h3 class="text-md font-bold text-indigo-900 mb-3">Variabel yang Tersedia:</h3>
        <p class="text-sm text-indigo-700 mb-4">Anda dapat menggunakan variabel berikut di dalam pesan. Variabel akan otomatis diganti dengan data wajib pajak saat petugas penagih mengirim pesan.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{nama}</code>
                <span class="text-indigo-600 text-right">Nama Pemilik Kendaraan</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{nopol}</code>
                <span class="text-indigo-600 text-right">Nomor Polisi (Nopol)</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{merek}</code>
                <span class="text-indigo-600 text-right">Merek Kendaraan</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{type}</code>
                <span class="text-indigo-600 text-right">Tipe Kendaraan</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{th}</code>
                <span class="text-indigo-600 text-right">Tahun Buat</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{jenis}</code>
                <span class="text-indigo-600 text-right">Jenis Kendaraan</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{pkb}</code>
                <span class="text-indigo-600 text-right">Nilai PKB (Format Rp)</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{opsen}</code>
                <span class="text-indigo-600 text-right">Nilai Opsen (Format Rp)</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{nominal}</code>
                <span class="text-indigo-600 text-right">Total Tagihan (Format Rp)</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{masa_laku}</code>
                <span class="text-indigo-600 text-right">Masa Laku Pajak</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{masa_stnk}</code>
                <span class="text-indigo-600 text-right">Masa Laku STNK</span>
            </div>
            <div class="flex justify-between border-b border-indigo-200/50 pb-1">
                <code class="font-bold text-indigo-800">{link_keabsahan}</code>
                <span class="text-indigo-600 text-right">Tautan Keabsahan Dokumen (Otomatis)</span>
            </div>
        </div>
    </div>
</div>
@endsection
