@extends('layouts.admin')

@section('page-title', 'Laporan Absensi')

@section('content')
    {{-- Kontainer Utama untuk Konten --}}
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
        
        {{-- Area ini TIDAK akan dicetak --}}
        <div class="no-print">
            {{-- Header Konten (Judul & Tombol) --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Laporan Absensi</h2>
                    <p class="text-sm text-slate-500">Gunakan filter di bawah untuk melihat data absensi.</p>
                </div>
                <div class="mt-3 sm:mt-0 flex space-x-2">
                    <a href="{{ route('reports.absensi.exportExcel', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
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
                <form action="{{ route('reports.absensi') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $request->start_date }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $request->end_date }}">
                    </div>
                    <div>
                        <label for="sales_id" class="block text-sm font-medium text-gray-700">Pilih Sales</label>
                        <select name="sales_id" id="sales_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}" {{ $request->sales_id == $sale->id ? 'selected' : '' }}>{{ $sale->nama_sales }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Filter</button>
                        <a href="{{ route('reports.absensi') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Reset</a>
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
                        <h3 class="font-bold text-lg">Laporan Absensi</h3>
                        <p class="text-sm text-gray-600">
                            Periode:
                            @if($request->start_date && $request->end_date)
                                {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                            @else
                                Semua Data
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Rekapitulasi --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-3">Rekapitulasi Absensi</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                    <div class="p-4 bg-green-50 rounded-lg"><p class="text-2xl font-bold text-green-600">{{ $rekapitulasi['Hadir'] }}</p><p class="text-sm text-green-800">Hadir</p></div>
                    <div class="p-4 bg-sky-50 rounded-lg"><p class="text-2xl font-bold text-sky-600">{{ $rekapitulasi['Izin'] }}</p><p class="text-sm text-sky-800">Izin</p></div>
                    <div class="p-4 bg-amber-50 rounded-lg"><p class="text-2xl font-bold text-amber-600">{{ $rekapitulasi['Sakit'] }}</p><p class="text-sm text-amber-800">Sakit</p></div>
                    <div class="p-4 bg-red-50 rounded-lg"><p class="text-2xl font-bold text-red-600">{{ $rekapitulasi['Alfa'] }}</p><p class="text-sm text-red-800">Alfa</p></div>
                    <div class="p-4 bg-slate-100 rounded-lg col-span-2 md:col-span-1"><p class="text-2xl font-bold text-slate-700">{{ $rekapitulasi['Total'] }}</p><p class="text-sm text-slate-800">Total Data</p></div>
                </div>
            </div>

            {{-- Tabel Detail --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sales</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($absensiList as $absensi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $absensi->salesMarketing->nama_sales ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($absensi->status == 'Hadir')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                    @elseif($absensi->status == 'Izin')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-sky-100 text-sky-800">Izin</span>
                                    @elseif($absensi->status == 'Sakit')<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Sakit</span>
                                    @else<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alfa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $absensi->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
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
        
        // Buat kerangka HTML baru yang bersih untuk dicetak
        // Kita tidak akan menyertakan link CSS Bootstrap, tapi akan meniru gayanya
        const printHtml = `
            <html>
                <head>
                    <title>Laporan Absensi</title>
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
                        /* Tambahan untuk styling tabel agar mirip Bootstrap */
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
