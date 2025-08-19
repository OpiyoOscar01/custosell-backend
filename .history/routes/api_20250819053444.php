<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::prefix('auth')->group(function () {
    // Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    // Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    // Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');