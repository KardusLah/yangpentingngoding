<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserLevel;
use App\Http\Middleware\CheckPelanggan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PaketWisataController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ObyekWisataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenginapanController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiskonPaketController;

// ===========================
// FRONTEND / UMUM
// ===========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// --- Paket Wisata ---
Route::get('/paket', [PaketWisataController::class, 'frontendIndex'])->name('fe.paket.index');
Route::get('/paket/{id}', [PaketWisataController::class, 'show'])->name('fe.paket.show');

// --- Penginapan ---
Route::get('/penginapan', [PenginapanController::class, 'frontendIndex'])->name('fe.penginapan.index');
Route::get('/penginapan/{id}', [PenginapanController::class, 'show'])->name('fe.penginapan.show');

// --- Berita ---
Route::get('/berita', [BeritaController::class, 'frontendIndex'])->name('fe.berita.index');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('fe.berita.show');

// --- Wisata ---
Route::get('/wisata', [ObyekWisataController::class, 'frontendIndex'])->name('fe.wisata.index');
Route::get('/wisata/{id}', [ObyekWisataController::class, 'show'])->name('fe.wisata.show');

// --- Profile & Reservasi ---
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/reservasi/detail/{id}', [ReservasiController::class, 'detail'])->name('fe.reservasi.detail');
Route::get('/reservasi', [ReservasiController::class, 'feIndex'])->name('fe.reservasi.index');
Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
Route::post('/reservasi/{id}/upload-bukti', [ProfileController::class, 'uploadBukti'])->name('reservasi.uploadBukti');

// ===========================
// AUTH
// ===========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
    Route::get('/register', [AuthController::class, 'registration'])->name('registration');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');
});
Route::get('/pelanggan/data-diri', [AuthController::class, 'pelangganDataDiriForm'])->name('auth.pelangganDataDiriForm');
Route::post('/pelanggan/data-diri', [AuthController::class, 'pelangganDataDiriSimpan'])->name('auth.pelangganDataDiriSimpan');
Route::get('/karyawan/data-diri', [AuthController::class, 'karyawanDataDiriForm'])->name('auth.karyawanDataDiriForm');
Route::post('/karyawan/data-diri', [AuthController::class, 'karyawanDataDiriSimpan'])->name('auth.karyawanDataDiriSimpan');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ===========================
// ADMIN
// ===========================
Route::middleware(['auth', CheckUserLevel::class . ':admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // --- Manajemen Objek Wisata ---
    Route::prefix('be/wisata')->name('wisata.')->group(function () {
        Route::get('/', [ObyekWisataController::class, 'index'])->name('index');
        Route::get('/create', [ObyekWisataController::class, 'create'])->name('create');
        Route::post('/', [ObyekWisataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ObyekWisataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ObyekWisataController::class, 'update'])->name('update');
        Route::delete('/{id}', [ObyekWisataController::class, 'destroy'])->name('destroy');
    });

    // --- Manajemen Penginapan ---
    Route::prefix('be/penginapan')->name('penginapan.')->group(function () {
        Route::get('/', [PenginapanController::class, 'index'])->name('index');
        Route::get('/create', [PenginapanController::class, 'create'])->name('create');
        Route::post('/', [PenginapanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PenginapanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenginapanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenginapanController::class, 'destroy'])->name('destroy');
    });

    // --- Manajemen Berita ---
    Route::prefix('be/berita')->name('berita.')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('index');
        Route::get('/create', [BeritaController::class, 'create'])->name('create');
        Route::post('/', [BeritaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');
    });

    // --- Manajemen User & Hak Akses ---
    Route::prefix('be/user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/status/{id}', [UserController::class, 'updateStatus']);
        Route::post('/bulk/{action}', [UserController::class, 'bulkAction'])->name('user.bulk');
    });

    // --- Kategori Berita ---
    Route::prefix('be/kategori-berita')->name('kategori-berita.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KategoriBeritaController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\KategoriBeritaController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\KategoriBeritaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\KategoriBeritaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\KategoriBeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\KategoriBeritaController::class, 'destroy'])->name('destroy');
    });

    // --- Kategori Wisata ---
    Route::prefix('be/kategori-wisata')->name('kategori-wisata.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KategoriWisataController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\KategoriWisataController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\KategoriWisataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\KategoriWisataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\KategoriWisataController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\KategoriWisataController::class, 'destroy'])->name('destroy');
    });
});

// ===========================
// BENDAHARA
// ===========================
Route::middleware(['auth', CheckUserLevel::class . ':bendahara'])->group(function () {
    Route::get('/bendahara', [BendaharaController::class, 'index'])->name('bendahara.index');

    // --- Manajemen Pembayaran Reservasi ---
    Route::prefix('be/reservasi')->name('reservasi.')->group(function () {
        Route::get('/', [ReservasiController::class, 'index'])->name('index');
        Route::get('/create', [ReservasiController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [ReservasiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReservasiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReservasiController::class, 'destroy'])->name('destroy');
        Route::match(['get', 'post'], '/simulasi', [ReservasiController::class, 'simulasi'])->name('simulasi');
        Route::post('bulk/{action}', [ReservasiController::class, 'bulk'])->name('bulk');
    });
    Route::post('/reservasi/{id}/terima', [ReservasiController::class, 'terima'])->name('reservasi.terima');
    Route::post('/reservasi/{id}/tolak', [ReservasiController::class, 'tolak'])->name('reservasi.tolak');
    Route::post('/reservasi/{id}/selesai', [ReservasiController::class, 'selesai'])->name('reservasi.selesai');

    // --- Manajemen Paket Wisata ---
    Route::prefix('be/paket')->name('paket.')->group(function () {
        Route::get('/', [PaketWisataController::class, 'index'])->name('index');
        Route::get('/create', [PaketWisataController::class, 'create'])->name('create');
        Route::post('/', [PaketWisataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PaketWisataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaketWisataController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaketWisataController::class, 'destroy'])->name('destroy');
    });

    // --- Laporan Keuangan ---
    Route::prefix('be/laporan')->name('laporan.')->group(function () {
        Route::get('/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export-excel', [LaporanKeuanganController::class, 'exportExcel'])->name('exportExcel');
    });

    // --- Diskon Paket ---
    Route::middleware(['auth', CheckUserLevel::class . ':bendahara'])->group(function () {
        Route::get('be/diskon', [DiskonPaketController::class, 'index'])->name('diskon.index');
        Route::post('be/diskon/update', [DiskonPaketController::class, 'update'])->name('diskon.update');
    });

    // --- Bank ---
    Route::resource('be/bank', \App\Http\Controllers\BankController::class)->names('bank');
});

// ===========================
// OWNER
// ===========================
Route::middleware(['auth', CheckUserLevel::class . ':pemilik'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner.index');
    Route::prefix('be/laporan')->name('laporan.')->group(function () {
        Route::get('/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export-excel', [LaporanKeuanganController::class, 'exportExcel'])->name('exportExcel');
    });
});

// ===========================
// PELANGGAN
// ===========================
Route::middleware(['auth', CheckPelanggan::class])->group(function () {
    Route::get('/profilepelanggan', [PelangganController::class, 'profilePelanggan'])->name('profilepelanggan');
    // ...route pelanggan lain...
});

// ===========================
// LAPORAN EKSPORT (GLOBAL)
// ===========================
Route::get('/laporan/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('laporan.exportPdf')->middleware(['auth', CheckUserLevel::class . ':pemilik,bendahara']);
Route::get('/laporan/export-excel', [LaporanKeuanganController::class, 'exportExcel'])->name('laporan.exportExcel')->middleware(['auth', CheckUserLevel::class . ':pemilik,bendahara']);

// ===========================
// END ROUTES
// ===========================