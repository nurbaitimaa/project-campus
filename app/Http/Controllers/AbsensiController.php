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

    foreach ($data as $index => $item) {
        $fotoPath = null;

        if (isset($item['foto']) && $item['foto'] instanceof \Illuminate\Http\UploadedFile) {
            $fotoPath = $item['foto']->store('absensi', 'public');
        }

        Absensi::create([
            'tanggal'            => $tanggal,
            'sales_marketing_id' => $item['sales_marketing_id'],
            'jam_masuk'          => $item['check_in'] ?? null,
            'jam_keluar'         => $item['check_out'] ?? null,
            'status'             => $item['presensi'],
            'keterangan'         => $item['keterangan'] ?? null,
            'foto'               => $fotoPath,
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
        'status'     => 'required|in:Hadir,Izin,Alfa,Sakit',
        'keterangan' => 'nullable|string',
        'foto'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Handle upload foto baru (jika ada)
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($absensi->foto && \Storage::disk('public')->exists($absensi->foto)) {
            \Storage::disk('public')->delete($absensi->foto);
        }

        // Simpan foto baru
        $absensi->foto = $request->file('foto')->store('absensi', 'public');
    }

    $absensi->update([
        'jam_masuk'  => $request->jam_masuk,
        'jam_keluar' => $request->jam_keluar,
        'status'     => $request->status,
        'keterangan' => $request->keterangan,
        'foto'       => $absensi->foto,
        'updated_by' => auth()->user()->id,
    ]);

    return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
}

}
