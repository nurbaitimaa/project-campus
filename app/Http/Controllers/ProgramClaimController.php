<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\ProgramClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramClaimController extends Controller
{
    public function index()
{
    $programClaims = ProgramClaim::with('programBerjalan.program', 'programBerjalan.customer')->get();

    return view('program-claims.index', compact('programClaims'));
}

    
    // Halaman Form Create
    public function create()
    {
        // Ambil data program_berjalan lengkap dengan relasi
        $programs = ProgramBerjalan::with(['program', 'customer'])->get();

        // Generate kode transaksi otomatis
        $kodeTransaksi = $this->generateKodeTransaksi();

        return view('program-claims.create', compact('programs', 'kodeTransaksi'));
    }

    // Simpan klaim baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:program_claims,kode_transaksi',
            'tanggal_klaim' => 'required|date',
            'program_berjalan_id' => 'required|exists:program_berjalan,id',
            'total_pembelian' => 'required|numeric',
            'jumlah_unit' => 'nullable|integer',
            'bukti_klaim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $klaim = new ProgramClaim();
        $klaim->kode_transaksi = $request->kode_transaksi;
        $klaim->tanggal_klaim = $request->tanggal_klaim;
        $klaim->program_berjalan_id = $request->program_berjalan_id;
        $klaim->total_pembelian = $request->total_pembelian;
        $klaim->jumlah_unit = $request->jumlah_unit;
        
        // TODO: Perhitungan total_klaim otomatis nanti di langkah berikutnya
        $klaim->total_klaim = 0;

        // Simpan file bukti
        if ($request->hasFile('bukti_klaim')) {
            $path = $request->file('bukti_klaim')->store('bukti_klaim', 'public');
            $klaim->bukti_klaim = $path;
        }

        $klaim->save();

        return redirect()->route('program-claims.index')->with('success', 'Klaim berhasil disimpan.');
    }

    // Fungsi bantu untuk generate kode transaksi unik
    private function generateKodeTransaksi()
    {
        $prefix = 'KLM' . date('Ymd');
        $last = ProgramClaim::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 3, '0', STR_PAD_LEFT); // Contoh: KLM20250521001
    }

   public function fetchProgram($id)
{
    $pb = ProgramBerjalan::with(['program', 'customer'])->findOrFail($id);

    return response()->json([
        // flat keys agar JS lebih mudah
        'customer'      => $pb->customer->nama_customer,
        'nama_program'  => $pb->program->nama_program,
        'jenis_program' => $pb->program->jenis_program,
        'tipe_klaim'    => $pb->program->tipe_klaim,      // "unit" atau "rupiah"
        'nilai_klaim'   => $pb->program->nilai_klaim,     // numeric
        'parameter_klaim'  => $pb->program->parameter_klaim,
    ]);
}


}
