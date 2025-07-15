@extends('layouts.admin')

@section('page-title', 'Laporan Klaim Program')

@section('content')
<div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">

    {{-- Bagian yang tidak dicetak --}}
    <div class="no-print">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Laporan Klaim Program</h2>
                <p class="text-sm text-slate-500">Analisis data klaim berdasarkan periode, customer, program, dan status.</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ route('reports.klaim.exportExcel', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    Export Excel
                </a>
                <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800">
                    Cetak
                </button>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('reports.klaim') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tgl Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $request->start_date }}">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tgl Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $request->end_date }}">
                </div>
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->kode_customer }}" {{ $request->customer_id == $customer->kode_customer ? 'selected' : '' }}>{{ $customer->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program</label>
                    <select name="program_id" id="program_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->kode_program }}" {{ $request->program_id == $program->kode_program ? 'selected' : '' }}>{{ $program->nama_program }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $request->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1 md:col-span-5 flex justify-end space-x-2">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Filter</button>
                    <a href="{{ route('reports.klaim') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Reset</a>
                </div>
            </form>
        </div>
    </div>
 <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-700 mb-3">Rekapitulasi Klaim</h3>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-center">
                <div class="p-4 bg-blue-50 rounded-lg"><p class="text-xs text-blue-800">Total Penjualan</p><p class="text-xl font-bold text-blue-600">Rp {{ number_format($rekapitulasi['total_penjualan'], 0, ',', '.') }}</p></div>
                <div class="p-4 bg-purple-50 rounded-lg"><p class="text-xs text-purple-800">Total Nilai Klaim</p><p class="text-xl font-bold text-purple-600">Rp {{ number_format($rekapitulasi['total_klaim'], 0, ',', '.') }}</p></div>
                <div class="p-4 bg-amber-50 rounded-lg"><p class="text-xs text-amber-800">Pending</p><p class="text-2xl font-bold text-amber-600">{{ $rekapitulasi['pending'] }}</p></div>
                <div class="p-4 bg-green-50 rounded-lg"><p class="text-xs text-green-800">Approved</p><p class="text-2xl font-bold text-green-600">{{ $rekapitulasi['approved'] }}</p></div>
                <div class="p-4 bg-red-50 rounded-lg"><p class="text-xs text-red-800">Rejected</p><p class="text-2xl font-bold text-red-600">{{ $rekapitulasi['rejected'] }}</p></div>
                <div class="p-4 bg-slate-100 rounded-lg"><p class="text-xs text-slate-800">Total Klaim</p><p class="text-2xl font-bold text-slate-700">{{ $rekapitulasi['total_data'] }}</p></div>
            </div>
        </div> 

    {{-- Bagian yang akan dicetak --}}
    <div id="printable-area">
        <div class="header-print mb-6 hidden">
            <div class="flex justify-between items-center border-b pb-4">
                <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                <div class="text-right">
                    <h3 class="font-bold text-lg">Laporan Klaim Program</h3>
                    <p class="text-sm text-gray-600">Periode: {{ $request->start_date ? \Carbon\Carbon::parse($request->start_date)->format('d M Y') : 'Semua' }} – {{ $request->end_date ? \Carbon\Carbon::parse($request->end_date)->format('d M Y') : 'Data' }}</p>
                </div>
            </div>
        </div>

        

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Klaim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Klaim</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($claims as $claim)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-semibold text-gray-900">{{ $claim->kode_transaksi }}</div><div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d M Y') }}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-800">{{ $claim->programBerjalan->program->nama_program ?? '-' }}</div><div class="text-xs text-gray-500">{{ $claim->programBerjalan->customer->nama_customer ?? '-' }}</div></td>
                        <td class="px-6 py-4 text-right text-sm text-gray-500">Rp {{ number_format($claim->total_pembelian, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-800">Rp {{ number_format($claim->total_klaim, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center text-sm">
                            @if($claim->status == 'approved')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @elseif($claim->status == 'pending')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @else<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data klaim untuk filter yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script cetak --}}
<script>
function printReport() {
    const printContent = document.getElementById('printable-area').innerHTML;
    const originalContent = document.body.innerHTML;
    const printHtml = `
        <html>
            <head>
                <title>Laporan Klaim Program</title>
                <style>
                    body { margin: 1.5cm; font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; }
                    .header-print { display: flex !important; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; padding-bottom: 1rem; margin-bottom: 1.5rem; }
                    .header-print img { height: 50px; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .text-lg { font-size: 1.125rem; }
                    .text-sm { font-size: 0.875rem; }
                    .text-gray-600 { color: #4b5563; }
                    .hidden { display: flex !important; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #dee2e6; padding: 8px; }
                    thead { background-color: #f8f9fa; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>`;
    
    document.body.innerHTML = printHtml;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>
@endsection
