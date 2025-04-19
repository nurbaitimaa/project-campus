<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman depan
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect otomatis setelah login
Route::get('/dashboard', function () {
    return redirect('/redirect-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ROUTE REDIRECT BERDASARKAN ROLE
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

// Dashboard untuk Admin
Route::get('/admin-dashboard', function () {
    return view('admin-dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// Dashboard untuk Manager
Route::get('/manager-dashboard', function () {
    return view('manager-dashboard');
})->middleware(['auth', 'verified'])->name('manager.dashboard');

// Grup route untuk profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/force-logout', function () {
    Auth::logout();
    return redirect('/');
});


require __DIR__.'/auth.php';
