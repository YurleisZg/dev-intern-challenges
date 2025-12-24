<?php
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return view('salary-calculator::index');
});

Route::get('/login',function(){
    return view('salary-calculator::login');
});


