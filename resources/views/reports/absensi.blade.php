@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Area ini TIDAK akan dicetak --}}
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Laporan Absensi</h3>
                <p class="text-muted">Gunakan filter di bawah untuk melihat data absensi.</p>
            </div>
            {{-- Grup Tombol --}}
            <div class="btn-group">
                <button onclick="printReport()" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak</button>
                <a href="{{ route('reports.absensi.exportExcel', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="{{ route('reports.absensi') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4"><label for="start_date" class="form-label">Tanggal Mulai</label><input type="date" name="start_date" id="start_date" class="form-control" value="{{ $request->start_date }}"></div>
                        <div class="col-md-4"><label for="end_date" class="form-label">Tanggal Selesai</label><input type="date" name="end_date" id="end_date" class="form-control" value="{{ $request->end_date }}"></div>
                        <div class="col-md-4"><label for="sales_id" class="form-label">Pilih Sales</label><select name="sales_id" id="sales_id" class="form-select"><option value="">Semua Sales</option>@foreach($sales as $sale)<option value="{{ $sale->id }}" {{ $request->sales_id == $sale->id ? 'selected' : '' }}>{{ $sale->nama_sales }}</option>@endforeach</select></div>
                        <div class="col-md-12 mt-3"><button type="submit" class="btn btn-primary">Terapkan Filter</button><a href="{{ route('reports.absensi') }}" class="btn btn-light">Reset Filter</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- ====================================================== --}}
    {{-- AREA YANG AKAN DICETAK --}}
    {{-- ====================================================== --}}
    <div id="printable-area">
        {{-- Header Laporan (Hanya muncul saat print) --}}
        <div class="header-print mb-4 d-none">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                <div class="text-end">
                    <h4 class="fw-bold mb-1">Laporan Absensi</h4>
                    <p class="mb-0 text-muted">
                        <strong>Periode:</strong>
                        @if($request->start_date && $request->end_date)
                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                        @else
                            Semua Data
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Konten Laporan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Rekapitulasi Absensi</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col"><h5 class="text-success">{{ $rekapitulasi['Hadir'] }}</h5><p>Hadir</p></div>
                    <div class="col"><h5 class="text-info">{{ $rekapitulasi['Izin'] }}</h5><p>Izin</p></div>
                    <div class="col"><h5 class="text-warning">{{ $rekapitulasi['Sakit'] }}</h5><p>Sakit</p></div>
                    <div class="col"><h5 class="text-danger">{{ $rekapitulasi['Alfa'] }}</h5><p>Alfa</p></div>
                    <div class="col border-start"><h5 class="fw-bold">{{ $rekapitulasi['Total'] }}</h5><p>Total Data</p></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Laporan</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th><th>Tanggal</th><th>Nama Sales</th><th>Status</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Keterangan</th><th>Diedit Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensiList as $absensi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                            <td>{{ $absensi->salesMarketing->nama_sales ?? '-' }}</td>
                            <td>@if($absensi->status == 'Hadir')<span class="badge bg-success">{{ $absensi->status }}</span>@elseif($absensi->status == 'Izin')<span class="badge bg-info text-dark">{{ $absensi->status }}</span>@elseif($absensi->status == 'Sakit')<span class="badge bg-warning text-dark">{{ $absensi->status }}</span>@else<span class="badge bg-danger">{{ $absensi->status }}</span>@endif</td>
                            <td>{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                            <td>{{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-' }}</td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                            <td>{{ $absensi->updatedBy->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    /* Salin CSS dari laporan sebelumnya */
    .header-print{display:none}@media print{body *{visibility:hidden}#printable-area,#printable-area *{visibility:visible}#printable-area{position:absolute;left:0;top:0;width:100%;padding:20px}.header-print{display:block !important}.no-print{display:none !important}.card{box-shadow:none !important;border:1px solid #dee2e6 !important}}
</style>
@endpush

@push('scripts')
<script>
    // Salin fungsi printReport dari laporan sebelumnya
    function printReport(){const t=document.getElementById("printable-area").innerHTML,e=`
            <html>
                <head>
                    <title>Laporan Absensi</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; }
                        .header-print { display: block !important; }
                        .d-none { display: flex !important; }
                        .badge { border: 1px solid #6c757d !important; background-color: transparent !important; color: #212529 !important; }
                    </style>
                </head>
                <body>${t}</body>
            </html>`;const o=document.createElement("iframe");o.style.position="absolute",o.style.width="0",o.style.height="0",o.style.border="0",document.body.appendChild(o);const n=o.contentWindow.document;n.open(),n.write(e),n.close(),setTimeout(function(){o.contentWindow.focus(),o.contentWindow.print(),document.body.removeChild(o)},500)}
</script>
@endpush
@endsection
