@extends('layouts.admin')

@section('page-title', 'Tambah Program Berjalan')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('program-berjalan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Detail Program Berjalan</h2>

            {{-- Grid untuk form --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Informasi Utama --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-600 border-b pb-2 mb-4">Informasi Utama</h3>
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-slate-700">Tanggal Input</label>
                    <input type="date" name="tanggal" id="tanggal" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                </div>

                <div>
                    <label for="pic" class="block text-sm font-medium text-slate-700">PIC</label>
                    <input type="text" name="pic" id="pic" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('pic') }}" placeholder="Nama Penanggung Jawab">
                </div>

                <div class="md:col-span-2">
                    <label for="kode_customer" class="block text-sm font-medium text-slate-700">Customer</label>
                    <select name="kode_customer" id="kode_customer" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->kode_customer }}" {{ old('kode_customer') == $customer->kode_customer ? 'selected' : '' }}>{{ $customer->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="kode_program" class="block text-sm font-medium text-slate-700">Master Program</label>
                    <select name="kode_program" id="kode_program" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Master Program --</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->kode_program }}" {{ old('kode_program') == $program->kode_program ? 'selected' : '' }}>{{ $program->nama_program }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Periode Program</label>
                    <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="date" name="start_date" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('start_date') }}" required>
                        <input type="date" name="end_date" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('end_date') }}" required>
                    </div>
                </div>

                {{-- Aturan & Reward --}}
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-semibold text-slate-600 border-b pb-2 mb-4">Aturan & Reward</h3>
                </div>

                <div>
                    <label for="min_pembelian" class="block text-sm font-medium text-slate-700">Syarat Minimal Pembelian</label>
                    <input type="number" step="0.01" name="min_pembelian" id="min_pembelian" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('min_pembelian') }}" placeholder="Contoh: 500000">
                </div>

                <div class="grid grid-cols-2 gap-4">
                     <div>
                        <label for="reward" class="block text-sm font-medium text-slate-700">Nilai Reward</label>
                        <input type="number" step="0.01" name="reward" id="reward" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('reward') }}" placeholder="Contoh: 10000">
                    </div>
                    <div>
                        <label for="reward_type" class="block text-sm font-medium text-slate-700">Tipe Reward</label>
                        <select name="reward_type" id="reward_type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="rupiah" {{ old('reward_type') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                            <option value="unit" {{ old('reward_type') == 'unit' ? 'selected' : '' }}>Unit</option>
                            <option value="persen" {{ old('reward_type') == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                        </select>
                    </div>
                </div>
                
                {{-- Informasi Tambahan --}}
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-semibold text-slate-600 border-b pb-2 mb-4">Informasi Tambahan</h3>
                </div>
                
                <div class="md:col-span-2">
                    <label for="target" class="block text-sm font-medium text-slate-700">Deskripsi Target/Hadiah</label>
                    <input type="text" name="target" id="target" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('target') }}" placeholder="Contoh: Mendapatkan Diskon 5% atau Hadiah 1 Pcs Piring Cantik">
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-slate-700">Keterangan / Mekanisme Lengkap</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="file_path" class="block text-sm font-medium text-slate-700">Upload Lampiran (Opsional)</label>
                    <input type="file" name="file_path" id="file_path" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('program-berjalan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Simpan Program
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
