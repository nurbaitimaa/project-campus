<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\ProgramClaim;
use App\Models\ProgramClaimDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProgramClaimController extends Controller
{
    public function index()
    {
        $programClaims = ProgramClaim::with('programBerjalan.program', 'programBerjalan.customer', 'details')
            ->latest()->get();

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

        $pb = ProgramBerjalan::findOrFail($request->program_berjalan_id);
        $tipeReward = $pb->reward_type;
        $minPembelian = floatval($pb->min_pembelian ?? 0);
        $reward = floatval($pb->reward ?? 0);

        $klaim = new ProgramClaim();
        $klaim->kode_transaksi = $request->kode_transaksi;
        $klaim->tanggal_klaim = $request->tanggal_klaim;
        $klaim->program_berjalan_id = $request->program_berjalan_id;
        $klaim->total_pembelian = $request->total_pembelian;
        $klaim->status = 'pending';

        if ($request->hasFile('bukti_klaim')) {
            $klaim->bukti_klaim = $request->file('bukti_klaim')->store('bukti_klaim', 'public');
        }
        $klaim->save();

        $totalKlaimSistem = 0;

        foreach ($request->outlets as $data) {
            $penjualan = floatval($data['penjualan']);
            $klaimDistributor = floatval($data['klaim']);
            $klaimSistem = 0;
            $syaratTerpenuhi = $penjualan >= $minPembelian;

            if ($syaratTerpenuhi) {
                if ($tipeReward === 'persen') {
                    $klaimSistem = $penjualan * ($reward / 100);
                } elseif ($tipeReward === 'rupiah') {
                    $klaimSistem = $reward;
                } elseif ($tipeReward === 'unit') {
                    $kelipatan = $minPembelian > 0 ? floor($penjualan / $minPembelian) : 0;
                    $klaimSistem = $kelipatan * $reward;
                }
            }

            $totalKlaimSistem += $klaimSistem;

            ProgramClaimDetail::create([
                'program_claim_id' => $klaim->id,
                'nama_outlet' => $data['nama'],
                'penjualan' => $penjualan,
                'klaim_distributor' => $klaimDistributor,
                'klaim_sistem' => $klaimSistem,
                'selisih' => $klaimDistributor - $klaimSistem,
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        }

        $klaim->total_klaim = $totalKlaimSistem;
        $klaim->save();

        return redirect()->route('program-claims.index')->with('success', 'Klaim berhasil disimpan.');
    }

    public function edit($id)
    {
        $programClaim = ProgramClaim::with('details', 'programBerjalan.program', 'programBerjalan.customer')->findOrFail($id);
        $programs = ProgramBerjalan::with('program', 'customer')->get();

        return view('program-claims.edit', [
            'programClaim' => $programClaim,
            'programs' => $programs,
            'customer' => $programClaim->programBerjalan->customer->nama_customer,
            'nama_program' => $programClaim->programBerjalan->program->nama_program,
            'jenis_program' => $programClaim->programBerjalan->program->jenis_program,
            'tipe_klaim' => $programClaim->programBerjalan->program->tipe_klaim,
            'nilai_klaim' => $programClaim->programBerjalan->reward,
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

        $pb = ProgramBerjalan::findOrFail($request->program_berjalan_id);
        $tipeReward = $pb->reward_type;
        $minPembelian = floatval($pb->min_pembelian ?? 0);
        $reward = floatval($pb->reward ?? 0);

        $programClaim = ProgramClaim::findOrFail($id);
        $programClaim->tanggal_klaim = $request->tanggal_klaim;
        $programClaim->program_berjalan_id = $request->program_berjalan_id;
        $programClaim->total_pembelian = $request->total_pembelian;

        if ($request->hasFile('bukti_klaim')) {
            if ($programClaim->bukti_klaim && Storage::disk('public')->exists($programClaim->bukti_klaim)) {
                Storage::disk('public')->delete($programClaim->bukti_klaim);
            }
            $programClaim->bukti_klaim = $request->file('bukti_klaim')->store('bukti_klaim', 'public');
        }

        ProgramClaimDetail::where('program_claim_id', $programClaim->id)->delete();
        $totalKlaimSistem = 0;

        foreach ($request->outlets as $data) {
            $penjualan = floatval($data['penjualan']);
            $klaimDistributor = floatval($data['klaim']);
            $klaimSistem = 0;
            $syaratTerpenuhi = $penjualan >= $minPembelian;

            if ($syaratTerpenuhi) {
                if ($tipeReward === 'persen') {
                    $klaimSistem = $penjualan * ($reward / 100);
                } elseif ($tipeReward === 'rupiah') {
                    $klaimSistem = $reward;
                } elseif ($tipeReward === 'unit') {
                    $kelipatan = $minPembelian > 0 ? floor($penjualan / $minPembelian) : 0;
                    $klaimSistem = $kelipatan * $reward;
                }
            }
            
            $totalKlaimSistem += $klaimSistem;

            ProgramClaimDetail::create([
                'program_claim_id' => $programClaim->id,
                'nama_outlet' => $data['nama'],
                'penjualan' => $penjualan,
                'klaim_distributor' => $klaimDistributor,
                'klaim_sistem' => $klaimSistem,
                'selisih' => $klaimDistributor - $klaimSistem,
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        }

        $programClaim->total_klaim = $totalKlaimSistem;
        $programClaim->save();

        return redirect()->route('program-claims.index')->with('success', 'Klaim berhasil diperbarui.');
    }
    
    // --- FUNGSI BARU UNTUK PREVIEW ---
    public function preview($id)
    {
        $claim = ProgramClaim::with('details', 'programBerjalan.program', 'programBerjalan.customer')->findOrFail($id);
        return view('program-claims.preview', compact('claim'));
    }

    // --- FUNGSI BARU UNTUK HAPUS ---
    public function destroy($id)
    {
        $claim = ProgramClaim::findOrFail($id);

        ProgramClaimDetail::where('program_claim_id', $claim->id)->delete();

        if ($claim->bukti_klaim && Storage::disk('public')->exists($claim->bukti_klaim)) {
            Storage::disk('public')->delete($claim->bukti_klaim);
        }

        $claim->delete();

        return redirect()->route('program-claims.index')->with('success', 'Data klaim berhasil dihapus.');
    }
    
    public function fetchProgram($id)
    {
        $pb = ProgramBerjalan::with(['program', 'customer'])->findOrFail($id);
        return response()->json([
            'customer'      => $pb->customer->nama_customer,
            'nama_program'  => $pb->program->nama_program,
            'jenis_program' => $pb->program->jenis_program,
            'tipe_klaim'    => $pb->program->tipe_klaim,
            'min_pembelian' => floatval($pb->min_pembelian ?? 0),
            'reward'        => floatval($pb->reward ?? 0),
            'reward_type'   => $pb->reward_type,
        ]);
    }

    private function generateKodeTransaksi()
    {
        $prefix = 'KLM' . date('Ymd');
        $last = ProgramClaim::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 3, '0', STR_PAD_LEFT);
    }
}