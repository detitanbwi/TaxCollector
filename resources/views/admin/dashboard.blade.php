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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100 flex flex-col items-center">
            <h2 class="text-lg font-bold text-slate-900 mb-4 w-full text-left">Statistik Penagihan</h2>
            <div class="w-full max-w-xs">
                <canvas id="penagihanChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('penagihanChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Sudah Ditagih', 'Belum Ditagih'],
                    datasets: [{
                        data: [{{ $totalDitagih }}, {{ $totalBelumDitagih }}],
                        backgroundColor: [
                            '#059669', // emerald-600
                            '#d97706'  // amber-600
                        ],
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: "'Inter', sans-serif"
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
