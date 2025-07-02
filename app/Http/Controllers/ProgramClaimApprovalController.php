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

// app/Http/Controllers/ProgramClaimApprovalController.php

public function approve($id)
{
    \Log::info("Memulai proses approve untuk klaim ID: $id");

    $claim = ProgramClaim::with('programBerjalan.customer')->findOrFail($id); // Muat relasi customer

    \Log::info("Klaim ditemukan: " . $claim->kode_transaksi);

    $program = $claim->programBerjalan;

    // ---- PERUBAHAN 1: Dapatkan ID Customer dari relasi ----
    // Pastikan relasi 'customer' pada model ProgramBerjalan sudah benar.
    // Jika belum, pastikan ada fungsi `public function customer()` di model ProgramBerjalan.
    if (!$program->customer) {
        \Log::error("Relasi customer pada ProgramBerjalan tidak ditemukan.");
        return redirect()->back()->with('error', 'Data customer untuk program ini tidak ditemukan.');
    }
    $customerId = $program->customer->id; // Mengambil ID numerik dari relasi
    // ---- AKHIR PERUBAHAN 1 ----

    $tahunAnggaran = \Carbon\Carbon::parse($program->start_date)->year;

    \Log::info("Customer ID: $customerId | Tahun: $tahunAnggaran");

    $budget = \App\Models\MarketingBudget::where('customer_id', $customerId)
        ->where('tahun_anggaran', $tahunAnggaran)
        ->first();

    if (!$budget) {
        \Log::error("Budget tidak ditemukan.");
        return redirect()->back()->with('error', 'Budget marketing untuk customer ini di tahun tersebut belum ditentukan.');
    }

    // ---- PERUBAHAN 2: Gunakan kolom 'total_klaim' yang benar ----
    if ($budget->sisa_budget < $claim->total_klaim) { // Menggunakan kolom yang benar
        \Log::warning("Sisa budget tidak cukup.");
        return redirect()->back()->with('error', 'Sisa budget tidak mencukupi untuk menyetujui klaim ini.');
    }

    \Log::info("Budget ditemukan. Potong budget...");
    $budget->sisa_budget -= $claim->total_klaim; // Menggunakan kolom yang benar
    // ---- AKHIR PERUBAHAN 2 ----
    
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
