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


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);

// Login and Registration Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
    Route::get('/register', [AuthController::class, 'registration'])->name('registration');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');
}); 

// User authentication routes
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', CheckUserLevel::class . ':admin']);
Route::get('/bendahara', [BendaharaController::class, 'index'])->middleware(['auth', CheckUserLevel::class . ':bendahara']);
Route::get('/owner', [OwnerController::class, 'index'])->middleware(['auth', CheckUserLevel::class . ':owner']);
Route::get('/profilepelanggan', [PelangganController::class, 'profilePelanggan'])->middleware(['auth', CheckPelanggan::class]);
Route::post('/keluar', [PelangganController::class, 'keluar'])->middleware('auth:pelanggan');

// Reservasi Backend Routes
Route::prefix('be/reservasi')->name('reservasi.')->group(function () {
    Route::get('/', [ReservasiController::class, 'index'])->name('index');
    Route::get('/create', [ReservasiController::class, 'create'])->name('create');
    Route::post('/', [ReservasiController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ReservasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ReservasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReservasiController::class, 'destroy'])->name('destroy');
});

// Paket Wisata Backend Routes
Route::prefix('be/paket')->name('paket.')->group(function () {
    Route::get('/', [PaketWisataController::class, 'index'])->name('index');
    Route::get('/create', [PaketWisataController::class, 'create'])->name('create');
    Route::post('/', [PaketWisataController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PaketWisataController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PaketWisataController::class, 'update'])->name('update');
    Route::delete('/{id}', [PaketWisataController::class, 'destroy'])->name('destroy');
});

// Berita Routes
Route::prefix('be/berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/create', [BeritaController::class, 'create'])->name('create');
    Route::post('/', [BeritaController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
    Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');
});

// Obyek Wisata Routes
Route::prefix('be/wisata')->name('wisata.')->group(function () {
    Route::get('/', [ObyekWisataController::class, 'index'])->name('index');
    Route::get('/create', [ObyekWisataController::class, 'create'])->name('create');
    Route::post('/', [ObyekWisataController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ObyekWisataController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ObyekWisataController::class, 'update'])->name('update');
    Route::delete('/{id}', [ObyekWisataController::class, 'destroy'])->name('destroy');
});

// Penginapan Routes
use App\Http\Controllers\PenginapanController;

Route::prefix('be/penginapan')->name('penginapan.')->group(function () {
    Route::get('/', [PenginapanController::class, 'index'])->name('index');
    Route::get('/create', [PenginapanController::class, 'create'])->name('create');
    Route::post('/', [PenginapanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PenginapanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PenginapanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PenginapanController::class, 'destroy'])->name('destroy');
});

// Manajemen User & Hak Akses Routes
Route::prefix('be/user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::get('/be/user/create', [UserController::class, 'create'])->name('create');
    Route::post('/be/user', [UserController::class, 'store'])->name('store');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
});
