@extends('layouts.admin')

@section('page-title', 'Edit Klaim Program')

@section('content')
<div class="max-w-7xl mx-auto">
    <form action="{{ route('program-claims.update', $programClaim->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="bg-white p-8 rounded-lg shadow-lg">
            
            {{-- Informasi Umum --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kode Transaksi</label>
                        <input type="text" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300" value="{{ $programClaim->kode_transaksi }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tanggal Klaim</label>
                        <input type="date" name="tanggal_klaim" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm" value="{{ $programClaim->tanggal_klaim }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Program Berjalan</label>
                        <select name="program_berjalan_id" id="programSelect" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm" required>
                            @foreach($programs as $pb)
                                <option value="{{ $pb->id }}" {{ $pb->id == $programClaim->program_berjalan_id ? 'selected' : '' }}>
                                    {{ $pb->program->nama_program }} - {{ $pb->customer->nama_customer }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-slate-700">Upload Bukti Klaim (Kosongkan jika tidak diganti)</label>
                        <input type="file" name="bukti_klaim" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($programClaim->bukti_klaim)
                            <a href="{{ asset('storage/' . $programClaim->bukti_klaim) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat file saat ini</a>
                        @endif
                    </div>
                </div>
                {{-- Kolom Kanan (Read-only) --}}
                <div class="space-y-6">
                    <div><label class="block text-sm font-medium text-slate-700">Customer</label><input type="text" id="customer" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300" readonly></div>
                    <div><label class="block text-sm font-medium text-slate-700">Nama Program</label><input type="text" id="nama_program" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300" readonly></div>
                    <div><label class="block text-sm font-medium text-slate-700">Jenis Program</label><input type="text" id="jenis_program" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300" readonly></div>
                    <div><label class="block text-sm font-medium text-slate-700">Tipe Klaim</label><input type="text" id="tipe_klaim_text" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300" readonly></div>
                </div>
            </div>

            {{-- Tabel Klaim Outlet --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 mt-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Outlet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penjualan (Rp)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klaim Distributor (Rp)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klaim Sistem (Rp)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih (Rp)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="claimTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach ($programClaim->details as $index => $detail)
                        <tr>
                            <td class="px-2 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="px-2 py-2"><input type="text" name="outlets[{{$index}}][nama]" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $detail->nama_outlet }}" required></td>
                            <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm penjualan-input currency-input" value="{{ number_format($detail->penjualan, 0, ',', '.') }}" required></td>
                            <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm klaim-distributor-input currency-input" value="{{ number_format($detail->klaim_distributor, 0, ',', '.') }}" required></td>
                            <input type="hidden" name="outlets[{{$index}}][penjualan]" value="{{ $detail->penjualan }}">
                            <input type="hidden" name="outlets[{{$index}}][klaim]" value="{{ $detail->klaim_distributor }}">
                            <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm bg-slate-100 klaim-sistem-output" readonly></td>
                            <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm bg-slate-100 selisih-output" readonly></td>
                            <td class="px-2 py-2"><input type="text" name="outlets[{{$index}}][keterangan]" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $detail->keterangan }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-semibold" onclick="tambahBaris()">+ Tambah Baris Outlet</button>

            {{-- Total Pembelian & Klaim Sistem --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Total Pembelian</label>
                    <input type="text" id="totalPembelian" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300 text-right font-bold" readonly>
                    <input type="hidden" name="total_pembelian" id="totalPembelianRaw">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Total Klaim Sistem</label>
                    <input type="text" id="totalKlaimSistem" class="mt-1 block w-full bg-slate-100 rounded-md border-slate-300 text-right font-bold" readonly>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('program-claims.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Update Klaim</button>
            </div>
        </div>
    </form>
</div>

{{-- Memindahkan script ke dalam section content --}}
<script>
    // ... (Salin seluruh blok <script> dari halaman create.blade.php yang sudah berfungsi) ...
    function formatNumber(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
    function unformatNumber(s) { return s.toString().replace(/\./g, ""); }

    document.addEventListener('DOMContentLoaded', function() {
        let aturanProgram = {};

        const programSelect = document.getElementById('programSelect');
        
        function fetchAndCalculate(programId) {
            if (!programId) { return; }
            fetch(`/program-claims/fetch/${programId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('customer').value = data.customer;
                    document.getElementById('nama_program').value = data.nama_program;
                    document.getElementById('jenis_program').value = data.jenis_program;
                    document.getElementById('tipe_klaim_text').value = data.tipe_klaim;
                    aturanProgram = {
                        reward: parseFloat(data.reward) || 0,
                        minPembelian: parseFloat(data.min_pembelian) || 0,
                        tipeReward: data.reward_type || ''
                    };
                    updateSemuaBaris();
                });
        }

        programSelect.addEventListener('change', () => fetchAndCalculate(programSelect.value));
        
        // --- PERBAIKAN UTAMA: Jalankan kalkulasi saat halaman dimuat ---
        fetchAndCalculate(programSelect.value);

        function hitungSatuBaris(row) {
            const penjualanInput = row.querySelector('.penjualan-input');
            const klaimDistributorInput = row.querySelector('.klaim-distributor-input');
            const klaimSistemOutput = row.querySelector('.klaim-sistem-output');
            const selisihOutput = row.querySelector('.selisih-output');

            const penjualan = parseFloat(unformatNumber(penjualanInput.value)) || 0;
            const klaimDistributor = parseFloat(unformatNumber(klaimDistributorInput.value)) || 0;
            
            row.querySelector('input[name*="[penjualan]"]').value = penjualan;
            row.querySelector('input[name*="[klaim]"]').value = klaimDistributor;

            let klaimSistem = 0;
            if (penjualan >= aturanProgram.minPembelian) {
                if (aturanProgram.tipeReward === 'persen') klaimSistem = penjualan * (aturanProgram.reward / 100);
                else if (aturanProgram.tipeReward === 'rupiah') klaimSistem = aturanProgram.reward;
                else if (aturanProgram.tipeReward === 'unit' && aturanProgram.minPembelian > 0) {
                    klaimSistem = Math.floor(penjualan / aturanProgram.minPembelian) * aturanProgram.reward;
                }
            }
            klaimSistemOutput.value = formatNumber(klaimSistem.toFixed(0));
            selisihOutput.value = formatNumber((klaimDistributor - klaimSistem).toFixed(0));
            return { penjualan, klaimSistem };
        }

        function updateSemuaBaris() {
            let totalPembelian = 0, totalKlaimSistem = 0;
            document.querySelectorAll('#claimTableBody tr').forEach(row => {
                const hasil = hitungSatuBaris(row);
                totalPembelian += hasil.penjualan;
                totalKlaimSistem += hasil.klaimSistem;
            });
            document.getElementById('totalPembelian').value = formatNumber(totalPembelian.toFixed(0));
            document.getElementById('totalKlaimSistem').value = formatNumber(totalKlaimSistem.toFixed(0));
            document.getElementById('totalPembelianRaw').value = totalPembelian;
        }

        document.addEventListener('input', e => {
            if (e.target && e.target.classList.contains('currency-input')) {
                const value = unformatNumber(e.target.value);
                e.target.value = formatNumber(value);
                updateSemuaBaris();
            }
        });

        window.tambahBaris = function() {
            const tbody = document.getElementById('claimTableBody');
            const rowCount = tbody.rows.length;
            const newRow = `<tr>
                <td class="px-2 py-2 text-center">${rowCount + 1}</td>
                <td class="px-2 py-2"><input type="text" name="outlets[${rowCount}][nama]" class="w-full border-gray-300 rounded-md shadow-sm" required></td>
                <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm penjualan-input currency-input" required></td>
                <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm klaim-distributor-input currency-input" required></td>
                <input type="hidden" name="outlets[${rowCount}][penjualan]"><input type="hidden" name="outlets[${rowCount}][klaim]">
                <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm bg-slate-100 klaim-sistem-output" readonly></td>
                <td class="px-2 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm bg-slate-100 selisih-output" readonly></td>
                <td class="px-2 py-2"><input type="text" name="outlets[${rowCount}][keterangan]" class="w-full border-gray-300 rounded-md shadow-sm"></td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', newRow);
        }
    });
</script>
@endsection
