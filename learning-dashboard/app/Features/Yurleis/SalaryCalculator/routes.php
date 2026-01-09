<?php

use Illuminate\Support\Facades\Route;
use App\Features\Yurleis\SalaryCalculator\Http\Controllers\SalaryRecordController;

Route::middleware('auth:yurleis')->group(function () {
    Route::get('/', [SalaryRecordController::class, 'index'])->name('index');
    Route::get('/create', [SalaryRecordController::class, 'create'])->name('create');
    Route::post('/', [SalaryRecordController::class, 'store'])->name('store');

    Route::get('/{record}', [SalaryRecordController::class, 'show'])->name('show');
    Route::get('/{record}/edit', [SalaryRecordController::class, 'edit'])->name('edit');
    Route::put('/{record}', [SalaryRecordController::class, 'update'])->name('update');
    Route::delete('/{record}', [SalaryRecordController::class, 'destroy'])->name('destroy');
});
