@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-4">Absensi Harian</h2>

    <!-- Flash message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('absensi.store') }}">
        @csrf

        <!-- Input Tanggal -->
        <div class="flex items-center mb-6">
            <label for="tanggal" class="mr-4 font-semibold text-lg">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" class="border p-2 rounded shadow" required>
        </div>

        <!-- Tabel Absensi -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Check In (Waktu)</th>
                        <th class="px-4 py-2 border">Check In (Lokasi)</th>
                        <th class="px-4 py-2 border">Check Out (Waktu)</th>
                        <th class="px-4 py-2 border">Check Out (Lokasi)</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $index => $item)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border">{{ $item->nama_sales }}</td>
                            <td class="px-4 py-2 border">
                                <input type="time" name="data[{{ $index }}][check_in]" class="border rounded px-2 py-1 w-full">
                            </td>
                            <td class="px-4 py-2 border">
                                <input type="text" name="data[{{ $index }}][lokasi_in]" class="border rounded px-2 py-1 w-full" placeholder="Latitude, Longitude">
                            </td>
                            <td class="px-4 py-2 border">
                                <input type="time" name="data[{{ $index }}][check_out]" class="border rounded px-2 py-1 w-full">
                            </td>
                            <td class="px-4 py-2 border">
                                <input type="text" name="data[{{ $index }}][lokasi_out]" class="border rounded px-2 py-1 w-full" placeholder="Latitude, Longitude">
                            </td>
                            <td class="px-4 py-2 border">
                                <select name="data[{{ $index }}][presensi]" class="border rounded px-2 py-1 w-full">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alfa">Alfa</option>
                                </select>
                            </td>
                            <td class="px-4 py-2 border">
                                <input type="text" name="data[{{ $index }}][keterangan]" class="border rounded px-2 py-1 w-full" placeholder="Opsional">
                            </td>
                            <input type="hidden" name="data[{{ $index }}][sales_marketing_id]" value="{{ $item->id }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                Simpan Absensi
            </button>
        </div>
    </form>

    @if($absensiList->count())
    <div class="mt-10">
        <h3 class="text-xl font-semibold mb-3">Data Absensi Tersimpan</h3>
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensiList as $absensi)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $absensi->salesMarketing->nama_sales ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $absensi->tanggal }}</td>
                        <td class="px-4 py-2 border">{{ $absensi->status }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('absensi.edit', $absensi->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

</div>

<a href="{{ route('absensi.edit', $absensi->id) }}" class="btn btn-sm btn-warning">Edit</a>

@endsection
