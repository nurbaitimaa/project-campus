@extends('layouts.app') <!-- ganti sesuai layout utama kamu -->

@section('content')
<div class="container">
    <h4>Edit Absensi - {{ $absensi->salesMarketing->nama ?? 'Nama Sales' }}</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" class="form-control" value="{{ $absensi->tanggal }}" disabled>
        </div>

        <div class="mb-3">
            <label>Status Kehadiran</label>
            <select name="status" class="form-control" required>
                <option value="Hadir" {{ $absensi->status === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Izin" {{ $absensi->status === 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Alfa" {{ $absensi->status === 'Alfa' ? 'selected' : '' }}>Alfa</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" class="form-control" value="{{ $absensi->jam_masuk }}">
        </div>

        <div class="mb-3">
            <label>Jam Keluar</label>
            <input type="time" name="jam_keluar" class="form-control" value="{{ $absensi->jam_keluar }}">
        </div>

        <div class="mb-3">
            <label>Latitude (Lokasi Masuk)</label>
            <input type="text" name="latitude" class="form-control" value="{{ $absensi->latitude }}">
        </div>

        <div class="mb-3">
            <label>Longitude (Lokasi Keluar)</label>
            <input type="text" name="longitude" class="form-control" value="{{ $absensi->longitude }}">
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ $absensi->keterangan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
