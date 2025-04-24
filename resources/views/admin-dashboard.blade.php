@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Dashboard Admin</h1>
    <p>Selamat datang admin Mazzoni.</p>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Program</h2>
            <p class="text-3xl font-bold mt-2">25</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Klaim</h2>
            <p class="text-3xl font-bold mt-2">14</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Grafik Statistik</h2>
            <div class="mt-2 text-gray-500">[Placeholder Grafik]</div>
        </div>
    </div>
@endsection
