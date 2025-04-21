@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Sales Marketing</h2>
        <a href="{{ route('sales-marketing.create') }}" class="btn btn-primary">Tambah Data</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->kode_sales }}</td>
                    <td>{{ $item->nama_sales }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>
                        <a href="{{ route('sales-marketing.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('sales-marketing.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data?')">
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
