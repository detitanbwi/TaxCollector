@extends('layouts.penagih')

@section('content')

<!-- Search & Sort Card -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <h2 class="text-base font-black text-slate-900 mb-3 text-center uppercase tracking-wider">Daftar Target Tagihan</h2>
    
    <form id="filterForm" method="GET" action="{{ route('penagih.dashboard') }}" class="flex flex-col gap-3">
        <!-- Search Input -->
        <div class="relative w-full">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                placeholder="Cari Nopol, Nama Warga, atau Merek..." 
                class="block w-full h-11 rounded-xl border border-slate-200 pl-10 pr-10 text-xs font-semibold text-slate-700 placeholder:text-slate-400 placeholder:font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none shadow-sm transition-all duration-200"
                autocomplete="off">
            @if(request('search'))
                <a href="{{ route('penagih.dashboard') }}" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-700 transition-colors" title="Clear Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- Sort & Actions -->
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <select name="sort_option" onchange="this.form.submit()" class="block w-full h-11 rounded-xl border border-slate-200 bg-white pl-3.5 pr-10 text-xs font-bold text-slate-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none cursor-pointer appearance-none transition-all duration-200">
                    <option value="nama_pemilik-asc" {{ request('sort') == 'nama_pemilik' && request('order') == 'asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                    <option value="nama_pemilik-desc" {{ request('sort') == 'nama_pemilik' && request('order') == 'desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                    <option value="nominal-desc" {{ request('sort') == 'nominal' && request('order') == 'desc' ? 'selected' : '' }}>Tagihan Terbesar</option>
                    <option value="nominal-asc" {{ request('sort') == 'nominal' && request('order') == 'asc' ? 'selected' : '' }}>Tagihan Terkecil</option>
                    <option value="latest-desc" {{ request('sort') == 'id' || !request('sort') ? 'selected' : '' }}>Tagihan Terbaru</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            
            {{-- Hidden fields for sorting details --}}
            <input type="hidden" name="sort" id="sortField" value="{{ request('sort', 'nama_pemilik') }}">
            <input type="hidden" name="order" id="orderField" value="{{ request('order', 'asc') }}">

            @if(request('search') || request('sort'))
                <a href="{{ route('penagih.dashboard') }}" class="h-11 px-4 bg-slate-50 hover:bg-slate-100 active:bg-slate-200 text-slate-500 border border-slate-200 rounded-xl text-xs font-bold transition-all duration-200 shadow-sm flex items-center justify-center whitespace-nowrap">
                    Reset
                </a>
            @endif
            
            <button type="submit" class="h-11 px-5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold transition-all duration-200 shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 flex items-center justify-center gap-1.5 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Cari</span>
            </button>
        </div>
    </form>
</div>

<!-- List of Target Tagihan -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="divide-y divide-slate-50">
        @forelse($pajakList as $pajak)
            <a href="{{ route('penagih.pajak.show', $pajak->id) }}" class="p-4 hover:bg-slate-50/70 transition-colors flex items-center justify-between gap-3 group">
                
                <!-- Data Section (2 rows) -->
                <div class="flex-1 min-w-0 flex flex-col gap-1.5">
                    <!-- Row 1: Nopol & Tagihan -->
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="font-black text-slate-900 uppercase tracking-wide text-sm">{{ $pajak->nopol }}</span>
                            <div id="status-container-{{ $pajak->id }}">
                                @if($pajak->is_ditagih)
                                    <span id="status-badge-{{ $pajak->id }}" class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">
                                        SUDAH DITAGIH
                                    </span>
                                @else
                                    <span id="status-badge-{{ $pajak->id }}" class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/60 uppercase tracking-wider">
                                        BELUM DITAGIH
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="font-black text-slate-900 text-sm">Rp {{ number_format($pajak->nominal, 0, ',', '.') }}</div>
                    </div>
                    
                    <!-- Row 2: Nama & Masa Laku -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                        <div class="text-slate-600 truncate flex-1 min-w-0">
                            <span class="font-bold text-slate-800">{{ $pajak->nama_pemilik }}</span>
                            @if($pajak->nomor_hp)
                                <span class="text-slate-400 ml-1">({{ $pajak->nomor_hp }})</span>
                            @endif
                        </div>
                        <div class="text-indigo-600 font-bold whitespace-nowrap flex items-center gap-1.5">
                            <span class="text-slate-400 font-medium mr-1 hidden sm:inline">Jatuh Tempo:</span>
                            <span>{{ $pajak->masa_laku ?: '-' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Tidak Ada Tagihan Ditemukan</h3>
                        <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau filter Anda.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Beautiful Custom Pagination Controls for Mobile & Desktop -->
@if($pajakList->hasPages())
    <div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3 sm:px-6 rounded-2xl shadow-sm mt-6">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($pajakList->onFirstPage())
                <span class="relative inline-flex items-center rounded-xl border border-slate-250 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-450 cursor-not-allowed">Sebelumnya</span>
            @else
                <a href="{{ $pajakList->previousPageUrl() }}" class="relative inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Sebelumnya</a>
            @endif

            <span class="text-xs text-slate-500 font-medium self-center">Hal {{ $pajakList->currentPage() }} / {{ $pajakList->lastPage() }}</span>

            @if ($pajakList->hasMorePages())
                <a href="{{ $pajakList->nextPageUrl() }}" class="relative inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Berikutnya</a>
            @else
                <span class="relative inline-flex items-center rounded-xl border border-slate-250 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-450 cursor-not-allowed">Berikutnya</span>
            @endif
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-800">{{ $pajakList->firstItem() ?: 0 }}</span> s.d <span class="font-bold text-slate-800">{{ $pajakList->lastItem() ?: 0 }}</span> dari <span class="font-bold text-slate-800">{{ $pajakList->total() }}</span> tagihan
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-xl border border-slate-300 overflow-hidden" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($pajakList->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 text-slate-400 bg-slate-50 cursor-not-allowed">
                            <span class="sr-only">Sebelumnya</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </span>
                    @else
                        <a href="{{ $pajakList->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <span class="sr-only">Sebelumnya</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </a>
                    @endif

                    {{-- Page Numbers info --}}
                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-slate-50 border-x border-slate-300">Hal {{ $pajakList->currentPage() }} / {{ $pajakList->lastPage() }}</span>

                    {{-- Next Page Link --}}
                    @if ($pajakList->hasMorePages())
                        <a href="{{ $pajakList->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <span class="sr-only">Berikutnya</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 text-slate-400 bg-slate-50 cursor-not-allowed">
                            <span class="sr-only">Berikutnya</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endif

<!-- JavaScript Logic -->
<script>
    // Handle form sorting details mapping on submit
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const select = this.querySelector('select[name="sort_option"]');
        const sortField = document.getElementById('sortField');
        const orderField = document.getElementById('orderField');
        
        const val = select.value;
        if (val === 'latest-desc') {
            sortField.value = 'id';
            orderField.value = 'desc';
        } else {
            const parts = val.split('-');
            sortField.value = parts[0];
            orderField.value = parts[1];
    });

    // Debounce real-time search
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');
    
    if (searchInput && filterForm) {
        let debounceTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 600); // 600ms delay to balance response speed and server load
        });

        // Focus and put cursor at the end of text on page load if search is active
        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    }
</script>

@endsection
