@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Area ini TIDAK akan dicetak --}}
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Laporan Penggunaan Budget</h3>
                <p class="text-muted">Monitor alokasi, penggunaan, dan sisa budget marketing.</p>
            </div>
            {{-- Tombol Cetak dan Export --}}
            <div class="btn-group">
                <button onclick="printReport()" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak</button>
                <a href="{{ route('reports.budget.exportExcel', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="{{ route('reports.budget') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">Semua Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $request->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_anggaran" class="form-label">Tahun Anggaran</label>
                            <select name="tahun_anggaran" id="tahun_anggaran" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $request->tahun_anggaran == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            <a href="{{ route('reports.budget') }}" class="btn btn-light">Reset Filter</a>
                        </div>
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
                    <h4 class="fw-bold mb-1">Laporan Penggunaan Budget</h4>
                    <p class="mb-0 text-muted"><strong>Tahun:</strong> {{ $request->tahun_anggaran ?? 'Semua' }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Penggunaan Budget</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Customer</th>
                            <th class="text-center">Tahun Anggaran</th>
                            <th class="text-end">Budget Awal</th>
                            <th class="text-end">Terpakai (Klaim Approved)</th>
                            <th class="text-end">Sisa Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalBudget = 0;
                            $totalTerpakai = 0;
                            $totalSisa = 0;
                        @endphp
                        @forelse ($reportData as $data)
                        @php
                            $totalBudget += $data->nilai_budget;
                            $totalTerpakai += $data->terpakai;
                            $totalSisa += $data->sisa_aktual;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $data->customer->nama_customer }}</td>
                            <td class="text-center">{{ $data->tahun_anggaran }}</td>
                            <td class="text-end">Rp {{ number_format($data->nilai_budget, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($data->terpakai, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($data->sisa_aktual, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data budget untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
                    @if($reportData->count() > 0)
                    <tfoot class="table-secondary fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">Total Keseluruhan</td>
                            <td class="text-end">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($totalTerpakai, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
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
                    <title>Laporan Penggunaan Budget</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; }
                        .header-print { display: block !important; }
                        .d-none { display: flex !important; }
                        .text-success { color: #198754 !important; }
                        .text-danger { color: #dc3545 !important; }
                    </style>
                </head>
                <body>${t}</body>
            </html>`;const o=document.createElement("iframe");o.style.position="absolute",o.style.width="0",o.style.height="0",o.style.border="0",document.body.appendChild(o);const n=o.contentWindow.document;n.open(),n.write(e),n.close(),setTimeout(function(){o.contentWindow.focus(),o.contentWindow.print(),document.body.removeChild(o)},500)}
</script>
@endpush
@endsection
