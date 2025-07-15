@extends('layouts.admin')

@section('page-title', 'Laporan Penggunaan Budget')

@section('content')
    {{-- Kontainer Utama untuk Konten --}}
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
        
        {{-- Area ini TIDAK akan dicetak --}}
        <div class="no-print">
            {{-- Header Konten (Judul & Tombol) --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Laporan Penggunaan Budget</h2>
                    <p class="text-sm text-slate-500">Monitor alokasi, penggunaan, dan sisa budget marketing.</p>
                </div>
                <div class="mt-3 sm:mt-0 flex space-x-2">
                    <a href="{{ route('reports.budget.exportExcel', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.207 6.207a1 1 0 00-1.414 1.414L8.586 9H7a1 1 0 000 2h1.586l-1.293 1.293a1 1 0 101.414 1.414l2-2a1 1 0 000-1.414l-2-2zM13 9a1 1 0 100 2h-1a1 1 0 100-2h1z"/></svg>
                        Export Excel
                    </a>
                    <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-10V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path></svg>
                        Cetak
                    </button>
                </div>
            </div>

            {{-- Form Filter --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <form action="{{ route('reports.budget') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                        <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $request->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama_customer }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_anggaran" class="block text-sm font-medium text-gray-700">Tahun Anggaran</label>
                        <select name="tahun_anggaran" id="tahun_anggaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $request->tahun_anggaran == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Filter</button>
                        <a href="{{ route('reports.budget') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Area yang akan dicetak --}}
        <div id="printable-area">
            {{-- Header Khusus Cetak --}}
            <div class="header-print mb-6 hidden">
                <div class="flex justify-between items-center border-b pb-4">
                    <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                    <div class="text-right">
                        <h3 class="font-bold text-lg">Laporan Penggunaan Budget</h3>
                        <p class="text-sm text-gray-600">Tahun Anggaran: {{ $request->tahun_anggaran ?? 'Semua' }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabel Detail --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Budget Awal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Terpakai</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Budget</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $totalBudget = 0;
                            $totalTerpakai = 0;
                            $totalSisa = 0;
                        @endphp
                        @forelse ($reportData as $data)
                        @php
                            $totalBudget += $data->nilai_budget;
                            $totalTerpakai += $data->terpakai;
                            $totalSisa += $data->sisa_aktual;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $data->customer->nama_customer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $data->tahun_anggaran }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($data->nilai_budget, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">Rp {{ number_format($data->terpakai, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold text-right">Rp {{ number_format($data->sisa_aktual, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data budget untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
                    @if($reportData->count() > 0)
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-700 uppercase">Total</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($totalTerpakai, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-700">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Memindahkan script ke dalam section content untuk memastikan dieksekusi --}}
<script>
    function printReport() {
        const printContent = document.getElementById('printable-area').innerHTML;
        const originalContent = document.body.innerHTML;
        const printHtml = `
            <html>
                <head>
                    <title>Laporan Penggunaan Budget</title>
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
                        tfoot { background-color: #f1f5f9; }
                        .text-danger { color: #dc3545 !important; }
                        .text-success { color: #198754 !important; }
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
