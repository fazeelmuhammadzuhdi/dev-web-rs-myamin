<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // Role Management Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/data', [RoleController::class, 'getData'])->name('admin.roles.data');
        Route::post('/', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('admin.roles.show');
        Route::put('/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
        Route::post('/bulk-action', [RoleController::class, 'bulkAction'])->name('admin.roles.bulk-action');
    });
    
    // User Role Management Routes
    Route::prefix('user-roles')->group(function () {
        Route::get('/', [UserRoleController::class, 'index'])->name('admin.user-roles.index');
        Route::get('/data', [UserRoleController::class, 'getData'])->name('admin.user-roles.data');
        Route::post('/assign', [UserRoleController::class, 'assign'])->name('admin.user-roles.assign');
        Route::post('/revoke', [UserRoleController::class, 'revoke'])->name('admin.user-roles.revoke');
        Route::get('/{user}', [UserRoleController::class, 'show'])->name('admin.user-roles.show');
        Route::put('/{user}', [UserRoleController::class, 'update'])->name('admin.user-roles.update');
        Route::get('/{user}/permissions', [UserRoleController::class, 'getPermissions'])->name('admin.user-roles.permissions');
        Route::post('/bulk-assign', [UserRoleController::class, 'bulkAssign'])->name('admin.user-roles.bulk-assign');
    });
    
    // Protected Routes with Permission Middleware
    Route::middleware(['permission:products.view'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/data', [ProductController::class, 'getData'])->name('admin.products.data');
    });
    
    Route::middleware(['permission:products.create'])->group(function () {
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    });
    
    Route::middleware(['permission:products.edit'])->group(function () {
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    });
    
    Route::middleware(['permission:products.delete'])->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    });
    
    // Dashboard with permission check
    Route::middleware(['permission:dashboard.view'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });
});