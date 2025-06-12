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

        <div class="mb-3">
            <label for="jenis_program" class="form-label">Jenis Program</label>
            <select name="jenis_program" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="diskon" {{ $program->jenis_program == 'diskon' ? 'selected' : '' }}>Diskon</option>
                <option value="bundling" {{ $program->jenis_program == 'bundling' ? 'selected' : '' }}>Bundling</option>
                <option value="target_penjualan" {{ $program->jenis_program == 'target_penjualan' ? 'selected' : '' }}>Target Penjualan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="parameter_klaim" class="form-label">Parameter Klaim</label>
            <select name="parameter_klaim" class="form-control" required>
                <option value="">-- Pilih Parameter --</option>
                <option value="per_item" {{ $program->parameter_klaim == 'per_item' ? 'selected' : '' }}>Per Item</option>
                <option value="persen" {{ $program->parameter_klaim == 'persen' ? 'selected' : '' }}>Persentase</option>
                <option value="nominal" {{ $program->parameter_klaim == 'nominal' ? 'selected' : '' }}>Nominal</option>
            </select>
        </div>

        <a href="{{ route('programs.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
