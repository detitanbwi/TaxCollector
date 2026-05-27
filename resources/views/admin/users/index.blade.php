@extends('layouts.admin')

@section('content')
<div x-data="{ showDeleteConfirm: false, deleteUrl: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
            Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-sm uppercase tracking-wider">
                        <th class="p-4 font-medium">ID</th>
                        <th class="p-4 font-medium">Nama</th>
                        <th class="p-4 font-medium">Username</th>
                        <th class="p-4 font-medium">Peran</th>
                        <th class="p-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4">{{ $u->id }}</td>
                        <td class="p-4 font-medium text-slate-900">{{ $u->name }}</td>
                        <td class="p-4">{{ $u->username }}</td>
                        <td class="p-4">
                            @if($u->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Admin</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Penagih</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-end items-center space-x-2">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            @if(auth()->id() !== $u->id)
                            <button type="button" @click="deleteUrl = '{{ route('admin.users.destroy', $u->id) }}'; showDeleteConfirm = true;" class="text-red-600 hover:text-red-900 font-medium">
                                Hapus
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-500">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Master Delete Form -->
    <form :action="deleteUrl" method="POST" x-ref="masterDeleteForm" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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
            
            <h3 class="text-base font-black text-slate-900 uppercase tracking-wide mb-2">Hapus Pengguna Ini?</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun pengguna terpilih secara permanen? Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="showDeleteConfirm = false" class="flex-1 h-11 px-4 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 active:bg-slate-100 rounded-xl text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="button" @click="$refs.masterDeleteForm.submit()" class="flex-1 h-11 px-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-red-600/10 hover:shadow-red-600/20">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js logic -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
