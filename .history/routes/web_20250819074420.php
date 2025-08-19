<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('sdd');
})->name('design-doc');
Route::get('/design-doc', function () {
    return view('welcome');
})->name('database-doc');