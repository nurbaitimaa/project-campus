@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-bold text-primary mb-4">Klaim Program</h4>

    <form action="{{ route('program-claims.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Informasi Umum --}}
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label>Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" class="form-control" value="{{ $kodeTransaksi }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Tanggal Klaim</label>
                    <input type="date" name="tanggal_klaim" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Pilih Program Berjalan</label>
                    <select name="program_berjalan_id" id="programSelect" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($programs as $pb)
                            <option value="{{ $pb->id }}">
                                {{ $pb->program->nama_program }} - {{ $pb->customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Upload Bukti Klaim</label>
                    <input type="file" name="bukti_klaim" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-2">
                    <label>Customer</label>
                    <input type="text" id="customer" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label>Nama Program</label>
                    <input type="text" id="nama_program" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label>Jenis Program</label>
                    <input type="text" id="jenis_program" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label>Parameter Klaim</label>
                    <input type="text" id="parameter" class="form-control" readonly>
                </div>
            </div>
        </div>

        {{-- Tabel Klaim Outlet --}}
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Nama Outlet</th>
                        <th>Penjualan</th>
                        <th>Jumlah Klaim</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="claimTableBody">
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="outlets[0][nama]" class="form-control"></td>
                        <td><input type="number" name="outlets[0][penjualan]" class="form-control penjualan-input"></td>
                        <td><input type="text" name="outlets[0][klaim]" class="form-control klaim-output" readonly></td>
                        <td><input type="text" name="outlets[0][keterangan]" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBaris()">+ Tambah Baris</button>
        </div>

        {{-- Total Pembelian --}}
        <div class="mt-3 text-end">
            <label>Total Pembelian</label>
            <input type="text" id="totalPembelian" name="total_pembelian" class="form-control d-inline-block text-end" style="width: 200px;" readonly>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('program-claims.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        {{-- Hidden Input --}}
        <input type="hidden" id="tipe_klaim">
        <input type="hidden" id="nilai_klaim">
    </form>
</div>

{{-- Script --}}
<script>
    let paramKlaim = 0;

    document.getElementById('programSelect').addEventListener('change', function () {
    const id = this.value;
    if (!id) return;

    fetch(`/program-claims/fetch/${id}`, {
        method: 'GET',
        credentials: 'same-origin' // ⬅️ Tambahkan ini agar cookie Laravel terbaca
    })
    .then(res => {
        if (!res.ok) throw new Error("Gagal fetch data program.");
        return res.json();
    })
    .then(data => {
        document.getElementById('customer').value = data.customer;
        document.getElementById('nama_program').value = data.nama_program;
        document.getElementById('jenis_program').value = data.jenis_program;
        document.getElementById('parameter').value = data.parameter_klaim;
        document.getElementById('tipe_klaim').value = data.tipe_klaim;
        document.getElementById('nilai_klaim').value = data.nilai_klaim;

        paramKlaim = parseFloat(data.nilai_klaim) || 0;
        updateKlaim();
    })
    .catch(error => {
        console.error("Error:", error);
    });
});


    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('penjualan-input')) {
            updateKlaim();
        }
    });

    function updateKlaim() {
        const tipe = document.getElementById('tipe_klaim').value;
        const nilai = parseFloat(document.getElementById('nilai_klaim').value) || 0;
        let total = 0;

        document.querySelectorAll('#claimTableBody tr').forEach((row, i) => {
            const penjualanInput = row.querySelector('.penjualan-input');
            const klaimInput = row.querySelector('.klaim-output');
            const penjualan = parseFloat(penjualanInput.value) || 0;

            let klaim = 0;
            if (tipe === 'rupiah') {
                klaim = penjualan * nilai;
            } else if (tipe === 'unit') {
                klaim = Math.round(penjualan * nilai);
            } else {
                klaim = Math.round(penjualan * nilai / 100); // fallback persentase
            }

            klaimInput.value = klaim;
            total += penjualan;
        });

        document.getElementById('totalPembelian').value = total;
    }

    function tambahBaris() {
        const tbody = document.getElementById('claimTableBody');
        const rowCount = tbody.rows.length;
        const row = `
            <tr>
                <td>${rowCount + 1}</td>
                <td><input type="text" name="outlets[${rowCount}][nama]" class="form-control"></td>
                <td><input type="number" name="outlets[${rowCount}][penjualan]" class="form-control penjualan-input"></td>
                <td><input type="text" name="outlets[${rowCount}][klaim]" class="form-control klaim-output" readonly></td>
                <td><input type="text" name="outlets[${rowCount}][keterangan]" class="form-control"></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    }
</script>
@endsection
