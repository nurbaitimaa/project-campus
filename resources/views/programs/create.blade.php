@extends('layouts.admin')

@section('page-title', 'Tambah Master Program')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('programs.store') }}" method="POST">
        @csrf
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Detail Master Program</h2>

            {{-- Form dengan layout satu kolom --}}
            <div class="space-y-6">

                <div>
                    <label for="kode_program" class="block text-sm font-medium text-slate-700">Kode Program</label>
                    <input type="text" name="kode_program" id="kode_program" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('kode_program') }}" required placeholder="Contoh: PROG001">
                </div>

                <div>
                    <label for="nama_program" class="block text-sm font-medium text-slate-700">Nama Program</label>
                    <input type="text" name="nama_program" id="nama_program" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('nama_program') }}" required placeholder="Contoh: Diskon Lebaran">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan secara singkat mengenai program ini">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="jenis_program" class="block text-sm font-medium text-slate-700">Jenis Program</label>
                    <select name="jenis_program" id="jenis_program" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="diskon" {{ old('jenis_program') == 'diskon' ? 'selected' : '' }}>Diskon</option>
                        <option value="bundling" {{ old('jenis_program') == 'bundling' ? 'selected' : '' }}>Bundling</option>
                        <option value="target_penjualan" {{ old('jenis_program') == 'target_penjualan' ? 'selected' : '' }}>Target Penjualan</option>
                    </select>
                </div>

                <div>
                    <label for="parameter_klaim" class="block text-sm font-medium text-slate-700">Parameter Klaim</label>
                    <select name="parameter_klaim" id="parameter_klaim" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Parameter --</option>
                        <option value="per_item" {{ old('parameter_klaim') == 'per_item' ? 'selected' : '' }}>Per Item</option>
                        <option value="persen" {{ old('parameter_klaim') == 'persen' ? 'selected' : '' }}>Persentase</option>
                        <option value="nominal" {{ old('parameter_klaim') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                    </select>
                </div>

                <div>
                    <label for="tipe_klaim" class="block text-sm font-medium text-slate-700">Tipe Klaim</label>
                    <select name="tipe_klaim" id="tipe_klaim" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="rupiah" {{ old('tipe_klaim') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                        <option value="unit" {{ old('tipe_klaim') == 'unit' ? 'selected' : '' }}>Unit</option>
                        <option value="persen" {{ old('tipe_klaim') == 'persen' ? 'selected' : '' }}>Persen</option>
                    </select>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('programs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Simpan Master Program
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
