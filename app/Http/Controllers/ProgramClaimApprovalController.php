<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramClaim;
use App\Models\ProgramBerjalan;


class ProgramClaimApprovalController extends Controller
{
    // Di ProgramClaimApprovalController
public function index()
{
    $claims = ProgramClaim::with('programBerjalan.customer', 'programBerjalan.program')
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('program-claims.approval.index', compact('claims'));
}

public function preview($id)
{
    $claim = ProgramClaim::with('details', 'programBerjalan.program', 'programBerjalan.customer')->findOrFail($id);
    return view('program-claims.preview', compact('claim'));
}

public function approve($id)
{
    \Log::info("Memulai proses approve untuk klaim ID: $id");

    $claim = ProgramClaim::with('programBerjalan')->findOrFail($id);

    \Log::info("Klaim ditemukan: " . $claim->kode_transaksi);

    $program = $claim->programBerjalan;
    $customerId = $program->customer_id ?? $program->kode_customer;
    $tahunAnggaran = \Carbon\Carbon::parse($program->start_date)->year;

    \Log::info("Customer ID: $customerId | Tahun: $tahunAnggaran");

    $budget = \App\Models\MarketingBudget::where('customer_id', $customerId)
        ->where('tahun_anggaran', $tahunAnggaran)
        ->first();

    if (!$budget) {
        \Log::error("Budget tidak ditemukan.");
        return redirect()->back()->with('error', 'Budget marketing untuk customer ini di tahun tersebut belum ditentukan.');
    }

    if ($budget->sisa_budget < $claim->klaim_nominal_sistem) {
        \Log::warning("Sisa budget tidak cukup.");
        return redirect()->back()->with('error', 'Sisa budget tidak mencukupi untuk menyetujui klaim ini.');
    }

    \Log::info("Budget ditemukan. Potong budget...");
    $budget->sisa_budget -= $claim->klaim_nominal_sistem;
    $budget->save();

    $claim->status = 'approved';
    $claim->save();

    \Log::info("Klaim berhasil di-approve!");

    return redirect()->back()->with('success', 'Klaim berhasil di-approve dan budget diperbarui.');
}




public function reject($id)
{
    $claim = ProgramClaim::findOrFail($id);
    $claim->status = 'rejected';
    $claim->save();

    return redirect()->back()->with('success', 'Klaim ditolak.');
}


}
