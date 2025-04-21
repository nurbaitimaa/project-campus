@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Data Program</h2>

    <form action="{{ route('programs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="kode_program" class="form-label">Kode Program</label>
            <input type="text" name="kode_program" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama_program" class="form-label">Nama Program</label>
            <input type="text" name="nama_program" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        <a href="{{ route('programs.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
