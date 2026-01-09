<?php
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return "<H1>TODO LIST</H1>";
});

Route::get('/login',function(){
    return view('salary-calculator::login');
});


