@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Sales Marketing</h2>

    <form action="{{ route('sales-marketing.update', $salesMarketing->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="kode_sales" class="form-label">Kode Sales</label>
            <input type="text" name="kode_sales" class="form-control" value="{{ $salesMarketing->kode_sales }}" required>
        </div>

        <div class="mb-3">
            <label for="nama_sales" class="form-label">Nama</label>
            <input type="text" name="nama_sales" class="form-control" value="{{ $salesMarketing->nama_sales }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $salesMarketing->email }}" required>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ $salesMarketing->telepon }}" required>
        </div>

        <a href="{{ route('sales-marketing.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
