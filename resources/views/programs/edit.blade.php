@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Program</h2>

    <form action="{{ route('programs.update', $program->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Kode Program --}}
        <div class="mb-3">
            <label for="kode_program" class="form-label">Kode Program</label>
            <input type="text" name="kode_program" class="form-control" value="{{ old('kode_program', $program->kode_program) }}" required>
            @error('kode_program')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nama Program --}}
        <div class="mb-3">
            <label for="nama_program" class="form-label">Nama Program</label>
            <input type="text" name="nama_program" class="form-control" value="{{ old('nama_program', $program->nama_program) }}" required>
            @error('nama_program')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $program->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jenis Program --}}
        <div class="mb-3">
            <label for="jenis_program" class="form-label">Jenis Program</label>
            <select name="jenis_program" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="diskon" {{ old('jenis_program', $program->jenis_program) == 'diskon' ? 'selected' : '' }}>Diskon</option>
                <option value="bundling" {{ old('jenis_program', $program->jenis_program) == 'bundling' ? 'selected' : '' }}>Bundling</option>
                <option value="target_penjualan" {{ old('jenis_program', $program->jenis_program) == 'target_penjualan' ? 'selected' : '' }}>Target Penjualan</option>
            </select>
            @error('jenis_program')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Parameter Klaim --}}
        <div class="mb-3">
            <label for="parameter_klaim" class="form-label">Parameter Klaim</label>
            <select name="parameter_klaim" class="form-control" required>
                <option value="">-- Pilih Parameter --</option>
                <option value="per_item" {{ old('parameter_klaim', $program->parameter_klaim) == 'per_item' ? 'selected' : '' }}>Per Item</option>
                <option value="persen" {{ old('parameter_klaim', $program->parameter_klaim) == 'persen' ? 'selected' : '' }}>Persentase</option>
                <option value="nominal" {{ old('parameter_klaim', $program->parameter_klaim) == 'nominal' ? 'selected' : '' }}>Nominal</option>
            </select>
            @error('parameter_klaim')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tipe Klaim --}}
        <div class="mb-3">
            <label for="tipe_klaim" class="form-label">Tipe Klaim</label>
            <select name="tipe_klaim" class="form-control" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="rupiah" {{ old('tipe_klaim', $program->tipe_klaim) == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                <option value="unit" {{ old('tipe_klaim', $program->tipe_klaim) == 'unit' ? 'selected' : '' }}>Unit</option>
                <option value="persen" {{ old('tipe_klaim', $program->tipe_klaim) == 'persen' ? 'selected' : '' }}>Persen</option>
            </select>
            @error('tipe_klaim')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        {{-- Tombol --}}
        <div class="mt-4">
            <a href="{{ route('programs.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
