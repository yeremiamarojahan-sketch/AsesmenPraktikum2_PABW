<?php

use Illuminate\Support\Facades\Route;
use Modules\Donasi\Http\Controllers\DonasiController;

Route::middleware(['auth'])->prefix('donasi')->name('donasi.')->group(function () {
    Route::get('/', [DonasiController::class, 'index'])->name('index');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/create', [DonasiController::class, 'create'])->name('create');
        Route::post('/', [DonasiController::class, 'store'])->name('store');
        Route::get('/{donasi}/edit', [DonasiController::class, 'edit'])->name('edit');
        Route::put('/{donasi}', [DonasiController::class, 'update'])->name('update');
        Route::delete('/{donasi}', [DonasiController::class, 'destroy'])->name('destroy');
    });
});