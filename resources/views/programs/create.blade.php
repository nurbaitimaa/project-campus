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

        <div class="mb-3">
            <label for="jenis_program" class="form-label">Jenis Program</label>
            <select name="jenis_program" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="diskon">Diskon</option>
                <option value="bundling">Bundling</option>
                <option value="target_penjualan">Target Penjualan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="parameter_klaim" class="form-label">Parameter Klaim</label>
            <select name="parameter_klaim" class="form-control" required>
                <option value="">-- Pilih Parameter --</option>
                <option value="per_item">Per Item</option>
                <option value="persen">Persentase</option>
                <option value="nominal">Nominal</option>
            </select>
        </div>

<div class="mb-3">
    <label for="tipe_klaim" class="form-label">Tipe Klaim</label>
    <select name="tipe_klaim" class="form-control" required>
        <option value="">-- Pilih --</option>
        <option value="rupiah" {{ old('tipe_klaim') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
        <option value="unit" {{ old('tipe_klaim') == 'unit' ? 'selected' : '' }}>Unit</option>
        <option value="persen" {{ old('tipe_klaim') == 'persen' ? 'selected' : '' }}>Persen</option>
    </select>
</div>

        <a href="{{ route('programs.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
