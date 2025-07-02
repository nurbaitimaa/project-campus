@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Program Berjalan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada beberapa masalah:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('program-berjalan.update', $program->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ============================================= --}}
        {{-- --- KELOMPOK INFORMASI UTAMA --- --}}
        {{-- ============================================= --}}
        <h5 class="fw-bold text-primary mt-4">Informasi Utama Program</h5>
        <div class="p-3 border rounded bg-light mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal">Tanggal Input</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $program->tanggal) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_customer">Customer</label>
                    <select name="kode_customer" id="kode_customer" class="form-control" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->kode_customer }}" {{ old('kode_customer', $program->kode_customer) == $customer->kode_customer ? 'selected' : '' }}>
                                {{ $customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="kode_program">Master Program</label>
                <select name="kode_program" id="kode_program" class="form-control" required>
                    <option value="">-- Pilih Master Program --</option>
                    @foreach ($programs as $prog)
                        <option value="{{ $prog->kode_program }}" {{ old('kode_program', $program->kode_program) == $prog->kode_program ? 'selected' : '' }}>
                            {{ $prog->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Periode Program</label>
                <div class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $program->start_date) }}" required>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $program->end_date) }}" required>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- --- KELOMPOK ATURAN/MEKANISME KLAIM --- --}}
        {{-- ============================================= --}}
        <h5 class="fw-bold text-primary">Aturan dan Reward Program</h5>
        <div class="p-3 border rounded bg-light mb-4">
            <div class="mb-3">
                <label for="min_pembelian" class="form-label fw-semibold">Syarat Minimal Pembelian (Per Transaksi)</label>
                <input type="number" step="0.01" name="min_pembelian" class="form-control" value="{{ old('min_pembelian', $program->min_pembelian) }}" placeholder="Contoh: 500000">
                <small class="form-text text-muted">Isi dengan nominal (misal: 500000) atau jumlah unit (misal: 10) sebagai syarat mendapatkan reward.</small>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="reward" class="form-label fw-semibold">Nilai Reward</label>
                        <input type="number" step="0.01" name="reward" class="form-control" value="{{ old('reward', $program->reward) }}" placeholder="Contoh: 10000 atau 5">
                        <small class="form-text text-muted">Isi dengan nominal rupiah (jika reward uang) atau persentase (jika reward diskon).</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reward_type" class="form-label fw-semibold">Tipe Reward</label>
                        <select name="reward_type" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="rupiah" {{ old('reward_type', $program->reward_type) == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                            <option value="unit" {{ old('reward_type', $program->reward_type) == 'unit' ? 'selected' : '' }}>Unit</option>
                            <option value="persen" {{ old('reward_type', $program->reward_type) == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- --- KELOMPOK INFORMASI TAMBAHAN --- --}}
        {{-- ============================================= --}}
        <h5 class="fw-bold text-primary">Informasi Tambahan</h5>
        <div class="p-3 border rounded bg-light mb-4">
            <div class="mb-3">
                <label for="target" class="form-label">Deskripsi Target/Hadiah</label>
                <input type="text" name="target" id="target" class="form-control" value="{{ old('target', $program->target) }}" placeholder="Contoh: Mendapatkan Diskon 5% atau Hadiah 1 Pcs Piring Cantik">
                <small class="form-text text-muted">Deskripsi singkat mengenai target atau hadiah yang akan diterima customer.</small>
            </div>
            <div class="mb-3">
                <label for="pic">PIC</label>
                <input type="text" name="pic" id="pic" class="form-control" value="{{ old('pic', $program->pic) }}">
            </div>
            <div class="mb-3">
                <label for="keterangan">Keterangan / Mekanisme Lengkap</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="form-control">{{ old('keterangan', $program->keterangan) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="file_path">Upload File Lampiran Baru (Opsional)</label>
                <input type="file" name="file_path" id="file_path" class="form-control" accept=".pdf,.doc,.docx">
                @if ($program->file_path)
                    <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $program->file_path) }}" target="_blank">Lihat File</a></small>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('program-berjalan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

{{-- Script untuk Select2 tetap dipertahankan karena merupakan peningkatan UX --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#kode_customer').select2({ placeholder: "Pilih Customer" });
        $('#kode_program').select2({ placeholder: "Pilih Program" });
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
