@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-4">Transaksi: {{ $inventory->nama_barang }}</h2>

    <div class="mb-4">
        <a href="{{ route('inventory.transaction.create', $inventory->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow">
            Tambah Transaksi
        </a>
        <a href="{{ route('inventory.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded shadow">
            Kembali ke Inventory
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Tipe</th>
                    <th class="px-4 py-2 border">Jumlah</th>
                    <th class="px-4 py-2 border">Keterangan</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-4 py-2 border">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($transaction->tipe) }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->jumlah }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->keterangan }}</td>
                        <td class="px-4 py-2 border">
                            <form action="{{ route('inventory-transaction.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
