@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Data Klaim Program</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('program-claims.create') }}" class="btn btn-primary">
            + Tambah Klaim Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Klaim</th>
                    <th>Customer</th>
                    <th>Program</th>
                    <th>Total Pembelian</th>
                    <th>Total Klaim Sistem</th>
                    <th>Bukti Klaim</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalPembelian = 0;
                    $grandTotalKlaim = 0;
                @endphp

                @forelse($programClaims as $klaim)
                    @php
                        $grandTotalPembelian += $klaim->total_pembelian;
                        $grandTotalKlaim += $klaim->total_klaim;
                    @endphp
                    <tr>
                        <td>{{ $klaim->kode_transaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($klaim->tanggal_klaim)->format('d/m/Y') }}</td>
                        <td>{{ $klaim->programBerjalan->customer->nama_customer ?? '-' }}</td>
                        <td>{{ $klaim->programBerjalan->program->nama_program ?? '-' }}</td>
                        <td>Rp {{ number_format($klaim->total_pembelian, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($klaim->total_klaim, 0, ',', '.') }}</td>
                        <td>
                            @if($klaim->bukti_klaim)
                                <a href="{{ asset('storage/' . $klaim->bukti_klaim) }}" target="_blank">Lihat</a>
                            @else
                                Tidak ada
                            @endif
                        </td>
                        <td>{{ $klaim->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('program-claims.edit', $klaim->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <form action="{{ route('program-claims.destroy', $klaim->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klaim ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
<a href="{{ route('program-claims.preview', $klaim->id) }}" class="btn btn-sm btn-info mb-1" target="_blank">
    Preview
</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data klaim.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($programClaims->count() > 0)
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="4" class="text-end">Total Keseluruhan:</td>
                    <td>Rp {{ number_format($grandTotalPembelian, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($grandTotalKlaim, 0, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
