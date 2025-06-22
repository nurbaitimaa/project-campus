@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-bold text-primary mb-4">Edit Klaim Program</h4>

    <form action="{{ route('program-claims.update', $programClaim->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Informasi Umum --}}
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label>Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" class="form-control" value="{{ $programClaim->kode_transaksi }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Tanggal Klaim</label>
                    <input type="date" name="tanggal_klaim" class="form-control" value="{{ $programClaim->tanggal_klaim }}" required>
                </div>
                <div class="mb-2">
                    <label>Program Berjalan</label>
                    <select name="program_berjalan_id" id="programSelect" class="form-select" required>
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $pb)
                            <option value="{{ $pb->id }}" {{ $pb->id == $programClaim->program_berjalan_id ? 'selected' : '' }}>
                                {{ $pb->program->nama_program }} - {{ $pb->customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Upload Bukti Klaim (Kosongkan jika tidak diganti)</label>
                    <input type="file" name="bukti_klaim" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-2">
                    <label>Customer</label>
                    <input type="text" id="customer" class="form-control" value="{{ $customer }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Nama Program</label>
                    <input type="text" id="nama_program" class="form-control" value="{{ $nama_program }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Jenis Program</label>
                    <input type="text" id="jenis_program" class="form-control" value="{{ $jenis_program }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Tipe Klaim</label>
                    <input type="text" id="tipe_klaim_text" class="form-control" value="{{ $tipe_klaim }}" readonly>
                </div>
            </div>
        </div>

        {{-- Tabel Outlet --}}
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Nama Outlet</th>
                        <th>Penjualan</th>
                        <th>Klaim Distributor</th>
                        <th>Klaim Sistem</th>
                        <th>Selisih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="claimTableBody">
                    @foreach ($programClaim->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><input type="text" name="outlets[{{ $index }}][nama]" class="form-control" value="{{ $detail->nama_outlet }}" required></td>
                            <td><input type="number" step="0.01" name="outlets[{{ $index }}][penjualan]" class="form-control penjualan-input" value="{{ $detail->penjualan }}" required></td>
                            <td><input type="number" step="0.01" name="outlets[{{ $index }}][klaim]" class="form-control klaim-distributor-input" value="{{ $detail->klaim_distributor }}" required></td>
                            <td><input type="text" class="form-control klaim-sistem-output" readonly value="{{ $detail->klaim_sistem }}"></td>
                            <td><input type="text" class="form-control selisih-output" readonly value="{{ $detail->selisih }}"></td>
                            <td><input type="text" name="outlets[{{ $index }}][keterangan]" class="form-control" value="{{ $detail->keterangan }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBaris()">+ Tambah Baris</button>
        </div>

        {{-- Total Pembelian --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <label>Total Pembelian</label>
                <input type="text" id="totalPembelian" name="total_pembelian" class="form-control text-end" value="{{ $programClaim->total_pembelian }}" readonly>
            </div>
        </div>

        {{-- Hidden fields --}}
        <input type="hidden" id="tipe_klaim" name="tipe_klaim" value="{{ $tipe_klaim }}">
        <input type="hidden" id="nilai_klaim" name="nilai_klaim" value="{{ $nilai_klaim }}">
        <input type="hidden" id="min_pembelian" name="min_pembelian" value="{{ $programClaim->programBerjalan->program->min_pembelian ?? 0 }}">

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('program-claims.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    let paramKlaim = parseFloat(document.getElementById('nilai_klaim').value) || 0;
    let minPembelian = parseFloat(document.getElementById('min_pembelian')?.value || 0);

    document.getElementById('programSelect').addEventListener('change', function () {
        const id = this.value;
        if (!id) return;

        fetch(`/program-claims/fetch/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('customer').value = data.customer;
                document.getElementById('nama_program').value = data.nama_program;
                document.getElementById('jenis_program').value = data.jenis_program;
                document.getElementById('tipe_klaim_text').value = data.tipe_klaim;
                document.getElementById('tipe_klaim').value = data.tipe_klaim;
                document.getElementById('nilai_klaim').value = data.reward;
                document.getElementById('min_pembelian').value = data.min_pembelian;

                paramKlaim = parseFloat(data.reward) || 0;
                minPembelian = parseFloat(data.min_pembelian) || 0;
                updateKlaim();
            });
    });

    document.addEventListener('input', function (e) {
        if (
            e.target.classList.contains('penjualan-input') ||
            e.target.classList.contains('klaim-distributor-input')
        ) {
            updateKlaim();
        }
    });

    function updateKlaim() {
        const tipe = document.getElementById('tipe_klaim').value;
        const nilai = parseFloat(document.getElementById('nilai_klaim').value) || 0;
        minPembelian = parseFloat(document.getElementById('min_pembelian').value) || 0;
        let total = 0;

        document.querySelectorAll('#claimTableBody tr').forEach((row) => {
            const penjualan = parseFloat(row.querySelector('.penjualan-input').value) || 0;
            const klaimDistributor = parseFloat(row.querySelector('.klaim-distributor-input').value) || 0;

            let klaimSistem = 0;

            if (tipe === 'rupiah') {
                klaimSistem = penjualan >= minPembelian ? nilai : 0;
            } else if (tipe === 'persen') {
                klaimSistem = penjualan * (nilai / 100);
            } else if (tipe === 'unit') {
                klaimSistem = minPembelian > 0 ? Math.floor(penjualan / minPembelian) * nilai : 0;
            }

            row.querySelector('.klaim-sistem-output').value = klaimSistem.toFixed(2);
            row.querySelector('.selisih-output').value = (klaimDistributor - klaimSistem).toFixed(2);
            total += penjualan;
        });

        document.getElementById('totalPembelian').value = total.toFixed(2);
    }

    function tambahBaris() {
        const tbody = document.getElementById('claimTableBody');
        const rowCount = tbody.rows.length;
        const row = `
        <tr>
            <td>${rowCount + 1}</td>
            <td><input type="text" name="outlets[${rowCount}][nama]" class="form-control" required></td>
            <td><input type="number" step="0.01" name="outlets[${rowCount}][penjualan]" class="form-control penjualan-input" required></td>
            <td><input type="number" step="0.01" name="outlets[${rowCount}][klaim]" class="form-control klaim-distributor-input" required></td>
            <td><input type="text" class="form-control klaim-sistem-output" readonly></td>
            <td><input type="text" class="form-control selisih-output" readonly></td>
            <td><input type="text" name="outlets[${rowCount}][keterangan]" class="form-control"></td>
        </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    }

    window.addEventListener('DOMContentLoaded', updateKlaim);
</script>

@endsection
