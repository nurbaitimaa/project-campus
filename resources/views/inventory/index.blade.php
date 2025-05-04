@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-4">📦 Daftar Inventory</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Tambah Barang -->
    <a href="{{ route('inventory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        ➕ Tambah Barang
    </a>

    <!-- Tabel Inventory -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border text-left">#</th>
                    <th class="px-4 py-2 border text-left">Nama Barang</th>
                    <th class="px-4 py-2 border text-left">Deskripsi</th>
                    <th class="px-4 py-2 border text-left">Stok Awal</th>
                    <th class="px-4 py-2 border text-left">Satuan</th>
                    <th class="px-4 py-2 border text-left">Stok Akhir</th>
                    <th class="px-4 py-2 border text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $item->nama_barang }}</td>
                    <td class="px-4 py-2 border">{{ $item->deskripsi }}</td>
                    <td class="px-4 py-2 border">{{ $item->stok_awal }}</td>
                    <td class="px-4 py-2 border">{{ $item->satuan }}</td>
                    <td class="px-4 py-2 border">{{ $item->stok_akhir }}</td>
                    <td class="px-4 py-2 border space-y-1">
    <a href="{{ route('inventory.transaction.index', ['inventory' => $item->id]) }}"
       class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition inline-block w-full text-center">
        🔍 Transaksi
    </a>
    <a href="{{ route('inventory-transaction.create', ['inventory_id' => $item->id]) }}"
       class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition inline-block w-full text-center">
        🔄 Mutasi
    </a>
    <a href="{{ route('inventory.edit', $item->id) }}"
       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition inline-block w-full text-center">
        ✏️ Edit
    </a>
    <form action="{{ route('inventory.destroy', $item->id) }}" method="POST"
          onsubmit="return confirm('Yakin ingin menghapus data?')" class="inline-block w-full">
        @csrf
        @method('DELETE')
        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition w-full">
            🗑 Hapus
        </button>
    </form>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
