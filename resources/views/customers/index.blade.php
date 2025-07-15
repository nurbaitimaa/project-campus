@extends('layouts.admin') {{-- Pastikan ini tetap mengacu ke layouts.admin --}}

@section('content')
<div class="container mx-auto mt-6 px-4 sm:px-6 lg:px-8"> {{-- Tambah padding horizontal untuk container utama --}}
    <h2 class="text-2xl font-bold text-slate-800 mb-4">Daftar Customer</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4 shadow-sm"> {{-- Rounded-md dan shadow-sm --}}
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-xl"> {{-- Shadow-xl untuk efek yang lebih menonjol --}}
        <div class="flex justify-between items-center mb-6"> {{-- Margin-bottom lebih besar --}}
            <h3 class="text-xl font-semibold text-slate-800">Manajemen Customer</h3>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md"> {{-- Gaya tombol Tailwind --}}
                <i class="bi bi-plus-circle mr-2"></i> Tambah Customer
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm"> {{-- Border dan shadow untuk tabel --}}
            <table class="min-w-full divide-y divide-gray-200"> {{-- Ganti kelas Bootstrap tabel dengan Tailwind untuk kontrol lebih --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">No</th> {{-- Rounded sudut --}}
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Aksi</th> {{-- Rounded sudut --}}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customers as $index => $customer)
                        <tr class="hover:bg-gray-50"> {{-- Hover state untuk baris --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->kode_customer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->nama_customer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->alamat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->telepon }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm"> {{-- Gaya tombol Tailwind --}}
                                    Edit
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block ml-2"
                                      onsubmit="return confirm('Yakin ingin menghapus customer ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm"> {{-- Gaya tombol Tailwind --}}
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Data customer belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
    {{-- Tambahkan Bootstrap Icons jika belum ada di layout utama --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endpush
@endsection
