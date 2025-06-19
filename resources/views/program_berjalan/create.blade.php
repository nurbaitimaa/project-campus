@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Tambah Program Berjalan</h4>

    <form action="{{ route('program-berjalan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="tanggal">Tanggal Input</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="kode_customer">Customer</label>
            <select name="kode_customer" id="kode_customer" class="form-control" required>
                <option value="">-- Pilih Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->kode_customer }}">{{ $customer->nama_customer }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="kode_program">Program</label>
            <select name="kode_program" id="kode_program" class="form-control" required>
                <option value="">-- Pilih Program --</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->kode_program }}">{{ $program->nama_program }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Klaim Dinamis -->
        <div class="mb-3" id="input-klaim-wrapper"></div>

        <div class="mb-3">
            <label>Periode Program</label>
            <div class="d-flex gap-2">
                <input type="date" name="start_date" class="form-control" required>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="target">Target Reward</label>
            <input type="text" name="target" id="target" class="form-control">
        </div>

        <div class="mb-3">
            <label for="pic">PIC</label>
            <input type="text" name="pic" id="pic" class="form-control">
        </div>

        <div class="mb-3">
            <label for="keterangan">Deskripsi / Mekanisme</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="budget">Budget (Rp)</label>
            <input type="number" name="budget" id="budget" class="form-control">
        </div>

        <div class="mb-3">
            <label for="file_path">Upload File (PDF/DOC)</label>
            <input type="file" name="file_path" id="file_path" class="form-control" accept=".pdf,.doc,.docx">
            <small class="form-text text-muted">Ukuran maksimal 2MB. Format: PDF, DOC, DOCX</small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('program-berjalan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectProgram = document.getElementById('kode_program');
    const klaimWrapper = document.getElementById('input-klaim-wrapper');

    selectProgram.addEventListener('change', function () {
        const programId = this.value;
        if (!programId) {
            klaimWrapper.innerHTML = '';
            return;
        }

        fetch(`/program-detail/${programId}`)
            .then(res => res.json())
            .then(data => {
                klaimWrapper.innerHTML = '';
                if (data.parameter_klaim === 'item') {
                    klaimWrapper.innerHTML = `
                        <label for="nilai_klaim_per_item">Nilai Klaim per Item</label>
                        <input type="number" name="nilai_klaim_per_item" id="nilai_klaim_per_item" class="form-control" required>
                    `;
                } else if (data.parameter_klaim === 'persen') {
                    klaimWrapper.innerHTML = `
                        <label for="persen_klaim">Persentase Klaim (%)</label>
                        <input type="number" step="0.01" name="persen_klaim" id="persen_klaim" class="form-control" required>
                    `;
                } else if (data.parameter_klaim === 'rupiah') {
                    klaimWrapper.innerHTML = `
                        <label for="nominal_klaim">Nominal Klaim (Rp)</label>
                        <input type="number" name="nominal_klaim" id="nominal_klaim" class="form-control" required>
                    `;
                }
            })
            .catch(error => {
                console.error("AJAX ERROR:", error);
                klaimWrapper.innerHTML = '';
            });
    });
});
</script>
@endsection
