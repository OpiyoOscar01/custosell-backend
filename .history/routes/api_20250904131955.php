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
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\InvoiceController;

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

    // Product management routes
    Route::apiResource('products', ProductController::class);
    Route::prefix('products')->group(function () {
        Route::get('low-stock', [ProductController::class, 'lowStock']);
        Route::get('category/{categoryId}', [ProductController::class, 'byCategory']);
    });

    // Project management routes
    Route::apiResource('projects', ProjectController::class);
    Route::prefix('projects')->group(function () {
        Route::get('active', [ProjectController::class, 'active']);
        Route::get('{id}/statistics', [ProjectController::class, 'statistics']);
    });

    // Task management routes
    Route::apiResource('tasks', TaskController::class);
    Route::prefix('tasks')->group(function () {
        Route::get('my-tasks', [TaskController::class, 'myTasks']);
        Route::put('{id}/status', [TaskController::class, 'updateStatus']);
    });

    // Order management routes
    Route::apiResource('orders', OrderController::class);
    Route::prefix('orders')->group(function () {
        Route::get('pending', [OrderController::class, 'pending']);
        Route::put('{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Invoice management routes
    Route::apiResource('invoices', InvoiceController::class);
    Route::prefix('invoices')->group(function () {
        Route::get('overdue', [InvoiceController::class, 'overdue']);
        Route::post('{id}/send-email', [InvoiceController::class, 'sendEmail']);
    });

    // Company management routes
    Route::apiResource('companies', CompanyController::class);
    Route::prefix('companies')->group(function () {
        Route::get('active', [CompanyController::class, 'active']);
        Route::get('stats', [CompanyController::class, 'stats']);
        Route::get('search', [CompanyController::class, 'search']);
    });

    // Branch management routes
    Route::apiResource('branches', BranchController::class);
    Route::prefix('branches')->group(function () {
        Route::get('active', [BranchController::class, 'active']);
        Route::get('warehouses', [BranchController::class, 'warehouses']);
        Route::get('pos-enabled', [BranchController::class, 'posEnabled']);
        Route::get('managed-by', [BranchController::class, 'managedBy']);
    });

    // Brand management routes
    Route::apiResource('brands', BrandController::class);
    Route::prefix('brands')->group(function () {
        Route::get('active', [BrandController::class, 'active']);
    });

    // Unit management routes
    Route::apiResource('units', UnitController::class);
    Route::prefix('units')->group(function () {
        Route::get('active', [UnitController::class, 'active']);
    });

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
