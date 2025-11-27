<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\FileUploadController; // TAMBAHKAN INI

Route::get('/sia', function () {
    return 'Selamat Datang di Website Kampus PCR!';
});

Route::get('/mahasiswa', function () {
    return 'Halo Mahasiswa';
});

Route::get('/nama/{param1}', function ($param1) {
    return 'Nama saya: '.$param1;
});

Route::get('/mahasiswa', function () {
    return 'Halo Mahasiswa';
})->name('mahasiswa.show');

Route::get('/mahsiswa/{param1}',[MahasiswaController::class, 'show']);

Route::get('/about', function () {
    return view('halaman-about');
});

Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

Route::post('question/store', [QuestionController::class, 'store'])
		->name('question.store');

Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

// Routes untuk Pelanggan - JANGAN DUPLIKAT
Route::resource('pelanggan', PelangganController::class);
Route::delete('/pelanggan/{id}/files/{filename}', [PelangganController::class, 'destroyFile'])
     ->name('pelanggan.destroy-file');
// HAPUS BARIS INI: Route::get('/pelanggan/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');

// Routes untuk User
Route::resource('user', UserController::class);

// Routes untuk File Upload
Route::post('/upload', [FileUploadController::class, 'store'])->name('file.upload');
Route::delete('/files/{id}', [FileUploadController::class, 'destroy'])->name('file.destroy');
Route::get('/files/{refTable}/{refId}', [FileUploadController::class, 'getFiles'])->name('file.get');

// Routes untuk File Upload dengan prefix
Route::prefix('api')->group(function () {
    Route::post('/upload', [FileUploadController::class, 'store'])->name('file.upload');
    Route::delete('/files/{id}', [FileUploadController::class, 'destroy'])->name('file.destroy');
    Route::get('/files/{refTable}/{refId}', [FileUploadController::class, 'getFiles'])->name('file.get');
});