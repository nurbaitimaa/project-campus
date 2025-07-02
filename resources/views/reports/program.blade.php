@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Laporan Program Berjalan</h3>
                <p class="text-muted">Melihat daftar semua program yang diimplementasikan untuk customer.</p>
            </div>
            <div>
                <button onclick="printReport()" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak Laporan</button>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="{{ route('reports.program') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3"><label for="start_date" class="form-label">Periode Mulai</label><input type="date" name="start_date" id="start_date" class="form-control" value="{{ $request->start_date }}"></div>
                        <div class="col-md-3"><label for="end_date" class="form-label">Periode Selesai</label><input type="date" name="end_date" id="end_date" class="form-control" value="{{ $request->end_date }}"></div>
                        <div class="col-md-3"><label for="customer_id" class="form-label">Customer</label><select name="customer_id" id="customer_id" class="form-select"><option value="">Semua</option>@foreach($customers as $customer)<option value="{{ $customer->kode_customer }}" {{ $request->customer_id == $customer->kode_customer ? 'selected' : '' }}>{{ $customer->nama_customer }}</option>@endforeach</select></div>
                        <div class="col-md-3"><label for="program_id" class="form-label">Program</label><select name="program_id" id="program_id" class="form-select"><option value="">Semua</option>@foreach($programs as $program)<option value="{{ $program->kode_program }}" {{ $request->program_id == $program->kode_program ? 'selected' : '' }}>{{ $program->nama_program }}</option>@endforeach</select></div>
                        <div class="col-md-12 mt-3"><button type="submit" class="btn btn-primary">Terapkan Filter</button><a href="{{ route('reports.program') }}" class="btn btn-light">Reset Filter</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="printable-area">
        <div class="header-print mb-4 d-none">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                <div class="text-end">
                    <h4 class="fw-bold mb-1">Laporan Program Berjalan</h4>
                    <p class="mb-0 text-muted"><strong>Periode:</strong> {{ $request->start_date ? \Carbon\Carbon::parse($request->start_date)->format('d M Y') : 'Semua' }} &mdash; {{ $request->end_date ? \Carbon\Carbon::parse($request->end_date)->format('d M Y') : 'Data' }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Program</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Nama Program</th>
                            <th>Periode</th>
                            <th>Deskripsi Hadiah</th>
                            <th>Aturan Reward</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programBerjalan as $program)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $program->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $program->program->nama_program ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($program->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/y') }}</td>
                            <td>{{ $program->target }}</td>
                            <td>
                                Min. Beli: {{ number_format($program->min_pembelian, 0, ',', '.') }}<br>
                                Reward: {{ number_format($program->reward, 0, ',', '.') }} ({{ $program->reward_type }})
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data program berjalan untuk filter yang dipilih.</td></tr>
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
                    <title>Laporan Program Berjalan</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; }
                        .header-print { display: block !important; }
                        .d-none { display: flex !important; }
                    </style>
                </head>
                <body>${t}</body>
            </html>`;const o=document.createElement("iframe");o.style.position="absolute",o.style.width="0",o.style.height="0",o.style.border="0",document.body.appendChild(o);const n=o.contentWindow.document;n.open(),n.write(e),n.close(),setTimeout(function(){o.contentWindow.focus(),o.contentWindow.print(),document.body.removeChild(o)},500)}
</script>
@endpush
@endsection
