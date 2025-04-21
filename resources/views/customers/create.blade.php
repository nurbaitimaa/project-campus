@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Tambah Customer</h3>

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="kode_customer" class="form-label">Kode Customer</label>
            <input type="text" class="form-control" name="kode_customer" required>
        </div>

        <div class="mb-3">
            <label for="nama_customer" class="form-label">Nama Customer</label>
            <input type="text" class="form-control" name="nama_customer" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control" name="telepon">
        </div>

        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
