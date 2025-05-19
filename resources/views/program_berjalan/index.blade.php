@extends('layouts.app') {{-- Admin Marketing - Program Berjalan --}}
@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Program Berjalan</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('program-berjalan.create') }}" class="btn btn-primary">+ Tambah Program</a>

        {{-- Filter Form --}}
        <form action="{{ route('program-berjalan.index') }}" method="GET" class="d-flex gap-2">
            <select name="customer" class="form-select">
                <option value="">Semua Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->kode_customer }}" {{ request('customer') == $customer->kode_customer ? 'selected' : '' }}>
                        {{ $customer->nama_customer }}
                    </option>
                @endforeach
            </select>
            <select name="program" class="form-select">
                <option value="">Semua Program</option>
                @foreach ($programList as $p)
                    <option value="{{ $p->kode_program }}" {{ request('program') == $p->kode_program ? 'selected' : '' }}>
                        {{ $p->nama_program }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Program</th>
                <th>Periode</th>
                <th>Target</th>
                <th>PIC</th>
                <th>Budget</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programs as $program)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($program->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $program->customer->nama_customer ?? '-' }}</td>
                    <td>{{ $program->program->nama_program ?? '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($program->start_date)->format('d-m-Y') }} s/d
                        {{ \Carbon\Carbon::parse($program->end_date)->format('d-m-Y') }}
                    </td>
                    <td>{{ $program->target }}</td>
                    <td>{{ $program->pic }}</td>
                    <td>Rp {{ number_format($program->budget, 0, ',', '.') }}</td>
                    <td>
                        @if ($program->file_path)
                            <a href="{{ asset('storage/' . $program->file_path) }}" target="_blank">Lihat File</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('program-berjalan.edit', $program->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('program-berjalan.destroy', $program->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
