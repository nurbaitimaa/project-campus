@extends('layouts.admin') {{-- Menggunakan layout admin untuk konsistensi --}}

@section('content')
<div class="container mx-auto mt-6 px-4 sm:px-6 lg:px-8"> {{-- Container utama dengan padding responsif --}}
    <h2 class="text-2xl font-bold text-slate-800 mb-4">Tambah Customer Baru</h2>

    {{-- Pesan error validasi dari Laravel --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4 shadow-sm">
            <strong class="font-bold">Terjadi kesalahan!</strong>
            <span class="block sm:inline">Mohon periksa kembali input Anda.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-xl"> {{-- Card-like container untuk form --}}
        <h3 class="text-xl font-semibold text-slate-800 mb-6">Informasi Customer</h3>
        
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="mb-4"> {{-- Margin bottom lebih besar untuk setiap field --}}
                <label for="kode_customer" class="block font-semibold text-slate-700 mb-1">Kode Customer</label>
                <input type="text" id="kode_customer" name="kode_customer" class="form-control w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('kode_customer') }}" required>
            </div>

            <div class="mb-4">
                <label for="nama_customer" class="block font-semibold text-slate-700 mb-1">Nama Customer</label>
                <input type="text" id="nama_customer" name="nama_customer" class="form-control w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('nama_customer') }}" required>
            </div>

            <div class="mb-4">
                <label for="alamat" class="block font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" class="form-control w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-6"> {{-- Margin bottom lebih besar sebelum tombol --}}
                <label for="telepon" class="block font-semibold text-slate-700 mb-1">Telepon</label>
                <input type="text" id="telepon" name="telepon" class="form-control w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('telepon') }}">
            </div>

            <div class="flex items-center justify-end"> {{-- Penempatan tombol di kanan bawah --}}
                <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md ml-3">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
