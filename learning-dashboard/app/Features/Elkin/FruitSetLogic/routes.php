<?php
namespace App\Features\Views;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return view('fruit-set-logic::index');
});

Route::get('/challenge1',function(){
    return view('challenge1');
})->name('challenge1');
