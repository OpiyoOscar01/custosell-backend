<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;

// API Routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

// Test route to check API connectivity
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
