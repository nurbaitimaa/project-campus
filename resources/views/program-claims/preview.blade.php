<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Klaim Program - {{ $claim->kode_transaksi }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; }
        .btn-print {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

@php
    $tipe = $claim->programBerjalan->program->tipe_klaim;
    function formatKlaim($angka, $tipe) {
        if ($tipe === 'unit') {
            return number_format($angka, 0, ',', '.') . ' pcs';
        } elseif ($tipe === 'rupiah' || $tipe === 'persen') {
            return 'Rp ' . number_format($angka, 0, ',', '.');
        } else {
            return number_format($angka, 0, ',', '.');
        }
    }
@endphp

<div class="header">
    <h2>Klaim Program</h2>
    <p>Kode: <strong>{{ $claim->kode_transaksi }}</strong></p>
    <p>Customer: {{ $claim->programBerjalan->customer->nama_customer }}</p>
    <p>Program: {{ $claim->programBerjalan->program->nama_program }} ({{ $claim->programBerjalan->program->tipe_klaim }})</p>
    <p>Tanggal Klaim: {{ \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d/m/Y') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Outlet</th>
            <th>Penjualan</th>
            <th>Klaim Distributor</th>
            <th>Klaim Sistem</th>
            <th>Selisih</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($claim->details as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $d->nama_outlet }}</td>
                <td>{{ number_format((float) $d->penjualan ?? 0, 0, ',', '.') }}</td>
                <td>{{ formatKlaim($d->klaim_distributor, $tipe) }}</td>
                <td>{{ formatKlaim($d->klaim_sistem, $tipe) }}</td>
                <td>{{ formatKlaim($d->selisih, $tipe) }}</td>
                <td>{{ $d->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{ number_format($claim->total_pembelian, 0, ',', '.') }}</strong></td>
            <td></td>
            <td colspan="3"><strong>Klaim Sistem: {{ formatKlaim($claim->total_klaim, $tipe) }}</strong></td>
        </tr>
    </tfoot>
</table>

<a href="#" onclick="window.print()" class="btn-print">🖨 Cetak / Print</a>

</body>
</html>
