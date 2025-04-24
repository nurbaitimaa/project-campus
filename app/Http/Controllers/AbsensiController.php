<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\SalesMarketing;

class AbsensiController extends Controller
{
    public function index()
{
    $sales = SalesMarketing::all();
    $absensiList = Absensi::with('salesMarketing')->latest()->get(); // ambil data absensi + relasi nama sales
    return view('absensi.index', compact('sales', 'absensiList'));
}


    public function store(Request $request)
    {
        $tanggal = $request->tanggal;
        $data = $request->data;

        foreach ($data as $item) {
            Absensi::create([
                'tanggal'            => $tanggal,
                'sales_marketing_id' => $item['sales_marketing_id'],
                'jam_masuk'          => $item['check_in'] ?? null,
                'latitude'           => $item['lokasi_in'] ?? null,
                'jam_keluar'         => $item['check_out'] ?? null,
                'longitude'          => $item['lokasi_out'] ?? null,
                'status'             => $item['presensi'],
                'keterangan'         => $item['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan!');
    }

    public function edit(Absensi $absensi)
    {
        return view('absensi.edit', compact('absensi'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'latitude'   => 'nullable|string',
            'longitude'  => 'nullable|string',
            'status'     => 'required|in:Hadir,Izin,Alfa',
            'keterangan' => 'nullable|string',
        ]);

        $absensi->update([
            'jam_masuk'  => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'status'     => $request->status,
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }
}
