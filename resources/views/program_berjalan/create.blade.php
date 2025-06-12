@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Tambah Program Berjalan</h4>

    <form action="{{ route('program-berjalan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="tanggal_input">Tanggal Input</label>
            <input type="date" name="tanggal_input" id="tanggal_input" class="form-control" required>
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
        <div class="mb-3" id="input-klaim-wrapper">
            <!-- Akan diisi via JavaScript -->
        </div>

        <div class="mb-3">
            <label>Periode Program</label>
            <div class="d-flex gap-2">
                <input type="date" name="periode_awal" class="form-control" required>
                <input type="date" name="periode_akhir" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="target_reward">Target Reward</label>
            <input type="text" name="target_reward" id="target_reward" class="form-control">
        </div>

        <div class="mb-3">
            <label for="pic">PIC</label>
            <input type="text" name="pic" id="pic" class="form-control">
        </div>

        <div class="mb-3">
            <label for="deskripsi">Deskripsi / Mekanisme</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="budget">Budget (Rp)</label>
            <input type="number" name="budget" id="budget" class="form-control">
        </div>

        <div class="mb-3">
            <label for="file">Upload File (PDF/DOC)</label>
            <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx">
            <small class="form-text text-muted">Ukuran maksimal 2MB. Format: PDF, DOC, DOCX</small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('program-berjalan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- JavaScript untuk input klaim dinamis -->
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
            .then(res => {
                if (!res.ok) {
                    throw new Error('Gagal mengambil data program');
                }
                return res.json();
            })
            .then(data => {
                klaimWrapper.innerHTML = '';

                if (data.parameter_klaim === 'item') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_item">Item Klaim</label>
                        <input type="text" name="klaim_item" id="klaim_item" class="form-control" required>
                    `;
                } else if (data.parameter_klaim === 'persentase') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_persen">Klaim Persentase (%)</label>
                        <input type="number" name="klaim_persen" id="klaim_persen" class="form-control" step="0.01" required>
                    `;
                } else if (data.parameter_klaim === 'nominal') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_nominal">Klaim Nominal (Rp)</label>
                        <input type="number" name="klaim_nominal" id="klaim_nominal" class="form-control" required>
                    `;
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat memuat data program. Silakan coba lagi.');
                klaimWrapper.innerHTML = '';
            });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("JS Loaded ✅"); // 👈 Tambahan log

    const selectProgram = document.getElementById('kode_program');
    const klaimWrapper = document.getElementById('input-klaim-wrapper');

    selectProgram.addEventListener('change', function () {
        const programId = this.value;
        console.log("Program selected:", programId); // 👈 Tambahan log

        if (!programId) return;

        fetch(`/program-detail/${programId}`)
            .then(res => res.json())
            .then(data => {
                console.log("FETCH RESPONSE:", data); // 👈 Tambahan log

                klaimWrapper.innerHTML = '';

                if (data.parameter_klaim === 'item') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_item">Item Klaim</label>
                        <input type="text" name="klaim_item" id="klaim_item" class="form-control" required>
                    `;
                } else if (data.parameter_klaim === 'persentase') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_persen">Klaim Persentase (%)</label>
                        <input type="number" name="klaim_persen" id="klaim_persen" class="form-control" step="0.01" required>
                    `;
                } else if (data.parameter_klaim === 'nominal') {
                    klaimWrapper.innerHTML = `
                        <label for="klaim_nominal">Klaim Nominal (Rp)</label>
                        <input type="number" name="klaim_nominal" id="klaim_nominal" class="form-control" required>
                    `;
                }
            })
            .catch(error => {
                console.error("AJAX ERROR:", error);
            });
    });
});
</script>


@endsection
