@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Program</h2>

    <form action="{{ route('programs.update', $program->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="kode_program" class="form-label">Kode Program</label>
            <input type="text" name="kode_program" class="form-control" value="{{ $program->kode_program }}" required>
        </div>

        <div class="mb-3">
            <label for="nama_program" class="form-label">Nama Program</label>
            <input type="text" name="nama_program" class="form-control" value="{{ $program->nama_program }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ $program->deskripsi }}</textarea>
        </div>

        <a href="{{ route('programs.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
