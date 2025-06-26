@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Approval Klaim Program</h1>

    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Customer</th>
                <th>Program</th>
                <th>Tanggal</th>
                <th>Total Klaim</th>
                <th>Status</th>
                <th>Preview</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($claims as $claim)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $claim->kode_transaksi }}</td>
                    <td>{{ $claim->programBerjalan->customer->nama_customer ?? '-' }}</td>
                    <td>{{ $claim->programBerjalan->program->nama_program ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($claim->total_klaim, 0, ',', '.') }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($claim->status) }}</span></td>
                    <td>
                        <a href="{{ route('program-claims.preview', $claim->id) }}" class="btn btn-info btn-sm" target="_blank">
                            Preview
                        </a>
                    </td>
                    <td>
                        <!-- Tombol Approve -->
<form action="{{ route('approve-program-claims.approve', $claim->id) }}" method="POST" style="display:inline-block">
    @csrf
    @method('POST') {{-- Tambahkan ini meskipun method sudah POST, untuk jaga-jaga --}}
    <button type="submit" class="btn btn-success btn-sm">✅ Setujui</button>
</form>

<!-- Tombol Reject -->
<form action="{{ route('approve-program-claims.reject', $claim->id) }}" method="POST" style="display:inline-block">
    @csrf
    @method('POST') {{-- Tambahkan ini juga --}}
    <button type="submit" class="btn btn-danger btn-sm">❌ Tolak</button>
</form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada klaim yang menunggu persetujuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
