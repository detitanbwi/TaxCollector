@extends('layouts.admin')

@section('content')
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
                    <td class="p-4 flex justify-end space-x-2">
                        <a href="{{ route('admin.users.edit', $u->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        @if(auth()->id() !== $u->id)
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                        </form>
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
@endsection
