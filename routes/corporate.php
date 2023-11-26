<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\LoginController AS Login;
// use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Corporate\HomeController;
use App\Http\Controllers\Corporate\HistoryLaundryController;
use App\Http\Controllers\Corporate\PermintaanLaundryController;

// Route::get('/', [Login::class, 'index']);
// Route::post('login-qr', [LoginController::class, 'loginQR'])->name('login-qr');

// Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('corporate');

    Route::prefix('history-laundry')->middleware(['role_or_permission:Maintener|history-transaksi-corporate'])->group(function () {
        Route::get('/', [HistoryLaundryController::class, 'index'])->name('history-laundry-corporate');
        Route::post('/get-data', [HistoryLaundryController::class, 'getData'])->name('history-laundry-corporate.get-data');
        Route::get('/create', [HistoryLaundryController::class, 'create'])->name('history-laundry-corporate.create');
        Route::post('/store', [HistoryLaundryController::class, 'store'])->name('history-laundry-corporate.store');
        Route::get('/detail/{id}', [HistoryLaundryController::class, 'detail'])->name('history-laundry-corporate.detail');
        Route::get('/edit/{id}', [HistoryLaundryController::class, 'edit'])->name('history-laundry-corporate.edit');
        Route::put('/update', [HistoryLaundryController::class, 'update'])->name('history-laundry-corporate.update');
        Route::get('/destroy/{id}', [HistoryLaundryController::class, 'destroy'])->name('history-laundry-corporate.destroy');
        Route::post('/get-data-layanan', [HistoryLaundryController::class, 'getDataLayanan'])->name('history-laundry-corporate.get-data-layanan');
        Route::post('/get-data-parfume', [HistoryLaundryController::class, 'getDataParfume'])->name('history-laundry-corporate.get-data-parfume');
        Route::get('/like/{id}', [HistoryLaundryController::class, 'like'])->name('history-laundry-corporate.like');
        Route::get('/dislike/{id}', [HistoryLaundryController::class, 'dislike'])->name('history-laundry-corporate.dislike');
        Route::post('/get-data-info', [HistoryLaundryController::class, 'getDataInfo'])->name('history-laundry-corporate.get-data-info');
    });

    Route::prefix('permintaan-laundry')->middleware(['role_or_permission:Maintener|list-transaksi-corporate'])->group(function () {
        Route::get('/', [PermintaanLaundryController::class, 'index'])->name('permintaan-laundry-corporate');
        Route::post('/get-data', [PermintaanLaundryController::class, 'getData'])->name('permintaan-laundry-corporate.get-data');
        Route::get('/create', [PermintaanLaundryController::class, 'create'])->name('permintaan-laundry-corporate.create');
        Route::post('/store', [PermintaanLaundryController::class, 'store'])->name('permintaan-laundry-corporate.store');
        Route::get('/detail/{id}', [PermintaanLaundryController::class, 'detail'])->name('permintaan-laundry-corporate.detail');
        Route::get('/edit/{id}', [PermintaanLaundryController::class, 'edit'])->name('permintaan-laundry-corporate.edit');
        Route::put('/update', [PermintaanLaundryController::class, 'update'])->name('permintaan-laundry-corporate.update');
        Route::get('/destroy/{id}', [PermintaanLaundryController::class, 'destroy'])->name('permintaan-laundry-corporate.destroy');
        Route::post('/get-data-layanan', [PermintaanLaundryController::class, 'getDataLayanan'])->name('permintaan-laundry-corporate.get-data-layanan');
        Route::post('/get-data-parfume', [PermintaanLaundryController::class, 'getDataParfume'])->name('permintaan-laundry-corporate.get-data-parfume');
        Route::post('/getDataPesanan', [PermintaanLaundryController::class, 'getDataPesanan'])->name('permintaan-laundry-corporate.getDataPesanan');
    });
});

