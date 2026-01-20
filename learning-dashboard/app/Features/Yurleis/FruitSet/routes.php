<?php 

use App\Features\Yurleis\FruitSet\Controllers\FruitSetController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:yurleis')->group(function () {

    Route::get('/fruit-set', [FruitSetController::class, 'index'])->name('index');

});