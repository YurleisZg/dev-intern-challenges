<?php
namespace App\Features\Elkin\FruitSetLogic;

use App\Features\Elkin\FruitSetLogic\Http\Controllers\FruitSetController;
use Illuminate\Support\Facades\Route;

Route::controller(FruitSetController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/add', 'addFruit')->name('add');
    Route::get('/reset', 'reset')->name('reset');
    Route::get('/operation', 'performOperation')->name('operation');
});
