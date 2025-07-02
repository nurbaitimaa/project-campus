<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesMarketingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProgramBerjalanController;
use App\Http\Controllers\ProgramClaimController;
use App\Http\Controllers\ProgramClaimApprovalController;
use App\Http\Controllers\MarketingBudgetController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect otomatis setelah login
Route::get('/dashboard', function () {
    return redirect('/redirect-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Redirect sesuai role user (admin/manager)
Route::get('/redirect-dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'manager') {
        return redirect()->route('manager.dashboard');
    } else {
        abort(403, 'Unauthorized');
    }
})->middleware(['auth']);

// =======================
// ROUTE UNTUK ADMIN
// =======================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Admin
    // routes/web.php
Route::get('/admin-dashboard', function () {
    // 1. Data untuk Kartu Statistik (sudah ada)
    $stats = [
        'programs' => \App\Models\Program::count(),
        'customers' => \App\Models\Customer::count(),
        'sales' => \App\Models\SalesMarketing::count(),
        'pending_claims' => \App\Models\ProgramClaim::where('status', 'pending')->count(),
    ];

    // 2. Data untuk Grafik Aktivitas Bulanan (Baru)
    $currentYear = date('Y');
    
    // Ambil data program baru per bulan
    $programs_monthly = \App\Models\Program::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month');

    // Ambil data klaim masuk per bulan
    $claims_monthly = \App\Models\ProgramClaim::select(
            DB::raw('MONTH(tanggal_klaim) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('tanggal_klaim', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month');
        
    // Siapkan array 12 bulan
    $program_chart_data = array_fill(1, 12, 0);
    $claim_chart_data = array_fill(1, 12, 0);

    // Isi array dengan data dari database
    foreach ($programs_monthly as $month => $count) {
        $program_chart_data[$month] = $count;
    }
    foreach ($claims_monthly as $month => $count) {
        $claim_chart_data[$month] = $count;
    }

    // Kirim semua data ke view
    return view('admin-dashboard', [
        'stats' => $stats,
        'program_chart_data' => array_values($program_chart_data), // kirim sebagai array biasa
        'claim_chart_data' => array_values($claim_chart_data) // kirim sebagai array biasa
    ]);
})->name('admin.dashboard');

    // Master Data
    Route::resource('sales-marketing', SalesMarketingController::class);
    Route::resource('customers', CustomerController::class)->middleware(['auth']);
    Route::resource('programs', ProgramController::class);

    // Absensi
    Route::resource('absensi', AbsensiController::class);
    Route::get('absensi/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
    
    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transaction', InventoryTransactionController::class)->except(['show', 'edit', 'update']);
    Route::get('/inventory/{inventory}/transactions', [InventoryTransactionController::class, 'index'])->name('inventory.transaction.index');

    // Program & Klaim
    Route::get('/program-berjalan/detail/{id}', [ProgramBerjalanController::class, 'getDetail']);
    Route::get('/program-detail/{kode_program}', [ProgramBerjalanController::class, 'getProgramDetail']);

    Route::resource('program-berjalan', ProgramBerjalanController::class);

    Route::resource('program-claims', ProgramClaimController::class)->except(['show']);
    Route::get('/program-claims/fetch/{id}', [ProgramClaimController::class, 'fetchProgram'])->name('program-claims.fetch');
    Route::get('/program-claims/{id}/edit', [ProgramClaimController::class, 'edit'])->name('program-claims.edit');
    Route::put('/program-claims/{id}', [ProgramClaimController::class, 'update'])->name('program-claims.update');
    Route::delete('/program-claims/{id}', [ProgramClaimController::class, 'destroy'])->name('program-claims.destroy');
    Route::get('/program-claims/{id}/preview', [ProgramClaimController::class, 'preview'])->name('program-claims.preview');

});

// =======================
// ROUTE UNTUK MANAGER
// =======================
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {

    // Dashboard Manager
    Route::get('/manager-dashboard', function () {
    $currentYear = date('Y');

    // 1. Ambil data untuk kartu statistik
    $pending_claims_count = \App\Models\ProgramClaim::where('status', 'pending')->count();
    $total_budget = \App\Models\MarketingBudget::where('tahun_anggaran', $currentYear)->sum('nilai_budget');
    $budget_used = \App\Models\ProgramClaim::where('status', 'approved')
                        ->whereYear('tanggal_klaim', $currentYear)
                        ->sum('total_klaim');
    $budget_remaining = $total_budget - $budget_used;

    // 2. Ambil 5 klaim terbaru yang menunggu persetujuan
    $recent_claims = \App\Models\ProgramClaim::with('programBerjalan.customer', 'programBerjalan.program')
                        ->where('status', 'pending')
                        ->latest('tanggal_klaim')
                        ->take(5)
                        ->get();
    
    // Kirim semua data ke view
    return view('manager-dashboard', [
        'pending_claims_count' => $pending_claims_count,
        'total_budget' => $total_budget,
        'budget_used' => $budget_used,
        'budget_remaining' => $budget_remaining,
        'recent_claims' => $recent_claims,
    ]);
})->name('manager.dashboard')->middleware(['auth', 'verified', 'role:manager']);

    // Approval Klaim Program
    Route::get('/approve-program-claims', [ProgramClaimApprovalController::class, 'index'])->name('approval.index');
    Route::get('/approve-program-claims/{id}/preview', [ProgramClaimApprovalController::class, 'preview'])->name('approve-program-claims.preview');
    Route::post('/approve-program-claims/{id}/approve', [ProgramClaimApprovalController::class, 'approve'])->name('approve-program-claims.approve');
    Route::post('/approve-program-claims/{id}/reject', [ProgramClaimApprovalController::class, 'reject'])->name('approve-program-claims.reject');

    // Marketing Budgets
    Route::get('marketing-budgets/create', [MarketingBudgetController::class, 'create'])->name('marketing-budgets.create');
    Route::post('marketing-budgets', [MarketingBudgetController::class, 'store'])->name('marketing-budgets.store');
});

// Route index bisa dibuka semua user login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('marketing-budgets', [MarketingBudgetController::class, 'index'])->name('marketing-budgets.index');
});
Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/absensi', [ReportController::class, 'absensi'])->name('absensi');
        Route::get('/absensi/export-excel', [ReportController::class, 'exportAbsensiExcel'])->name('absensi.exportExcel');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/klaim', [ReportController::class, 'klaim'])->name('klaim');
        Route::get('/program', [ReportController::class, 'program'])->name('program');
        Route::get('/budget', [ReportController::class, 'budget'])->name('budget');
        Route::get('/budget/export-excel', [ReportController::class, 'exportBudgetExcel'])->name('budget.exportExcel');
    });

// =======================
// ROUTE UNTUK PROFILE USER
// =======================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Force Logout Manual (opsional)
Route::get('/force-logout', function () {
    Auth::logout();
    return redirect('/');
});

// Auth Routes (dari Breeze)
require __DIR__.'/auth.php';
