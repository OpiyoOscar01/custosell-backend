<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\BranchController;

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verification.send');
});

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    // User info route
    Route::get('/user', function (Request $request) {
        return $request->user()->load('roles', 'permissions');
    });

    // Auth routes for authenticated users
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);
    });

    // Business entity routes with permission middleware
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('customers', CustomerController::class);

    // Workspace management routes
    Route::apiResource('workspaces', WorkspaceController::class);
    Route::prefix('workspaces/{workspace}')->group(function () {
        Route::post('members', [WorkspaceController::class, 'addMember']);
        Route::put('members/{user}', [WorkspaceController::class, 'updateMember']);
        Route::delete('members/{user}', [WorkspaceController::class, 'removeMember']);
        Route::get('statistics', [WorkspaceController::class, 'statistics']);
    });

    // Team management routes
    Route::apiResource('teams', TeamController::class);
    Route::prefix('teams/{team}')->group(function () {
        Route::post('members', [TeamController::class, 'addMember']);
        Route::put('members/{user}', [TeamController::class, 'updateMember']);
        Route::delete('members/{user}', [TeamController::class, 'removeMember']);
        Route::post('transfer-leadership', [TeamController::class, 'transferLeadership']);
        Route::get('statistics', [TeamController::class, 'statistics']);
    });
});
