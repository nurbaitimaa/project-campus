@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Customer</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">+ Tambah Customer</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode Customer</th>
                <th>Nama Customer</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->kode_customer }}</td>
                    <td>{{ $customer->nama_customer }}</td>
                    <td>{{ $customer->alamat }}</td>
                    <td>{{ $customer->telepon }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus customer ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Data belum tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
