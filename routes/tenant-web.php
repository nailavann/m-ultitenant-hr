<?php

use App\Http\Controllers\Tenant\Admin\AuthController;
use App\Http\Controllers\Tenant\Admin\DashboardController;
use App\Http\Controllers\Tenant\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


/** Admin */
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/reset-password/{token}', function (string $token) {
        return view('tenant.auth.reset-password', ['token' => $token]);
    })->name('tenant.password.reset');
    Route::get('/success', function () {
        return view('tenant.auth.success');
    })->name('password.success');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('tenant.password.update');

    Route::get('/login', function () {
        return view('tenant.auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('tenant.login');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('tenant.index.user');
        Route::get('/create-user', [UserController::class, 'create'])->name('tenant.create.user');
        Route::post('/store-user', [UserController::class, 'store'])->name('tenant.store.user');
        Route::get('/logout', [AuthController::class, 'logout'])->name('tenant.logout');
    });
});
