@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white p-4 rounded shadow border">
        <h2 class="text-xl font-bold mb-4">Daftar Inventory</h2>
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('inventory.create') }}" class="bg-blue-500 text-white px-3 py-2 rounded mb-2 inline-block">+ Tambah Barang</a>
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Nama Barang</th>
                    <th class="border px-2 py-1">Deskripsi</th>
                    <th class="border px-2 py-1">Stok</th>
                    <th class="border px-2 py-1">Satuan</th>
                    <th class="border px-2 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $item)
                <tr>
                    <td class="border px-2 py-1">{{ $item->nama_barang }}</td>
                    <td class="border px-2 py-1">{{ $item->deskripsi }}</td>
                    <td class="border px-2 py-1">{{ $item->stok }}</td>
                    <td class="border px-2 py-1">{{ $item->satuan }}</td>
                    <td class="border px-2 py-1">
                        <a href="{{ route('inventory-transaction.create', ['inventory_id' => $item->id]) }}" class="bg-red-500 text-white px-2 py-1 rounded">Mutasi</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white p-4 rounded shadow border">
        <h2 class="text-xl font-bold mb-4">Tambah Transaksi Inventory</h2>
        <form action="{{ route('inventory-transaction.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="inventory_id" class="block font-medium">Pilih Barang</label>
                <select name="inventory_id" id="inventory_id" class="w-full border rounded px-3 py-2">
                    @foreach ($inventories as $inv)
                        <option value="{{ $inv->id }}"
                            {{ request('inventory_id') == $inv->id ? 'selected' : '' }}>
                            {{ $inv->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tipe" class="block font-medium">Tipe Transaksi</label>
                <select name="tipe" id="tipe" class="w-full border rounded px-3 py-2">
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="block font-medium">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="block font-medium">Keterangan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Transaksi</button>
        </form>
    </div>
</div>
@endsection
