@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-4">Edit Inventory</h2>

    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama_barang" class="block font-semibold">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ $inventory->nama_barang }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block font-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border rounded px-3 py-2">{{ $inventory->deskripsi }}</textarea>
        </div>

        <div class="mb-4">
            <label for="stok_awal" class="block font-semibold">Stok Awal</label>
            <input type="number" name="stok_awal" value="{{ $inventory->stok_awal }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="satuan" class="block font-semibold">Satuan</label>
            <input type="text" name="satuan" value="{{ $inventory->satuan }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Update</button>
    </form>
</div>
@endsection
