@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-2">Pusat Laporan</h3>
    <p class="text-muted mb-4">Silakan pilih jenis laporan yang ingin Anda lihat, cetak, atau ekspor.</p>

    <div class="row">
        <!-- Laporan Absensi -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-lift">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">Laporan Absensi</h5>
                    <p class="card-text text-muted small flex-grow-1">Lihat rekapitulasi kehadiran tim sales marketing berdasarkan rentang tanggal.</p>
                    <a href="{{ route('reports.absensi') }}" class="btn btn-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Inventory -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-lift">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">Laporan Stok Inventory</h5>
                    <p class="card-text text-muted small flex-grow-1">Lacak pergerakan barang, stok masuk, stok keluar, dan sisa stok akhir.</p>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Klaim Program -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-lift">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">Laporan Klaim Program</h5>
                    <p class="card-text text-muted small flex-grow-1">Analisis detail klaim per customer, termasuk total penjualan dan nilai klaim.</p>
                    <a href="{{ route('reports.klaim') }}" class="btn btn-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>

        <!-- Laporan Program Berjalan -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-lift">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">Laporan Program Berjalan</h5>
                    <p class="card-text text-muted small flex-grow-1">Lihat daftar lengkap semua program yang diimplementasikan untuk customer.</p>
                    <a href="{{ route('reports.program') }}" class="btn btn-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>
        
        <!-- Laporan Budget -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm hover-lift">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">Laporan Penggunaan Budget</h5>
                    <p class="card-text text-muted small flex-grow-1">Monitor alokasi, penggunaan, dan sisa budget marketing per customer.</p>
                    <a href="{{ route('reports.budget') }}" class="btn btn-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform .2s ease-in-out, box-shadow .2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endpush
