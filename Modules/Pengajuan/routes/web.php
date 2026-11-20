<?php

use Illuminate\Support\Facades\Route;
use Modules\Pengajuan\Http\Controllers\PengajuanController;

Route::middleware(['auth'])->prefix('pengajuan')->name('pengajuan.')->group(function () {
    Route::get('/', [PengajuanController::class, 'index'])->name('index');
    Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');

    Route::middleware(['role:pemohon'])->group(function () {
        Route::post('/', [PengajuanController::class, 'store'])->name('store');
        Route::get('/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('edit');
        Route::put('/{pengajuan}', [PengajuanController::class, 'update'])->name('update');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::patch('/{pengajuan}/keputusan', [PengajuanController::class, 'keputusan'])->name('keputusan');
    });

    Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('destroy');
});