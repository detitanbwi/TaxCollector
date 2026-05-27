@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-bold text-slate-900">Data Pajak</h1>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pajak.download-template') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Contoh Excel
            </a>
            <label for="file" class="cursor-pointer px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Import Excel
            </label>
            <input type="file" name="file" id="file" class="hidden" accept=".xlsx, .csv" onchange="handleFileSelected(this)">
        </div>
    </div>
</div>

<div id="pajakTableWrapper">
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden" x-data="pajakTable()">
    
    <!-- Toolbar -->
    <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
        <form id="adminSearchForm" method="GET" action="{{ route('admin.pajak.index') }}" class="flex-1 max-w-md flex items-center gap-2">
            <input type="text" id="adminSearchInput" name="search" value="{{ request('search') }}" placeholder="Cari Nopol atau Nama Pemilik..." class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" autocomplete="off">
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm whitespace-nowrap">
                Cari
            </button>
        </form>

        <form action="{{ route('admin.pajak.bulk-delete') }}" method="POST" x-ref="deleteForm" @submit.prevent="showDeleteConfirm = true">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" :disabled="selectedIds.length === 0">
                Hapus <span x-text="selectedIds.length > 0 ? '(' + selectedIds.length + ')' : ''"></span>
            </button>
        </form>
    </div>

    <!-- Banner for Select All Matching -->
    <div x-show="showAllSelectionBanner()" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="bg-indigo-50 border-b border-slate-100 px-6 py-3 text-xs text-indigo-850 flex items-center justify-between gap-4 flex-wrap shadow-inner"
         style="display: none;">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-indigo-650 animate-pulse"></span>
            <span>
                <span x-text="isAllMatchingSelected ? 'Seluruh ' + totalRecords + ' data terpilih.' : 'Semua ' + currentPageIds.length + ' data pada halaman ini terpilih.'"></span>
            </span>
        </div>
        <button type="button" 
                @click="toggleAllMatching()" 
                class="font-bold underline text-indigo-700 hover:text-indigo-900 transition-colors focus:outline-none"
                x-text="isAllMatchingSelected ? 'Batalkan pilihan seluruh data' : 'Pilih seluruh (' + totalRecords + ') data'">
        </button>
    </div>

    <!-- Table -->
    <div class="relative">
        <!-- Table Loading Spinner Overlay (Inside x-data) -->
        <div id="tableLoadingOverlay" 
             class="absolute inset-0 bg-white/75 backdrop-blur-[1px] z-10 flex flex-col items-center justify-center hidden opacity-0 transition-all duration-200">
            <div class="flex flex-col items-center gap-2">
                <div class="relative w-9 h-9">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-t-indigo-650 animate-spin"></div>
                </div>
                <span class="text-xxs font-bold text-indigo-950 uppercase tracking-wider">Memuat Data...</span>
            </div>
        </div>

        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium w-12">
                        <input type="checkbox" :checked="selectAll" @change="toggleSelectAll($event)" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </th>
                    <th class="p-4 font-medium text-xs">Nopol</th>
                    <th class="p-4 font-medium text-xs">Nama Pemilik</th>
                    <th class="p-4 font-medium text-xs">Detail Kendaraan</th>
                    <th class="p-4 font-medium text-xs text-right">PKB + Opsen (Total)</th>
                    <th class="p-4 font-medium text-xs">Nomor HP</th>
                    <th class="p-4 font-medium text-xs text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($pajak as $p)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <input type="checkbox" value="{{ $p->id }}" x-model="selectedIds" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 row-checkbox">
                    </td>
                    <td class="p-4 font-bold text-slate-900">{{ $p->nopol }}</td>
                    <td class="p-4 font-medium text-slate-800">{{ $p->nama_pemilik }}</td>
                    <td class="p-4">
                        <div class="text-slate-800 font-semibold text-xs">{{ $p->merek_nama }} {{ $p->merek_type }}</div>
                        <div class="text-slate-400 text-xxs mt-0.5 uppercase">{{ $p->jenis_kendaraan }} • Th {{ $p->th_buat }}</div>
                    </td>
                    <td class="p-4 text-right">
                        <div class="font-bold text-slate-900 text-xs">Rp {{ number_format($p->nominal, 0, ',', '.') }}</div>
                        <div class="text-slate-400 text-xxs mt-0.5">PKB: {{ number_format($p->pkb, 0, ',', '.') }} | Opsen: {{ number_format($p->opsen, 0, ',', '.') }}</div>
                    </td>
                    <td class="p-4 text-slate-500 font-medium text-xs">{{ $p->nomor_hp ?: '-' }}</td>
                    <td class="p-4 text-center">
                        @if($p->is_ditagih)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 shadow-sm">
                                Sudah Ditagih
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 shadow-sm">
                                Belum Ditagih
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        Tidak ada data pajak ditemukan. 
                        @if(request('search')) <br> Coba sesuaikan kata kunci pencarian. @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    
    @if($pajak->hasPages())
    <div class="p-4 border-t border-slate-100 bg-white">
        {{ $pajak->links() }}
    </div>
    @endif

    <!-- Deletion Confirmation Modal -->
    <div x-show="showDeleteConfirm" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;">
         
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showDeleteConfirm = false"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-3xl shadow-xl border border-slate-100 max-w-sm w-full p-6 text-center z-10 animate-in fade-in zoom-in-95 duration-200">
            <!-- Trash Icon -->
            <div class="w-14 h-14 rounded-full bg-red-50 text-red-650 flex items-center justify-center border-4 border-white shadow-sm mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            
            <h3 class="text-base font-black text-slate-900 uppercase tracking-wide mb-2">Hapus Data Terpilih?</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Apakah Anda yakin ingin menghapus <span class="font-extrabold text-slate-800" x-text="selectedIds.length"></span> data pajak terpilih secara permanen? Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="showDeleteConfirm = false" class="flex-1 h-11 px-4 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 active:bg-slate-100 rounded-xl text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="button" @click="showDeleteConfirm = false; $refs.deleteForm.submit()" class="flex-1 h-11 px-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-red-600/10 hover:shadow-red-600/20">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>


</div>
</div>


<!-- Loading Spinner Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 shadow-xl flex flex-col items-center gap-4 max-w-xs text-center border border-slate-100">
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-indigo-600 animate-spin"></div>
        </div>
        <div>
            <h3 class="font-bold text-slate-900" id="loadingTitle">Memproses Berkas</h3>
            <p class="text-xs text-slate-500 mt-1" id="loadingSubtitle">Mohon tunggu sebentar...</p>
        </div>
    </div>
</div>

<!-- Import Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closePreviewModal()"></div>

    <!-- Modal Content -->
    <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all flex flex-col relative max-h-[85vh]">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Pratinjau Data Pajak</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="previewRowCount">Menampilkan data yang akan diimpor</p>
            </div>
            <button onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-600 rounded-lg p-1.5 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable Table) -->
        <div class="p-6 overflow-y-auto flex-1">
            <div class="bg-slate-50 rounded-xl border border-slate-150 overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-600 font-semibold uppercase text-xs tracking-wider">
                            <th class="p-3 text-xs">Nopol</th>
                            <th class="p-3 text-xs">Nama Pemilik</th>
                            <th class="p-3 text-xs">Kendaraan</th>
                            <th class="p-3 text-xs text-right">PKB</th>
                            <th class="p-3 text-xs text-right">Opsen</th>
                            <th class="p-3 text-xs text-right text-indigo-600">Total (PKB+Opsen)</th>
                            <th class="p-3 text-xs">No. HP</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody" class="divide-y divide-slate-200 text-slate-700 bg-white">
                        <!-- Rows injected via JS -->
                    </tbody>
                </table>
            </div>
            <p id="previewTruncatedNote" class="text-xs text-amber-600 font-medium mt-3 hidden flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                *Catatan: Pratinjau di atas hanya menampilkan 15 baris pertama untuk performa rendering. Seluruh data tetap akan disimpan.
            </p>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
            <button onclick="closePreviewModal()" class="px-4 py-2 border border-slate-350 text-slate-700 bg-white hover:bg-slate-50 rounded-xl text-sm font-medium transition-colors">
                Batal
            </button>
            <button id="btnConfirmImport" onclick="submitImportData()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                Konfirmasi & Simpan
            </button>
        </div>
    </div>
</div>

<!-- Error Alert Modal -->
<div id="errorModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeErrorModal()"></div>

    <!-- Modal Content -->
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all p-6 text-center relative">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-655 mb-4 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Gagal Mengimpor Berkas</h3>
        <p id="errorModalMessage" class="text-sm text-slate-500 mb-4 leading-relaxed">Format kolom berkas tidak sesuai. Kolom wajib berikut tidak ditemukan: nopol.</p>
        
        <!-- Column Checklist Container -->
        <div id="errorModalChecklist" class="my-4 text-left max-w-xs mx-auto bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-2.5 max-h-60 overflow-y-auto hidden">
            <!-- Checklist items injected via JS -->
        </div>

        <button onclick="closeErrorModal()" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-colors shadow-sm mt-2">
            Tutup
        </button>
    </div>
</div>


<!-- Alpine.js logic -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function pajakTable() {
        return {
            selectedIds: [],
            allMatchingIds: @json($allIds),
            totalRecords: {{ $pajak->total() }},
            currentPageIds: [],
            showDeleteConfirm: false,
            
            init() {
                this.currentPageIds = Array.from(document.querySelectorAll('.row-checkbox')).map(cb => cb.value);
            },
            
            get selectAll() {
                return this.currentPageIds.length > 0 && this.currentPageIds.every(id => this.selectedIds.includes(id));
            },
            
            get isAllMatchingSelected() {
                return this.selectedIds.length === this.totalRecords;
            },
            
            toggleSelectAll(event) {
                const checked = event.target.checked;
                if (checked) {
                    this.currentPageIds.forEach(id => {
                        if (!this.selectedIds.includes(id)) {
                            this.selectedIds.push(id);
                        }
                    });
                } else {
                    this.selectedIds = this.selectedIds.filter(id => !this.currentPageIds.includes(id));
                }
            },
            
            showAllSelectionBanner() {
                return this.selectAll && this.totalRecords > this.currentPageIds.length;
            },
            
            toggleAllMatching() {
                if (this.isAllMatchingSelected) {
                    this.selectedIds = [...this.currentPageIds];
                } else {
                    this.selectedIds = [...this.allMatchingIds];
                }
            }
        }
    }

    // AJAX import handlers
    let parsedImportData = null;

    function showLoading(title = 'Memproses Berkas', subtitle = 'Mohon tunggu sebentar...') {
        const overlay = document.getElementById('loadingOverlay');
        document.getElementById('loadingTitle').innerText = title;
        document.getElementById('loadingSubtitle').innerText = subtitle;
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
        }, 50);
    }

    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.add('opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    function handleFileSelected(input) {
        if (!input.files || input.files.length === 0) return;
        const file = input.files[0];
        
        showLoading('Membaca Berkas', 'Menganalisis kolom dan data berkas...');

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("admin.pajak.preview-import") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            hideLoading();
            input.value = ''; // reset

            if (status === 200 && data.success) {
                parsedImportData = data.data;
                showPreviewModal(data.data);
            } else {
                showErrorModal(data.message || 'Terjadi kesalahan saat memproses berkas.', data.columns || null);
            }
        })
        .catch(error => {
            hideLoading();
            input.value = '';
            showErrorModal('Gagal terhubung dengan server. Silakan periksa koneksi Anda.');
        });
    }

    function showPreviewModal(data) {
        const modal = document.getElementById('previewModal');
        const tableBody = document.getElementById('previewTableBody');
        const rowCountEl = document.getElementById('previewRowCount');
        const noteEl = document.getElementById('previewTruncatedNote');

        tableBody.innerHTML = '';
        rowCountEl.innerText = `Menemukan ${data.length} baris data siap impor`;

        const displayLimit = 15;
        const rowsToDisplay = data.slice(0, displayLimit);

        rowsToDisplay.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/50 transition-colors';
            tr.innerHTML = `
                <td class="p-3 font-bold text-slate-800 text-xs">${escapeHtml(row.nopol)}</td>
                <td class="p-3 text-slate-700 font-semibold text-xs">${escapeHtml(row.nama_pemilik)}</td>
                <td class="p-3 text-xs">
                    <div class="font-semibold text-slate-800">${escapeHtml(row.merek_nama)} ${escapeHtml(row.merek_type)}</div>
                    <div class="text-slate-400 text-xxs mt-0.5 uppercase">${escapeHtml(row.jenis_kendaraan)} • Th ${row.th_buat || '-'}</div>
                </td>
                <td class="p-3 text-right text-xs font-medium text-slate-700">Rp ${formatRupiah(row.pkb)}</td>
                <td class="p-3 text-right text-xs font-medium text-slate-700">Rp ${formatRupiah(row.opsen)}</td>
                <td class="p-3 text-right text-xs font-bold text-indigo-600">Rp ${formatRupiah(row.nominal)}</td>
                <td class="p-3 text-xs text-slate-500 font-medium">${escapeHtml(row.nomor_hp || '-')}</td>
            `;
            tableBody.appendChild(tr);
        });

        if (data.length > displayLimit) {
            noteEl.classList.remove('hidden');
        } else {
            noteEl.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        parsedImportData = null;
    }

    function showErrorModal(message, columns = null) {
        document.getElementById('errorModalMessage').innerText = message;
        
        const checklistContainer = document.getElementById('errorModalChecklist');
        checklistContainer.innerHTML = '';
        
        if (columns && columns.length > 0) {
            checklistContainer.classList.remove('hidden');
            columns.forEach(col => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between text-sm py-1 border-b border-slate-100/50';
                
                const labelClass = col.found 
                    ? 'font-semibold text-emerald-800 text-xs flex items-center gap-2'
                    : 'font-semibold text-red-800 text-xs flex items-center gap-2';
                
                const iconSvg = col.found
                    ? `<span class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                               <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                           </svg>
                       </span>`
                    : `<span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                               <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                           </svg>
                       </span>`;

                const textLabel = col.found
                    ? `<span class="text-slate-700">${escapeHtml(col.name)}</span>`
                    : `<span class="line-through text-slate-450">${escapeHtml(col.name)}</span>`;

                const badge = col.found
                    ? `<span class="text-[9px] bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">OK</span>`
                    : `<span class="text-[9px] bg-red-50 text-red-600 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">TIDAK ADA</span>`;

                item.innerHTML = `
                    <div class="${labelClass}">
                        ${iconSvg}
                        ${textLabel}
                    </div>
                    <div>
                        ${badge}
                    </div>
                `;
                checklistContainer.appendChild(item);
            });
        } else {
            checklistContainer.classList.add('hidden');
        }

        document.getElementById('errorModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    async function submitImportData() {
        if (!parsedImportData || parsedImportData.length === 0) return;

        const dataToSave = parsedImportData; // Salin data ke variabel lokal agar tidak hilang saat modal ditutup

        showLoading('Menyimpan Data', `Sedang memproses 0 dari ${dataToSave.length} data...`);
        closePreviewModal(); // Ini akan menutup modal dan mengeset parsedImportData = null, namun dataToSave tetap aman

        const chunkSize = 300; // Batch per 300 data untuk menghindari Payload Too Large di Nginx
        let successCount = 0;
        let hasError = false;
        let errorMessage = '';

        for (let i = 0; i < dataToSave.length; i += chunkSize) {
            const chunk = dataToSave.slice(i, i + chunkSize);
            
            try {
                const response = await fetch('{{ route("admin.pajak.import") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ data: chunk })
                });

                // Coba parsing sebagai teks dulu untuk menangkap Nginx HTML error (seperti 413 Payload Too Large)
                const textResponse = await response.text();
                let data;
                try {
                    data = JSON.parse(textResponse);
                } catch (e) {
                    throw new Error("Server mengembalikan response non-JSON (Kemungkinan Nginx Error): " + textResponse.substring(0, 100));
                }

                if (response.status === 200 && data.success) {
                    successCount += chunk.length;
                    document.getElementById('loadingSubtitle').innerText = `Menyimpan data... (${successCount} dari ${dataToSave.length})`;
                } else {
                    hasError = true;
                    errorMessage = data.message || 'Gagal menyimpan sebagian data pajak.';
                    break;
                }
            } catch (error) {
                hasError = true;
                errorMessage = error.message || 'Gagal terhubung dengan server saat menyimpan data.';
                break;
            }
        }

        hideLoading();
        if (!hasError) {
            window.location.reload();
        } else {
            showErrorModal(errorMessage);
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Auto-clear loading overlays when restored from back-forward cache (bfcache)
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoading();
        }
    });

    // Debounce AJAX real-time search & page transitions
    let searchTimeout;
    
    function performAjaxSearch(url) {
        const overlay = document.getElementById('tableLoadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const wrapper = document.getElementById('pajakTableWrapper');
            const newWrapper = doc.getElementById('pajakTableWrapper');
            if (wrapper && newWrapper) {
                wrapper.innerHTML = newWrapper.innerHTML;
                
                if (window.Alpine) {
                    window.Alpine.initTree(wrapper);
                }
            }
            
            bindAjaxEvents();
        })
        .catch(error => {
            console.error('AJAX Search Error:', error);
            const overlay = document.getElementById('tableLoadingOverlay');
            if (overlay) {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 200);
            }
        });
    }

    function bindAjaxEvents() {
        const searchInput = document.getElementById('adminSearchInput');
        const searchForm = document.getElementById('adminSearchForm');
        
        if (searchInput && searchForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = searchInput.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', query);
                    url.searchParams.delete('page'); // Reset page
                    
                    window.history.pushState({}, '', url.toString());
                    performAjaxSearch(url.toString());
                }, 600);
            });
            
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const query = searchInput.value;
                const url = new URL(window.location.href);
                url.searchParams.set('search', query);
                url.searchParams.delete('page');
                
                window.history.pushState({}, '', url.toString());
                performAjaxSearch(url.toString());
            });
            
            // Restore focus and cursor position at the end
            if (document.activeElement !== searchInput && searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
        
        // Intercept all pagination links inside #pajakTableWrapper to prevent full reload
        const paginationLinks = document.querySelectorAll('#pajakTableWrapper .pagination a, #pajakTableWrapper a[rel="next"], #pajakTableWrapper a[rel="prev"]');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url) {
                    window.history.pushState({}, '', url);
                    performAjaxSearch(url);
                }
            });
        });
    }
    
    // Support browser Back/Forward navigation states
    window.addEventListener('popstate', function() {
        performAjaxSearch(window.location.href);
    });
    
    // Initial AJAX binding on page load
    bindAjaxEvents();
</script>
@endsection
