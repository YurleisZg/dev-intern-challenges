<?php
use Illuminate\Support\Facades\Route;
use App\Features\Elkin\SalaryCalculator\Http\Controllers\SalaryCalculatorController;

Route::middleware('auth:elkin')->controller(SalaryCalculatorController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/calculate', 'calculate')->name('calculate');
    Route::post('/add-row', 'addRow')->name('add-row');
    Route::post('/remove-row/{index}', 'removeRow')->name('remove-row');
    Route::post('/reset', 'reset')->name('reset');
    
    Route::get('/records/{recordId}', 'show')->name('show');
    Route::delete('/records/{recordId}', 'delete')->name('delete');
    Route::match(['get', 'post'], '/records/{recordId}/edit', 'edit')->name('edit');
    Route::put('/records/{recordId}', 'update')->name('update');
    Route::get('/statistics', 'getStatistics')->name('statistics');
});


