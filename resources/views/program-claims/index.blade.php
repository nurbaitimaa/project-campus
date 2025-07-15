@extends('layouts.admin')

@section('page-title', 'Daftar Klaim Program')

@section('content')
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Kontainer Utama untuk Konten --}}
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
        
        {{-- Header Konten (Judul & Tombol) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Manajemen Klaim Program</h2>
            <a href="{{ route('program-claims.create') }}" class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Klaim Baru
            </a>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Klaim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Klaim</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $grandTotalPembelian = 0;
                        $grandTotalKlaim = 0;
                    @endphp
                    @forelse ($programClaims as $klaim)
                        @php
                            $grandTotalPembelian += $klaim->total_pembelian;
                            $grandTotalKlaim += $klaim->total_klaim;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $klaim->kode_transaksi }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($klaim->tanggal_klaim)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800">{{ $klaim->programBerjalan->program->nama_program ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $klaim->programBerjalan->customer->nama_customer ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($klaim->total_pembelian, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold text-right">Rp {{ number_format($klaim->total_klaim, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($klaim->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($klaim->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($klaim->status === 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-4">
                                    <a href="{{ route('program-claims.preview', $klaim->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Preview">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('program-claims.edit', $klaim->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('program-claims.destroy', $klaim->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klaim ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data klaim.</td></tr>
                    @endforelse
                </tbody>
                @if($programClaims->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr class="font-bold">
                        <td colspan="2" class="px-6 py-3 text-right text-sm text-gray-700">Total Keseluruhan:</td>
                        <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($grandTotalPembelian, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($grandTotalKlaim, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
