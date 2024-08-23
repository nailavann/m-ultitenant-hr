<?php

use App\Http\Controllers\Central\Auth\AuthController;
use App\Http\Controllers\Central\Dashboard\DashboardController;
use App\Http\Controllers\Central\Tenant\TenantController;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        /** Login */
        Route::get('/login', function () {
            return view('central.login');
        })->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('central.login');

        Route::middleware('auth:central')->group(function () {

            /** Dashboard */
            Route::get('/', [DashboardController::class, 'index'])->name('central.dashboard');

            /** Logout */
            Route::get('/logout', [AuthController::class, 'logout'])->name('central.logout');

            /** Tenant Ops */
            Route::get('/create-tenant', [TenantController::class, 'createTenant'])->name('central.create.tenant');
            Route::post('/create-tenant', [TenantController::class, 'store'])->name('central.store.tenant');
            Route::get('/tenants', [TenantController::class, 'index'])->name('central.index.tenant');
            Route::get('/tenant-detail/{tenantId}/{domainId}', [TenantController::class, 'index'])->name('central.detail.tenant');
        });

        /** Not Access Central => Tenant*/
        Route::get('/not-access', function () {
            return view('central.not_access');
        })->name('central.not.access');

    });
}
