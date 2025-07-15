@extends('layouts.admin')

@section('page-title', 'Preview Klaim Program')

@section('content')
    {{-- Fungsi helper PHP untuk format angka, diletakkan di atas agar rapi --}}
    @php
        // Mengambil tipe reward dari program berjalan, bukan dari master program
        $tipe = $claim->programBerjalan->reward_type; 
        function formatKlaim($angka, $tipe) {
            if ($tipe === 'unit') {
                return number_format($angka, 0, ',', '.') . ' pcs';
            }
            // Untuk rupiah dan persen, kita format sebagai mata uang
            return 'Rp ' . number_format($angka, 0, ',', '.');
        }
    @endphp

<div class="max-w-5xl mx-auto">
    
    {{-- Kontainer Utama --}}
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">

        {{-- Header dengan Tombol Aksi (tidak akan dicetak) --}}
        <div class="no-print flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Detail Klaim</h2>
                <p class="text-sm text-slate-500">Kode Transaksi: <span class="font-mono">{{ $claim->kode_transaksi }}</span></p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50">
                    Kembali
                </a>
                <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-10V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path></svg>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>

        {{-- Area yang akan dicetak --}}
        <div id="printable-area">
            {{-- Header Khusus Cetak --}}
            <div class="header-print mb-6 hidden">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                    <div class="text-right">
                        <h3 class="font-bold text-lg">FORM KLAIM PROGRAM</h3>
                        <p class="text-sm">{{ $claim->kode_transaksi }}</p>
                    </div>
                </div>
            </div>

            {{-- Detail Informasi Klaim --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm mb-6">
                <div>
                    <p class="text-slate-500">Customer</p>
                    <p class="font-semibold text-slate-800">{{ $claim->programBerjalan->customer->nama_customer ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Program</p>
                    <p class="font-semibold text-slate-800">{{ $claim->programBerjalan->program->nama_program ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Tanggal Klaim</p>
                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Status</p>
                    <p class="font-semibold">
                        @if($claim->status === 'pending') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($claim->status === 'approved') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($claim->status === 'rejected') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Tabel Detail Outlet --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penjualan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Klaim Distributor</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Klaim Sistem</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($claim->details as $detail)
                            <tr>
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $detail->nama_outlet }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">Rp {{ number_format($detail->penjualan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">{{ formatKlaim($detail->klaim_distributor, $tipe) }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-800 font-semibold">{{ formatKlaim($detail->klaim_sistem, $tipe) }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">{{ formatKlaim($detail->selisih, $tipe) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-right text-sm text-gray-700">Total Keseluruhan</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($claim->total_pembelian, 0, ',', '.') }}</td>
                            <td></td>
                            <td colspan="2" class="px-6 py-3 text-right text-sm text-gray-700">
                                Klaim Sistem: {{ formatKlaim($claim->total_klaim, $tipe) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function printReport() {
        const printContent = document.getElementById('printable-area').innerHTML;
        const printHtml = `
            <html>
                <head>
                    <title>Preview Klaim - {{ $claim->kode_transaksi }}</title>
                    {{-- Kita gunakan link CDN Bootstrap untuk styling di halaman cetak --}}
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 1cm; font-family: 'Helvetica', 'Arial', sans-serif; }
                        .header-print { display: flex !important; justify-content: space-between; align-items: center; }
                        .hidden { display: flex !important; } /* Memaksa header cetak untuk tampil */
                        table { font-size: 10pt; }
                        th, td { padding: 6px; }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
            </html>`;
        
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        document.body.appendChild(iframe);
        
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(printHtml);
        doc.close();

        setTimeout(function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 500);
    }
</script>
@endpush
