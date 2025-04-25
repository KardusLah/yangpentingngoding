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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login-user');
    Route::get('/register', [AuthController::class, 'registration'])->name('registration');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', CheckUserLevel::class . ':admin']);

Route::get('/bendahara', [BendaharaController::class, 'index'])
    ->middleware(['auth', CheckUserLevel::class . ':bendahara']);

Route::get('/owner', [OwnerController::class, 'index'])
    ->middleware(['auth', CheckUserLevel::class . ':owner']);

Route::get('/profilepelanggan', [PelangganController::class, 'profilePelanggan'])
    ->middleware(['auth', CheckPelanggan::class]);

Route::post('/keluar', [PelangganController::class, 'keluar'])->middleware('auth:pelanggan');