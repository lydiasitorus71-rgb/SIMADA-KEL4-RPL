<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaketPengadaanController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::get('/test-login', function () {
    $user = App\Models\Pengguna::find(1);
    Auth::login($user);
    return 'Logged in as ' . Auth::user()->username;
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:Admin,PPK'])->group(function () {
    Route::get('/paket-pengadaan/{id}/cetak/{jenis}', [\App\Http\Controllers\CetakDokumenController::class, 'unduhBeritaAcara'])
        ->name('paket-pengadaan.cetak')
        ->where('jenis', 'penetapan|pengumuman');

    Route::get('integrasi-spse', [\App\Http\Controllers\IntegrasiSpseController::class, 'index'])->name('integrasi-spse.index');
    Route::post('integrasi-spse', [\App\Http\Controllers\IntegrasiSpseController::class, 'store'])->name('integrasi-spse.store');
    
    Route::resource('paket-pengadaan', PaketPengadaanController::class)->except(['index', 'show']);
    Route::post('paket-pengadaan/{paket_id}/assign-personil', [PaketPengadaanController::class, 'assignPersonil'])->name('paket-pengadaan.assign-personil');
    Route::put('paket-pengadaan/{paket_id}/personil/{personil_id}', [PaketPengadaanController::class, 'updatePersonil'])->name('paket-pengadaan.update-personil');
    Route::delete('paket-pengadaan/{paket_id}/personil/{personil_id}', [PaketPengadaanController::class, 'removePersonil'])->name('paket-pengadaan.remove-personil');
    Route::post('paket-pengadaan/{paket_id}/tetapkan-pemenang', [PaketPengadaanController::class, 'tetapkanPemenang'])->name('paket-pengadaan.tetapkan-pemenang');
});

// Put this AFTER the except(['index', 'show']) so that /create doesn't get swallowed by /show
Route::middleware('auth')->group(function () {
    Route::resource('paket-pengadaan', PaketPengadaanController::class)->only(['index', 'show']);
    Route::post('paket-pengadaan/{paket_id}/ajukan-review', [PaketPengadaanController::class, 'ajukanReview'])->name('paket-pengadaan.ajukan-review');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('personil', PersonilController::class);
    Route::resource('pengguna', App\Http\Controllers\PenggunaController::class);
});
