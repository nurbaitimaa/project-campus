@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Area ini TIDAK akan dicetak --}}
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Laporan Klaim Program</h3>
                <p class="text-muted">Analisis data klaim berdasarkan periode, customer, program, dan status.</p>
            </div>
            <div>
                <button onclick="printReport()" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak Laporan</button>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="{{ route('reports.klaim') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3"><label for="start_date" class="form-label">Tgl Mulai Klaim</label><input type="date" name="start_date" id="start_date" class="form-control" value="{{ $request->start_date }}"></div>
                        <div class="col-md-3"><label for="end_date" class="form-label">Tgl Selesai Klaim</label><input type="date" name="end_date" id="end_date" class="form-control" value="{{ $request->end_date }}"></div>
                        <div class="col-md-2"><label for="customer_id" class="form-label">Customer</label><select name="customer_id" id="customer_id" class="form-select"><option value="">Semua</option>@foreach($customers as $customer)<option value="{{ $customer->kode_customer }}" {{ $request->customer_id == $customer->kode_customer ? 'selected' : '' }}>{{ $customer->nama_customer }}</option>@endforeach</select></div>
                        <div class="col-md-2"><label for="program_id" class="form-label">Program</label><select name="program_id" id="program_id" class="form-select"><option value="">Semua</option>@foreach($programs as $program)<option value="{{ $program->kode_program }}" {{ $request->program_id == $program->kode_program ? 'selected' : '' }}>{{ $program->nama_program }}</option>@endforeach</select></div>
                        <div class="col-md-2"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select"><option value="">Semua</option>@foreach($statuses as $status)<option value="{{ $status }}" {{ $request->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select></div>
                        <div class="col-md-12 mt-3"><button type="submit" class="btn btn-primary">Terapkan Filter</button><a href="{{ route('reports.klaim') }}" class="btn btn-light">Reset Filter</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- AREA YANG AKAN DICETAK --}}
    {{-- ====================================================== --}}
    <div id="printable-area">
        <div class="header-print mb-4 d-none">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                <div class="text-end">
                    <h4 class="fw-bold mb-1">Laporan Klaim Program</h4>
                    <p class="mb-0 text-muted"><strong>Periode:</strong> {{ $request->start_date ? \Carbon\Carbon::parse($request->start_date)->format('d M Y') : 'Semua' }} &mdash; {{ $request->end_date ? \Carbon\Carbon::parse($request->end_date)->format('d M Y') : 'Data' }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Rekapitulasi Klaim</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col"><h5>Rp {{ number_format($rekapitulasi['total_penjualan'], 0, ',', '.') }}</h5><p>Total Penjualan</p></div>
                    <div class="col"><h5>Rp {{ number_format($rekapitulasi['total_klaim'], 0, ',', '.') }}</h5><p>Total Nilai Klaim</p></div>
                    <div class="col border-start"><h5 class="text-warning">{{ $rekapitulasi['pending'] }}</h5><p>Pending</p></div>
                    <div class="col"><h5 class="text-success">{{ $rekapitulasi['approved'] }}</h5><p>Approved</p></div>
                    <div class="col"><h5 class="text-danger">{{ $rekapitulasi['rejected'] }}</h5><p>Rejected</p></div>
                    <div class="col border-start"><h5 class="fw-bold">{{ $rekapitulasi['total_data'] }}</h5><p>Total Klaim</p></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Laporan</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tgl. Klaim</th>
                            <th>Customer</th>
                            <th>Program</th>
                            <th>Total Penjualan</th>
                            <th>Total Klaim</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($claims as $claim)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d/m/Y') }}</td>
                            <td>{{ $claim->programBerjalan->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $claim->programBerjalan->program->nama_program ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($claim->total_pembelian, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($claim->total_klaim, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($claim->status == 'approved') <span class="badge bg-success">{{ ucfirst($claim->status) }}</span>
                                @elseif($claim->status == 'pending') <span class="badge bg-warning text-dark">{{ ucfirst($claim->status) }}</span>
                                @else <span class="badge bg-danger">{{ ucfirst($claim->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data untuk filter yang dipilih.</td></tr>
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
    /* Salin CSS dari laporan absensi */
    .header-print{display:none}@media print{body *{visibility:hidden}#printable-area,#printable-area *{visibility:visible}#printable-area{position:absolute;left:0;top:0;width:100%;padding:20px}.header-print{display:block !important}.no-print{display:none !important}.card{box-shadow:none !important;border:1px solid #dee2e6 !important}.badge{border:1px solid #6c757d !important;background-color:transparent !important;color:#212529 !important}}
</style>
@endpush

@push('scripts')
<script>
    // Salin fungsi printReport dari laporan absensi
    function printReport(){const t=document.getElementById("printable-area").innerHTML,e=`
            <html>
                <head>
                    <title>Laporan Klaim Program</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; }
                        .header-print { display: block !important; }
                        .d-none { display: flex !important; }
                        .text-success { color: #198754 !important; }
                        .text-danger { color: #dc3545 !important; }
                        .text-warning { color: #ffc107 !important; }
                        .badge { border: 1px solid #6c757d !important; background-color: transparent !important; color: #212529 !important; }
                    </style>
                </head>
                <body>${t}</body>
            </html>`;const o=document.createElement("iframe");o.style.position="absolute",o.style.width="0",o.style.height="0",o.style.border="0",document.body.appendChild(o);const n=o.contentWindow.document;n.open(),n.write(e),n.close(),setTimeout(function(){o.contentWindow.focus(),o.contentWindow.print(),document.body.removeChild(o)},500)}
</script>
@endpush
@endsection
