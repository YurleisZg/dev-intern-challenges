<?php

use Illuminate\Support\Facades\Route;
use App\Features\Yurleis\ToggleTimeAttack\Controllers\ToggleGameController;

Route::middleware('auth:yurleis')->group(function () {

    Route::get('/', [ToggleGameController::class, 'index'])->name('index');
    Route::post('/start', [ToggleGameController::class, 'startGame'])->name('start');
    
    Route::post('/stage1/update', [ToggleGameController::class, 'updateStage1'])->name('update-stage1');
    Route::post('/stage1/submit', [ToggleGameController::class, 'submitStage1'])->name('submit-stage1');
    
    Route::post('/stage2/update', [ToggleGameController::class, 'updateStage2'])->name('update-stage2');
    Route::post('/stage2/submit', [ToggleGameController::class, 'submitStage2'])->name('submit-stage2');
    
    Route::get('/history', [ToggleGameController::class, 'history'])->name('history');
    Route::post('/replay/{id}', [ToggleGameController::class, 'replay'])->name('replay');
    Route::put('/edit/{id}', [ToggleGameController::class, 'edit'])->name('edit');
    Route::delete('/history/{id}', [ToggleGameController::class, 'destroy'])->name('destroy');

    Route::post('/abandon', [ToggleGameController::class, 'abandon'])->name('abandon');
});
