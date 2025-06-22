<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\ProgramClaim;
use App\Models\ProgramClaimDetail;
use Illuminate\Http\Request;

class ProgramClaimController extends Controller
{
    public function index()
    {
        $programClaims = ProgramClaim::with('programBerjalan.program', 'programBerjalan.customer', 'details')->latest()->get();
        return view('program-claims.index', compact('programClaims'));
    }

    public function create()
    {
        $programs = ProgramBerjalan::with(['program', 'customer'])->get();
        $kodeTransaksi = $this->generateKodeTransaksi();
        return view('program-claims.create', compact('programs', 'kodeTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:program_claims,kode_transaksi',
            'tanggal_klaim' => 'required|date',
            'program_berjalan_id' => 'required|exists:program_berjalan,id',
            'total_pembelian' => 'required|numeric',
            'bukti_klaim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'outlets.*.nama' => 'required|string',
            'outlets.*.penjualan' => 'required|numeric',
            'outlets.*.klaim' => 'required|numeric',
        ]);

        $pb = ProgramBerjalan::with('program')->findOrFail($request->program_berjalan_id);
        $tipe = $pb->program->tipe_klaim;
        $minPembelian = floatval($pb->program->min_pembelian ?? 0);
        $reward = floatval($pb->program->reward ?? 0);

        $totalKlaim = 0;

        $klaim = new ProgramClaim();
        $klaim->kode_transaksi = $request->kode_transaksi;
        $klaim->tanggal_klaim = $request->tanggal_klaim;
        $klaim->program_berjalan_id = $request->program_berjalan_id;
        $klaim->total_pembelian = $request->total_pembelian;

        if ($request->hasFile('bukti_klaim')) {
            $klaim->bukti_klaim = $request->file('bukti_klaim')->store('bukti_klaim', 'public');
        }

        $klaim->save();

        foreach ($request->outlets as $data) {
            $penjualan = floatval($data['penjualan']);
            $klaimDistributor = floatval($data['klaim']);
            $klaimSistem = 0;

            // Hitung klaim sistem berdasarkan tipe
            if ($tipe === 'persen') {
                $klaimSistem = $penjualan * ($reward / 100);
            } elseif ($tipe === 'rupiah') {
                $klaimSistem = $penjualan >= $minPembelian ? $reward : 0;
            } elseif ($tipe === 'unit') {
                $kelipatan = $minPembelian > 0 ? floor($penjualan / $minPembelian) : 0;
                $klaimSistem = $kelipatan * $reward;
            }

            $totalKlaim += $klaimSistem;

            \Log::info('Hitung Klaim:', [
    'penjualan' => $penjualan,
    'minPembelian' => $minPembelian,
    'reward' => $reward,
    'tipe' => $tipe,
    'klaimSistem' => $klaimSistem,
]);


            ProgramClaimDetail::create([
    'program_claim_id' => $klaim->id, // atau $programClaim->id di update()
    'nama_outlet' => $data['nama'],
    'penjualan' => floatval($penjualan),
    'klaim_distributor' => floatval($klaimDistributor),
    'klaim_sistem' => floatval($klaimSistem),  // <== ini yang krusial
    'selisih' => floatval($klaimDistributor - $klaimSistem),
    'keterangan' => $data['keterangan'] ?? null,
]);


        }

        $klaim->total_klaim = $totalKlaim;
        $klaim->save();

        return redirect()->route('program-claims.index')->with('success', 'Klaim berhasil disimpan.');
    }

    public function fetchProgram($id)
    {
        $pb = ProgramBerjalan::with('program', 'customer')->findOrFail($id);

        return response()->json([
            'customer' => $pb->customer->nama_customer,
            'nama_program' => $pb->program->nama_program,
            'jenis_program' => $pb->program->jenis_program,
            'tipe_klaim' => $pb->program->tipe_klaim,
            'parameter_klaim' => $pb->program->parameter_klaim,
            'min_pembelian' => floatval($pb->program->min_pembelian ?? 0),
            'reward' => floatval($pb->program->reward ?? 0),
        ]);
    }

    private function generateKodeTransaksi()
    {
        $prefix = 'KLM' . date('Ymd');
        $last = ProgramClaim::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 3, '0', STR_PAD_LEFT);
    }

    public function edit($id)
{
    $programClaim = ProgramClaim::with('details', 'programBerjalan.program', 'programBerjalan.customer')->findOrFail($id);
    $programs = ProgramBerjalan::with('program', 'customer')->get();

    $program = $programClaim->programBerjalan->program;

    return view('program-claims.edit', [
        'programClaim' => $programClaim,
        'programs' => $programs,
        'customer' => $programClaim->programBerjalan->customer->nama_customer,
        'nama_program' => $program->nama_program,
        'jenis_program' => $program->jenis_program,
        'tipe_klaim' => $program->tipe_klaim,
        'nilai_klaim' => $program->reward,
    ]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'tanggal_klaim' => 'required|date',
        'program_berjalan_id' => 'required|exists:program_berjalan,id',
        'total_pembelian' => 'required|numeric',
        'bukti_klaim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'outlets.*.nama' => 'required|string',
        'outlets.*.penjualan' => 'required|numeric',
        'outlets.*.klaim' => 'required|numeric',
    ]);

    $pb = ProgramBerjalan::with('program')->findOrFail($request->program_berjalan_id);
    $tipe = $pb->program->tipe_klaim;
    $minPembelian = $pb->program->min_pembelian ?? 0;
    $reward = $pb->program->reward ?? 0;

    $programClaim = ProgramClaim::findOrFail($id);
    $programClaim->tanggal_klaim = $request->tanggal_klaim;
    $programClaim->program_berjalan_id = $request->program_berjalan_id;
    $programClaim->total_pembelian = $request->total_pembelian;

    if ($request->hasFile('bukti_klaim')) {
        $programClaim->bukti_klaim = $request->file('bukti_klaim')->store('bukti_klaim', 'public');
    }

    $programClaim->save();

    // Hapus detail lama
    ProgramClaimDetail::where('program_claim_id', $programClaim->id)->delete();

    // Simpan ulang detail
    $totalKlaim = 0;
    foreach ($request->outlets as $data) {
        $penjualan = $data['penjualan'];
        $klaimDistributor = $data['klaim'];
        $klaimSistem = 0;

        if ($tipe === 'persen') {
            $klaimSistem = $penjualan * ($reward / 100);
        } elseif ($tipe === 'rupiah') {
            $klaimSistem = $penjualan >= $minPembelian ? $reward : 0;
        } elseif ($tipe === 'unit') {
            if ($minPembelian > 0) {
                $kelipatan = floor($penjualan / $minPembelian);
                $klaimSistem = $kelipatan * $reward;
            }
        }

        $totalKlaim += $klaimSistem;

        ProgramClaimDetail::create([
    'program_claim_id' => $klaim->id, // atau $programClaim->id di update()
    'nama_outlet' => $data['nama'],
    'penjualan' => floatval($penjualan),
    'klaim_distributor' => floatval($klaimDistributor),
    'klaim_sistem' => floatval($klaimSistem),  // <== ini yang krusial
    'selisih' => floatval($klaimDistributor - $klaimSistem),
    'keterangan' => $data['keterangan'] ?? null,
]);
    }

    $programClaim->total_klaim = $totalKlaim;
    $programClaim->save();

    return redirect()->route('program-claims.index')->with('success', 'Klaim berhasil diperbarui.');
}
public function destroy($id)
{
    $claim = ProgramClaim::findOrFail($id);

    // Hapus detail terlebih dahulu
    ProgramClaimDetail::where('program_claim_id', $claim->id)->delete();

    // Hapus file bukti jika ada
    if ($claim->bukti_klaim && \Storage::disk('public')->exists($claim->bukti_klaim)) {
        \Storage::disk('public')->delete($claim->bukti_klaim);
    }

    $claim->delete();

    return redirect()->route('program-claims.index')->with('success', 'Data klaim berhasil dihapus.');
}
public function preview($id)
{
    $claim = ProgramClaim::with('details', 'programBerjalan.program', 'programBerjalan.customer')->findOrFail($id);

    return view('program-claims.preview', [
        'claim' => $claim,
    ]);
}


}
