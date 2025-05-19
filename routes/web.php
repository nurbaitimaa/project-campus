<?php

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
    Route::get('/admin-dashboard', function () {
        return view('admin-dashboard');
    })->name('admin.dashboard');

    // Master Data
    Route::resource('sales-marketing', SalesMarketingController::class);
    Route::resource('customers', CustomerController::class)->middleware(['auth']);
    Route::resource('programs', ProgramController::class);

    //Menu sidebar lain
    Route::resource('absensi', AbsensiController::class);
    Route::get('absensi/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
    
    //Inventory
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventory-transaction', InventoryTransactionController::class)->except(['show', 'edit', 'update']);
    Route::get('/inventory/{inventory}/transactions', [InventoryTransactionController::class, 'index'])->name('inventory.transaction.index');

    //program
    Route::resource('program-berjalan', ProgramBerjalanController::class);


});

// =======================
// ROUTE UNTUK MANAGER
// =======================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Manager
    Route::get('/manager-dashboard', function () {
        return view('manager-dashboard');
    })->name('manager.dashboard');
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
