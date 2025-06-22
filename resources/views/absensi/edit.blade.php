@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6 max-w-2xl">
    <h2 class="text-2xl font-bold mb-4">Edit Absensi</h2>

    <form method="POST" action="{{ route('absensi.update', $absensi->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold">Nama Sales:</label>
            <input type="text" value="{{ $absensi->salesMarketing->nama_sales }}" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Tanggal:</label>
            <input type="date" value="{{ $absensi->tanggal }}" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Jam Masuk:</label>
            <input type="time" name="jam_masuk" value="{{ $absensi->jam_masuk }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Jam Keluar:</label>
            <input type="time" name="jam_keluar" value="{{ $absensi->jam_keluar }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Status:</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="Hadir" {{ $absensi->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Izin" {{ $absensi->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Alfa" {{ $absensi->status == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                <option value="Sakit" {{ $absensi->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Keterangan:</label>
            <textarea name="keterangan" class="w-full border rounded px-3 py-2">{{ $absensi->keterangan }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Foto Absensi:</label>
            @if($absensi->foto)
                <p class="mb-2">Foto Saat Ini:
                    <a href="{{ asset('storage/' . $absensi->foto) }}" target="_blank" class="text-blue-500 underline">Lihat Foto</a>
                </p>
            @endif
            <input type="file" name="foto" class="w-full border rounded px-3 py-2" accept="image/*">
            <small class="text-gray-500">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded">
                Update Absensi
            </button>
        </div>
    </form>
</div>
@endsection
