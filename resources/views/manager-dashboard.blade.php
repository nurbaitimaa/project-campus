@extends('layouts.manager')

@section('page-title', 'Dashboard Manager')

@section('content')
    {{-- Helper untuk format angka menjadi lebih singkat --}}
    @php
        function formatShortNumber($num) {
            if ($num >= 1000000000) {
                return round($num / 1000000000, 1) . 'M'; // Miliar
            }
            if ($num >= 1000000) {
                return round($num / 1000000, 1) . 'Jt'; // Juta
            }
            if ($num >= 1000) {
                return round($num / 1000, 1) . 'Rb'; // Ribu
            }
            return $num;
        }
    @endphp

    <h1 class="text-3xl font-bold text-slate-800 mb-2">Halo, Manager Marketing Mazzoni!</h1>
    <p class="text-slate-600 mb-6">Berikut adalah ringkasan untuk persetujuan dan budget Anda.</p>

    <!-- Info Cards Dinamis -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Klaim Pending -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Klaim Menunggu Persetujuan</p>
                <p class="text-3xl font-bold text-slate-800">{{ $pending_claims_count }}</p>
            </div>
            <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Budget Tahun Ini -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Budget {{ date('Y') }}</p>
                <p class="text-3xl font-bold text-slate-800">Rp {{ formatShortNumber($total_budget) }}</p>
            </div>
            <div class="bg-green-100 text-green-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01M12 14v4m0 0v1m0-1.01M12 16.01V16m0 2.01M12 20v-1m0-1.01M12 18V16"></path></svg>
            </div>
        </div>

        <!-- Card 3: Budget Terpakai -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Budget Terpakai</p>
                <p class="text-3xl font-bold text-slate-800">Rp {{ formatShortNumber($budget_used) }}</p>
            </div>
            <div class="bg-rose-100 text-rose-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>

        <!-- Card 4: Sisa Budget -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Sisa Budget</p>
                <p class="text-3xl font-bold text-slate-800">Rp {{ formatShortNumber($budget_remaining) }}</p>
            </div>
            <div class="bg-emerald-100 text-emerald-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>
    
    <!-- Tabel & Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Tabel Klaim Terbaru -->
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Klaim Terbaru Menunggu Persetujuan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Customer</th>
                            <th class="py-2">Program</th>
                            <th class="py-2 text-right">Nilai Klaim</th>
                            <th class="py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_claims as $claim)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="py-3">{{ $claim->programBerjalan->customer->nama_customer ?? 'N/A' }}</td>
                            <td class="py-3">{{ $claim->programBerjalan->program->nama_program ?? 'N/A' }}</td>
                            <td class="py-3 text-right">Rp {{ number_format($claim->total_klaim, 0, ',', '.') }}</td>
                            <td class="py-3 text-center"><a href="{{ route('approval.index') }}" class="text-blue-600 hover:underline text-sm font-medium">Lihat</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-slate-500">Tidak ada klaim yang menunggu persetujuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grafik Pie Budget -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Komposisi Budget</h2>
            <canvas id="budgetPieChart"></canvas>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('budgetPieChart').getContext('2d');
    const budgetPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terpakai', 'Sisa'],
            datasets: [{
                // Mengambil data dari variabel PHP yang dikirim controller
                data: [{{ $budget_used }}, {{ $budget_remaining > 0 ? $budget_remaining : 0 }}],
                backgroundColor: [
                    'rgba(225, 29, 72, 0.7)', // rose-600
                    'rgba(5, 150, 105, 0.7)'  // emerald-600
                ],
                borderColor: [
                    'rgba(225, 29, 72, 1)',
                    'rgba(5, 150, 105, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection
