@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-bold text-primary mb-4">Form Klaim Program</h4>

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
                    <label>Program Berjalan</label>
                    <select name="program_berjalan_id" id="programSelect" class="form-select" required>
                        <option value="">-- Pilih Program --</option>
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
                    <label>Tipe Klaim</label>
                    <input type="text" id="tipe_klaim_text" class="form-control" readonly>
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
                        <th>Klaim Distributor</th>
                        <th>Klaim Sistem</th>
                        <th>Selisih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="claimTableBody">
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="outlets[0][nama]" class="form-control" required></td>
                        <td><input type="number" step="0.01" name="outlets[0][penjualan]" class="form-control penjualan-input" required></td>
                        <td><input type="number" step="0.01" name="outlets[0][klaim]" class="form-control klaim-distributor-input" required></td>
                        <td><input type="text" class="form-control klaim-sistem-output" readonly></td>
                        <td><input type="text" class="form-control selisih-output" readonly></td>
                        <td><input type="text" name="outlets[0][keterangan]" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBaris()">+ Tambah Baris</button>
        </div>

        {{-- Total Pembelian & Klaim Sistem --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <label>Total Pembelian</label>
                <input type="text" id="totalPembelian" name="total_pembelian" class="form-control text-end" readonly>
            </div>
            <div class="col-md-6">
                <label>Total Klaim Sistem</label>
                <input type="text" id="totalKlaimSistem" class="form-control text-end" readonly>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('program-claims.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

{{-- Script yang sudah diperbaiki --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variabel global untuk menyimpan aturan program yang dipilih
        let aturanProgram = {
            reward: 0,
            minPembelian: 0,
            tipeReward: ''
        };

        // Event listener ketika dropdown 'Program Berjalan' berubah
        const programSelect = document.getElementById('programSelect');
        if (programSelect) {
            programSelect.addEventListener('change', function () {
                const programBerjalanId = this.value;
                if (!programBerjalanId) {
                    resetForm();
                    return;
                }

                fetch(`/program-claims/fetch/${programBerjalanId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Isi field informasi umum
                        document.getElementById('customer').value = data.customer;
                        document.getElementById('nama_program').value = data.nama_program;
                        document.getElementById('jenis_program').value = data.jenis_program;
                        document.getElementById('tipe_klaim_text').value = data.tipe_klaim;
                        
                        // Simpan aturan program yang didapat dari API ke variabel global
                        aturanProgram.reward = parseFloat(data.reward) || 0;
                        aturanProgram.minPembelian = parseFloat(data.min_pembelian) || 0;
                        aturanProgram.tipeReward = data.reward_type || '';

                        // Panggil fungsi update untuk menghitung ulang semua baris
                        updateSemuaBaris();
                    })
                    .catch(error => {
                        console.error("Gagal mengambil data program:", error);
                        resetForm();
                    });
            });
        }

        // Fungsi untuk menghitung ulang nilai pada satu baris
        function hitungSatuBaris(row) {
            const penjualanInput = row.querySelector('.penjualan-input');
            const klaimDistributorInput = row.querySelector('.klaim-distributor-input');
            const klaimSistemOutput = row.querySelector('.klaim-sistem-output');
            const selisihOutput = row.querySelector('.selisih-output');

            const penjualan = parseFloat(penjualanInput.value) || 0;
            const klaimDistributor = parseFloat(klaimDistributorInput.value) || 0;
            
            let klaimSistem = 0;

            // Cek jika syarat minimal pembelian terpenuhi
            const syaratTerpenuhi = penjualan >= aturanProgram.minPembelian;

            if (syaratTerpenuhi) {
                // Jika syarat terpenuhi, hitung berdasarkan tipe reward
                if (aturanProgram.tipeReward === 'persen') {
                    klaimSistem = penjualan * (aturanProgram.reward / 100);
                } else if (aturanProgram.tipeReward === 'rupiah') {
                    klaimSistem = aturanProgram.reward;
                } else if (aturanProgram.tipeReward === 'unit') {
                    if (aturanProgram.minPembelian > 0) {
                        const kelipatan = Math.floor(penjualan / aturanProgram.minPembelian);
                        klaimSistem = kelipatan * aturanProgram.reward;
                    }
                }
            }
            // Jika syarat tidak terpenuhi, 'klaimSistem' akan tetap 0

            klaimSistemOutput.value = klaimSistem.toFixed(2);
            selisihOutput.value = (klaimDistributor - klaimSistem).toFixed(2);
            
            return { penjualan, klaimSistem };
        }

        // Fungsi untuk mengupdate semua baris dan total
        function updateSemuaBaris() {
            let totalPembelian = 0;
            let totalKlaimSistem = 0;

            document.querySelectorAll('#claimTableBody tr').forEach(row => {
                const hasil = hitungSatuBaris(row);
                totalPembelian += hasil.penjualan;
                totalKlaimSistem += hasil.klaimSistem;
            });

            document.getElementById('totalPembelian').value = totalPembelian.toFixed(2);
            const totalKlaimSistemEl = document.getElementById('totalKlaimSistem');
            if(totalKlaimSistemEl) {
                 totalKlaimSistemEl.value = totalKlaimSistem.toFixed(2);
            }
        }

        // Event listener untuk input di dalam tabel
        document.addEventListener('input', function (e) {
            if (e.target && e.target.closest('#claimTableBody')) {
                updateSemuaBaris();
            }
        });

        // Fungsi untuk menambah baris baru
        window.tambahBaris = function() {
            const tbody = document.getElementById('claimTableBody');
            const rowCount = tbody.rows.length;
            const newRow = `
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
            tbody.insertAdjacentHTML('beforeend', newRow);
        }
        
        // Fungsi untuk mereset form
        function resetForm() {
            document.getElementById('customer').value = '';
            document.getElementById('nama_program').value = '';
            document.getElementById('jenis_program').value = '';
            document.getElementById('tipe_klaim_text').value = '';
            aturanProgram = { reward: 0, minPembelian: 0, tipeReward: '' };
            updateSemuaBaris();
        }
    });
</script>
@endsection