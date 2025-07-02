@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('content')
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Selamat Datang, Admin!</h1>
    <p class="text-slate-600 mb-6">Berikut adalah ringkasan aktivitas sistem saat ini.</p>

    <!-- Info Cards Dinamis -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Total Program -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Program</p>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['programs'] }}</p>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Customer -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Customer</p>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['customers'] }}</p>
            </div>
            <div class="bg-green-100 text-green-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Total Sales -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Sales</p>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['sales'] }}</p>
            </div>
            <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-2a6 6 0 00-12 0v2"></path></svg>
            </div>
        </div>

        <!-- Card 4: Klaim Pending -->
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between transition hover:shadow-xl hover:-translate-y-1">
            <div>
                <p class="text-sm font-medium text-slate-500">Klaim Menunggu Approval</p>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['pending_claims'] }}</p>
            </div>
            <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>
    
    <!-- Grafik Statistik Dinamis -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Grafik Aktivitas Bulanan Tahun {{ date('Y') }}</h2>
        <canvas id="monthlyActivityChart" height="100"></canvas>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyActivityChart').getContext('2d');
    const monthlyActivityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Program Baru',
                // Mengambil data dari variabel PHP yang dikonversi ke JSON
                data: @json($program_chart_data),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 5
            }, {
                label: 'Klaim Masuk',
                // Mengambil data dari variabel PHP yang dikonversi ke JSON
                data: @json($claim_chart_data),
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    // Pastikan hanya menampilkan angka bulat di sumbu Y
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });
});
</script>
@endsection
