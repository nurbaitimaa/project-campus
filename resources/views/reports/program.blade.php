@extends('layouts.admin')

@section('page-title', 'Laporan Program Berjalan')

@section('content')
<div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
    {{-- Area Tidak Dicetak --}}
    <div class="no-print">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Laporan Program Berjalan</h2>
                <p class="text-sm text-slate-500">Melihat daftar semua program yang diimplementasikan.</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ route('reports.program.exportExcel', request()->query()) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-xs font-semibold uppercase">
                    Export Excel
                </a>
                <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-md hover:bg-slate-800 text-xs font-semibold uppercase">
                    Cetak
                </button>
            </div>
        </div>

        {{-- Form Filter --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('reports.program') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Periode Mulai</label>
                    <input type="date" name="start_date" id="start_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ $request->start_date }}">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Periode Selesai</label>
                    <input type="date" name="end_date" id="end_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        value="{{ $request->end_date }}">
                </div>
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->kode_customer }}" {{ $request->customer_id == $customer->kode_customer ? 'selected' : '' }}>
                                {{ $customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program</label>
                    <select name="program_id" id="program_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->kode_program }}" {{ $request->program_id == $program->kode_program ? 'selected' : '' }}>
                                {{ $program->nama_program }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4 flex justify-end space-x-2">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
                    <a href="{{ route('reports.program') }}" class="inline-flex justify-center py-2 px-4 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Area Dicetak --}}
    <div id="printable-area">
        <div class="header-print mb-6 hidden">
            <div class="flex justify-between items-center border-b pb-4">
                <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" style="height: 50px;">
                <div class="text-right">
                    <h3 class="font-bold text-lg">Laporan Program Berjalan</h3>
                    <p class="text-sm text-gray-600">
                        Periode: {{ $request->start_date ? \Carbon\Carbon::parse($request->start_date)->format('d M Y') : 'Semua' }}
                        &mdash;
                        {{ $request->end_date ? \Carbon\Carbon::parse($request->end_date)->format('d M Y') : 'Data' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aturan Reward</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($programBerjalan as $program)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $program->customer->nama_customer ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $program->program->nama_program ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                Min: {{ number_format($program->min_pembelian, 0, ',', '.') }} | 
                                Reward: {{ number_format($program->reward, 0, ',', '.') }} ({{ $program->reward_type }})
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data program berjalan untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function printReport() {
        const printContent = document.getElementById('printable-area').innerHTML;
        const originalContent = document.body.innerHTML;

        const printHtml = `
            <html>
                <head>
                    <title>Laporan Program Berjalan</title>
                    <style>
                        body { margin: 1.5cm; font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; }
                        .header-print { display: flex !important; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; padding-bottom: 1rem; margin-bottom: 1.5rem; }
                        .text-right { text-align: right; }
                        .font-bold { font-weight: bold; }
                        .text-lg { font-size: 1.125rem; }
                        .text-sm { font-size: 0.875rem; }
                        .text-gray-600 { color: #4b5563; }
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
