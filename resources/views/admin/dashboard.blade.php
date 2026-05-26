@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex flex-col">
            <span class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Data Pajak</span>
            <span class="text-3xl font-bold text-slate-900">{{ number_format($totalData, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex flex-col">
            <span class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Sudah Ditagih</span>
            <span class="text-3xl font-bold text-emerald-600">{{ number_format($totalDitagih, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex flex-col">
            <span class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Belum Ditagih</span>
            <span class="text-3xl font-bold text-amber-600">{{ number_format($totalBelumDitagih, 0, ',', '.') }}</span>
        </div>
    </div>
@endsection
