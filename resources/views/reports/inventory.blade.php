@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Area ini TIDAK akan dicetak --}}
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Laporan Stok Inventory</h3>
                <p class="text-muted">Laporan rekapitulasi stok awal, mutasi, dan stok akhir.</p>
            </div>
            <div>
                <button onclick="printReport()" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="{{ route('reports.inventory') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="inventory_id" class="form-label">Pilih Barang</label>
                            <select name="inventory_id" id="inventory_id" class="form-select">
                                <option value="">Semua Barang</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" {{ $request->inventory_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Tanggal Mulai (Mutasi)</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $request->start_date }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Tanggal Selesai (Mutasi)</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $request->end_date }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            <a href="{{ route('reports.inventory') }}" class="btn btn-light">Reset Filter</a>
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
                    <h4 class="fw-bold mb-1">Laporan Stok Inventory</h4>
                    <p class="mb-0 text-muted">
                        <strong>Periode Mutasi:</strong>
                        @if($request->start_date && $request->end_date)
                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                        @else
                            Semua Tanggal
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Stok</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th class="text-start">Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok Awal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportData as $data)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $data['nama_barang'] }}</td>
                            <td class="text-center">{{ $data['satuan'] }}</td>
                            <td class="text-center">{{ $data['stok_awal'] }}</td>
                            <td class="text-center text-success">{{ $data['total_masuk'] }}</td>
                            <td class="text-center text-danger">{{ $data['total_keluar'] }}</td>
                            <td class="text-center fw-bold">{{ $data['stok_akhir'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada data inventory ditemukan.
                            </td>
                        </tr>
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
    /* ... (salin kode <style> yang sama dari absensi.blade.php) ... */
    .header-print { display: none; }
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; padding: 20px; }
        .header-print { display: block !important; }
        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        .header-print img { visibility: visible; }
    }
</style>
@endpush

@push('scripts')
<script>
    function printReport() {
        // ... (salin fungsi JavaScript printReport() yang sama dari absensi.blade.php) ...
        const printContent = document.getElementById('printable-area').innerHTML;
        const printHtml = `
            <html>
                <head>
                    <title>Laporan Stok Inventory</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; }
                        .header-print { display: block !important; }
                        .d-none { display: flex !important; }
                        .text-success { color: #198754 !important; }
                        .text-danger { color: #dc3545 !important; }
                    </style>
                </head>
                <body>${printContent}</body>
            </html>`;
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        document.body.appendChild(iframe);
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(printHtml);
        doc.close();
        setTimeout(function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 500);
    }
</script>
@endpush
@endsection
