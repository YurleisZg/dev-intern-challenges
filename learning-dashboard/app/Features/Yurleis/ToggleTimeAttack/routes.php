<?php

use Illuminate\Support\Facades\Route;
use App\Features\Yurleis\ToggleTimeAttack\Http\Controllers\GameController;

Route::middleware('auth:yurleis')->group(function () {

    Route::get('/history', [GameController::class, 'history'])->name('history');
    Route::post('/start', [GameController::class, 'start'])->name('start');

    Route::get('/{game}/stage1', [GameController::class, 'stage1'])->name('stage1');
    Route::post('/{game}/stage1', [GameController::class, 'stage1Submit'])->name('stage1.submit');

    Route::get('/{game}/stage2', [GameController::class, 'stage2'])->name('stage2');
    Route::post('/{game}/stage2', [GameController::class, 'stage2Submit'])->name('stage2.submit');

    Route::get('/{game}/victory', [GameController::class, 'victory'])->name('victory');
    Route::get('/{game}/gameover', [GameController::class, 'gameover'])->name('gameover');

    Route::delete('/{game}', [GameController::class, 'destroy'])->name('destroy');
});
