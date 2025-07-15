@extends('layouts.admin')

@section('page-title', 'Tambah Sales & Marketing')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('sales-marketing.store') }}" method="POST">
        @csrf
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Detail Sales & Marketing</h2>
            
            {{-- Form dengan layout satu kolom --}}
            <div class="space-y-6">
                <div>
                    <label for="kode_sales" class="block text-sm font-medium text-slate-700">Kode Sales</label>
                    <input type="text" name="kode_sales" id="kode_sales" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('kode_sales') }}" required placeholder="Contoh: SALES001">
                </div>
                <div>
                    <label for="nama_sales" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="nama_sales" id="nama_sales" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('nama_sales') }}" required placeholder="Masukkan nama sales">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('email') }}" required placeholder="contoh@email.com">
                </div>
                <div>
                    <label for="telepon" class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('telepon') }}" required placeholder="Contoh: 08123456789">
                </div>
            </div>
            
            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('sales-marketing.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
