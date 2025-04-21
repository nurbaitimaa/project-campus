@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Data Sales Marketing</h2>

    <form action="{{ route('sales-marketing.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="kode_sales" class="form-label">Kode Sales</label>
            <input type="text" name="kode_sales" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama_sales" class="form-label">Nama</label>
            <input type="text" name="nama_sales" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" required>
        </div>

        <a href="{{ route('sales-marketing.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
