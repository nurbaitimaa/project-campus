@extends('layouts.app')
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Program</h2>
        <a href="{{ route('programs.create') }}" class="btn btn-primary">Tambah Program</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kode Program</th>
                <th>Nama Program</th>
                <th>Deskripsi</th>
                <th>Jenis</th>
                <th>Parameter</th>
                <th>Aksi</th> {{-- Aksi dipindah ke paling akhir --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->kode_program }}</td>
                    <td>{{ $item->nama_program }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->jenis_program ?? '-' }}</td>
                    <td>{{ $item->parameter_klaim ?? '-' }}</td>
                    <td>
                        <a href="{{ route('programs.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('programs.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
