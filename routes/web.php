<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Web\Auth\Admin\AdminRegisterWebController;
use App\Http\Controllers\Web\Auth\SuperAdmin\SuperAdminAuthController;
use App\Http\Controllers\Web\Medical\InventoryManagementController;
use App\Http\Controllers\Web\Sales\SalesController;
use App\Http\Controllers\Web\Client\ClientManagementController;
use App\Http\Controllers\Web\Revenue\RevenueManagementController;
use App\Http\Controllers\Web\Users\UserManagementController;
use App\Http\Controllers\Web\RolePermissionController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Web\Superadmin\Medical\InventoryManagementController as SuperadminInventoryManagementController;
use App\Http\Controllers\Web\Superadmin\Sales\SalesController as SuperadminSalesController;
use App\Http\Controllers\Web\Superadmin\Client\ClientManagementController as SuperadminClientManagementController;
use App\Http\Controllers\Web\Superadmin\Revenue\RevenueManagementController as SuperadminRevenueManagementController;
use App\Http\Controllers\Web\Superadmin\Users\UserManagementController as SuperadminUserManagementController;
use App\Http\Controllers\Web\Superadmin\Roles\RolePermissionController as SuperadminRolePermissionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing Pages (accessible to everyone)
Route::get('/', function () { return view('landing-pages.home'); });
Route::get('/about-us', function () { return view('landing-pages.about-us'); });
Route::get('/services', function () { return view('landing-pages.services'); });
Route::get('/contact-us', function () { return view('landing-pages.contact-us'); });
Route::get('/faqs', function () { return view('landing-pages.faqs'); });
Route::get('/privacy', function () { return view('landing-pages.privacy'); });
Route::get('/terms', function () { return view('landing-pages.terms-condition'); });
Route::get('/404-error', function () { return view('landing-pages.error-404'); });
Route::fallback(function () { return redirect('/404-error'); });


// --- Admin Authentication & Guest Routes ---
Route::prefix('admin')->middleware('guest:web')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/register', [AdminRegisterWebController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [AdminRegisterWebController::class, 'register'])->name('admin.register.submit');

    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('admin.password.reset');
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.update');
});

// --- SuperAdmin Authentication & Guest Routes ---
Route::prefix('superadmin')->middleware('guest:superadmin')->group(function () {
    Route::get('/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/login', [SuperAdminAuthController::class, 'login'])->name('superadmin.login.submit');

    Route::get('/forgot-password', [SuperAdminAuthController::class, 'showForgotPasswordForm'])->name('superadmin.password.request');
    Route::post('/forgot-password', [SuperAdminAuthController::class, 'sendResetLinkEmail'])->name('superadmin.password.email');
    Route::get('/reset-password/{token}', [SuperAdminAuthController::class, 'showResetPasswordForm'])->name('superadmin.password.reset');
    Route::post('/reset-password', [SuperAdminAuthController::class, 'resetPassword'])->name('superadmin.password.update');
});


// --- Admin Authenticated Routes ---
Route::prefix('admin')->middleware('auth:web')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', function () {
        $revenueController = new RevenueManagementController();
        $revenueSummary = $revenueController->getRevenueSummary(request());
        return view('admin-users.admin.dashboard', compact('revenueSummary'));
    })->name('admin.dashboard');
});

// --- SuperAdmin Authenticated Routes ---
Route::prefix('superadmin')->middleware('auth:superadmin')->group(function () {
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('superadmin.logout');
    Route::get('/dashboard', function () {
        $request = request();
        $revenueController = new RevenueManagementController();
        $revenueSummary = $revenueController->getRevenueSummary($request);
        return view('superadmin-users.superadmin.dashboard', compact('revenueSummary'));
    })->name('superadmin.dashboard');

    Route::prefix('permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'indexPermissions'])->name('permissions.index');
        Route::get('/create', [RolePermissionController::class, 'createPermission'])->name('permissions.create');
        Route::post('/', [RolePermissionController::class, 'storePermission'])->name('permissions.store');
        Route::get('/{permission}/edit', [RolePermissionController::class, 'editPermission'])->name('permissions.edit');
        Route::put('/{permission}', [RolePermissionController::class, 'updatePermission'])->name('permissions.update');
        Route::delete('/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');
    });
});

// Route::prefix('superadmin')->middleware('auth:superadmin')->group(function () {
//     Route::prefix('revenue')->group(function () {
//         Route::get('/', [RevenueManagementController::class, 'index'])->name('revenue.index');
//         Route::get('/create', [RevenueManagementController::class, 'create'])->name('revenue.create');
//         Route::post('/', [RevenueManagementController::class, 'store'])->name('revenue.store');
//         Route::get('/{revenueEntry}', [RevenueManagementController::class, 'show'])->name('revenue.show');
//         Route::get('/{revenueEntry}/edit', [RevenueManagementController::class, 'edit'])->name('revenue.edit');
//         Route::put('/{revenueEntry}', [RevenueManagementController::class, 'update'])->name('revenue.update');
//         Route::delete('/{revenueEntry}', [RevenueManagementController::class, 'destroy'])->name('revenue.destroy');
//         Route::get('/summary', [RevenueManagementController::class, 'getRevenueSummary'])->name('revenue.summary');
//     });

//     Route::prefix('roles')->group(function () {
//         Route::get('/', [RolePermissionController::class, 'indexRoles'])->name('roles.index');
//         Route::get('/{role}', [RolePermissionController::class, 'showRole'])->name('roles.show');
//         Route::get('/create', [RolePermissionController::class, 'createRole'])->name('roles.create');
//         Route::post('/', [RolePermissionController::class, 'storeRole'])->name('roles.store');
//         Route::get('/{role}/edit', [RolePermissionController::class, 'editRole'])->name('roles.edit');
//         Route::put('/{role}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
//         Route::delete('/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
//     });
// });

Route::prefix('superadmin')->middleware('auth:superadmin')->group(function () {
    // Dashboard and Permissions are already here

    // Client Management Routes (SuperAdmin manages all clients)
    Route::prefix('clients')->group(function () {
        Route::get('/', [SuperadminClientManagementController::class, 'indexClients'])->name('superadmin.clients.index');
        Route::get('/create', [SuperadminClientManagementController::class, 'createClient'])->name('superadmin.clients.create');
        Route::post('/', [SuperadminClientManagementController::class, 'storeClient'])->name('superadmin.clients.store');
        Route::get('/{client}', [SuperadminClientManagementController::class, 'showClient'])->name('superadmin.clients.show');
        Route::get('/{client}/edit', [SuperadminClientManagementController::class, 'editClient'])->name('superadmin.clients.edit');
        Route::put('/{client}', [SuperadminClientManagementController::class, 'updateClient'])->name('superadmin.clients.update');
        Route::delete('/{client}', [SuperadminClientManagementController::class, 'destroyClient'])->name('superadmin.clients.destroy');

        Route::prefix('{client}/branches')->group(function () {
            Route::get('/', [SuperadminClientManagementController::class, 'indexBranches'])->name('superadmin.branches.index');
            Route::get('/create', [SuperadminClientManagementController::class, 'createBranch'])->name('superadmin.branches.create');
            Route::post('/', [SuperadminClientManagementController::class, 'storeBranch'])->name('superadmin.branches.store');
            Route::get('/{branch}', [SuperadminClientManagementController::class, 'showBranch'])->name('superadmin.branches.show');
            Route::get('/{branch}/edit', [SuperadminClientManagementController::class, 'editBranch'])->name('superadmin.branches.edit');
            Route::put('/{branch}', [SuperadminClientManagementController::class, 'updateBranch'])->name('superadmin.branches.update');
            Route::delete('/{branch}', [SuperadminClientManagementController::class, 'destroyBranch'])->name('superadmin.branches.destroy');
        });

        Route::prefix('{client}/service-types')->group(function () {
            Route::get('/', [SuperadminClientManagementController::class, 'indexServiceTypes'])->name('superadmin.service-types.index');
            Route::get('/create', [SuperadminClientManagementController::class, 'createServiceType'])->name('superadmin.service-types.create');
            Route::post('/', [SuperadminClientManagementController::class, 'storeServiceType'])->name('superadmin.service-types.store');
            Route::get('/{serviceType}', [SuperadminClientManagementController::class, 'showServiceType'])->name('superadmin.service-types.show');
            Route::get('/{serviceType}/edit', [SuperadminClientManagementController::class, 'editServiceType'])->name('superadmin.service-types.edit');
            Route::put('/{serviceType}', [SuperadminClientManagementController::class, 'updateServiceType'])->name('superadmin.service-types.update');
            Route::delete('/{serviceType}', [SuperadminClientManagementController::class, 'destroyServiceType'])->name('superadmin.service-types.destroy');
        });
    });

    // Revenue Management Routes
    Route::prefix('revenue')->group(function () {
        Route::get('/', [SuperadminRevenueManagementController::class, 'index'])->name('superadmin.revenue.index');
        Route::get('/create', [SuperadminRevenueManagementController::class, 'create'])->name('superadmin.revenue.create');
        Route::post('/', [SuperadminRevenueManagementController::class, 'store'])->name('superadmin.revenue.store');
        Route::get('/{revenueEntry}', [SuperadminRevenueManagementController::class, 'show'])->name('superadmin.revenue.show');
        Route::get('/{revenueEntry}/edit', [SuperadminRevenueManagementController::class, 'edit'])->name('superadmin.revenue.edit');
        Route::put('/{revenueEntry}', [SuperadminRevenueManagementController::class, 'update'])->name('superadmin.revenue.update');
        Route::delete('/{revenueEntry}', [SuperadminRevenueManagementController::class, 'destroy'])->name('superadmin.revenue.destroy');
        Route::get('/summary', [SuperadminRevenueManagementController::class, 'getRevenueSummary'])->name('superadmin.revenue.summary');
    });

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [SuperadminUserManagementController::class, 'index'])->name('superadmin.users.index');
        Route::get('/create', [SuperadminUserManagementController::class, 'create'])->name('superadmin.users.create');
        Route::post('/', [SuperadminUserManagementController::class, 'store'])->name('superadmin.users.store');
        Route::get('/{userToManage}', [SuperadminUserManagementController::class, 'show'])->name('superadmin.users.show');
        Route::get('/{userToManage}/edit', [SuperadminUserManagementController::class, 'edit'])->name('superadmin.users.edit');
        Route::put('/{userToManage}', [SuperadminUserManagementController::class, 'update'])->name('superadmin.users.update');
        Route::delete('/{userToManage}', [SuperadminUserManagementController::class, 'destroy'])->name('superadmin.users.destroy');
    });

    // Role & Permission Management Routes (System-wide and Clinic-level roles)
    Route::prefix('roles')->group(function () {
        Route::get('/', [SuperadminRolePermissionController::class, 'indexRoles'])->name('superadmin.roles.index');
        Route::get('/{role}', [SuperadminRolePermissionController::class, 'showRole'])->name('superadmin.roles.show');
        Route::get('/create', [SuperadminRolePermissionController::class, 'createRole'])->name('superadmin.roles.create');
        Route::post('/', [SuperadminRolePermissionController::class, 'storeRole'])->name('superadmin.roles.store');
        Route::get('/{role}/edit', [SuperadminRolePermissionController::class, 'editRole'])->name('superadmin.roles.edit');
        Route::put('/{role}', [SuperadminRolePermissionController::class, 'updateRole'])->name('superadmin.roles.update');
        Route::delete('/{role}', [SuperadminRolePermissionController::class, 'destroyRole'])->name('superadmin.roles.destroy');
    });

    // Inventory Management Routes
    Route::prefix('inventory')->group(function () {
        Route::get('/medicines', [SuperadminInventoryManagementController::class, 'indexMedicines'])->name('superadmin.medicines.index');
        Route::get('/medicines/create', [SuperadminInventoryManagementController::class, 'createMedicine'])->name('superadmin.medicines.create');
        Route::post('/medicines', [SuperadminInventoryManagementController::class, 'storeMedicine'])->name('superadmin.medicines.store');
        Route::get('/medicines/{medicine}', [SuperadminInventoryManagementController::class, 'showMedicine'])->name('superadmin.medicines.show');
        Route::get('/medicines/{medicine}/edit', [SuperadminInventoryManagementController::class, 'editMedicine'])->name('superadmin.medicines.edit');
        Route::put('/medicines/{medicine}', [SuperadminInventoryManagementController::class, 'updateMedicine'])->name('superadmin.medicines.update');
        Route::delete('/medicines/{medicine}', [SuperadminInventoryManagementController::class, 'destroyMedicine'])->name('superadmin.medicines.destroy');

        Route::prefix('medicines/{medicine}/batches')->group(function () {
            Route::get('/create', [SuperadminInventoryManagementController::class, 'createBatch'])->name('superadmin.batches.create');
            Route::post('/', [SuperadminInventoryManagementController::class, 'storeBatch'])->name('superadmin.batches.store');
            Route::get('/{batch}/edit', [SuperadminInventoryManagementController::class, 'editBatch'])->name('superadmin.batches.edit');
            Route::put('/{batch}', [SuperadminInventoryManagementController::class, 'updateBatch'])->name('superadmin.batches.update');
            Route::delete('/{batch}', [SuperadminInventoryManagementController::class, 'destroyBatch'])->name('superadmin.batches.destroy');
        });
    });

    // Sales Routes
    Route::prefix('sales')->group(function () {
        Route::get('/', [SuperadminSalesController::class, 'index'])->name('superadmin.sales.index');
        Route::get('/create', [SuperadminSalesController::class, 'create'])->name('superadmin.sales.create');
        Route::post('/', [SuperadminSalesController::class, 'store'])->name('superadmin.sales.store');
        Route::get('/{sale}', [SuperadminSalesController::class, 'show'])->name('superadmin.sales.show');
        Route::get('/{sale}/edit', [SuperadminSalesController::class, 'edit'])->name('superadmin.sales.edit');
        Route::put('/{sale}', [SuperadminSalesController::class, 'update'])->name('superadmin.sales.update');
        Route::delete('/{sale}', [SuperadminSalesController::class, 'destroy'])->name('superadmin.sales.destroy');

        Route::get('/search-medicines', [SuperadminSalesController::class, 'searchMedicinesForSale'])->name('superadmin.sales.searchMedicines');
    });
});

// --- Shared Authenticated Routes (for both 'web' and 'superadmin' guards) ---
// THIS IS THE FINAL CRITICAL CHANGE: Use the new 'auth.shared' middleware group
Route::middleware('auth:web')->group(function () {
    // Client Management Routes
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientManagementController::class, 'indexClients'])->name('clients.index');
        Route::get('/create', [ClientManagementController::class, 'createClient'])->name('clients.create');
        Route::post('/', [ClientManagementController::class, 'storeClient'])->name('clients.store');
        Route::get('/{client}', [ClientManagementController::class, 'showClient'])->name('clients.show');
        Route::get('/{client}/edit', [ClientManagementController::class, 'editClient'])->name('clients.edit');
        Route::put('/{client}', [ClientManagementController::class, 'updateClient'])->name('clients.update');
        Route::delete('/{client}', [ClientManagementController::class, 'destroyClient'])->name('clients.destroy');

        Route::prefix('{client}/branches')->group(function () {
            Route::get('/', [ClientManagementController::class, 'indexBranches'])->name('branches.index');
            Route::get('/create', [ClientManagementController::class, 'createBranch'])->name('branches.create');
            Route::post('/', [ClientManagementController::class, 'storeBranch'])->name('branches.store');
            Route::get('/{branch}', [ClientManagementController::class, 'showBranch'])->name('branches.show');
            Route::get('/{branch}/edit', [ClientManagementController::class, 'editBranch'])->name('branches.edit');
            Route::put('/{branch}', [ClientManagementController::class, 'updateBranch'])->name('branches.update');
            Route::delete('/{branch}', [ClientManagementController::class, 'destroyBranch'])->name('branches.destroy');
        });

        Route::prefix('{client}/service-types')->group(function () {
            Route::get('/', [ClientManagementController::class, 'indexServiceTypes'])->name('service-types.index');
            Route::get('/create', [ClientManagementController::class, 'createServiceType'])->name('service-types.create');
            Route::post('/', [ClientManagementController::class, 'storeServiceType'])->name('service-types.store');
            Route::get('/{serviceType}', [ClientManagementController::class, 'showServiceType'])->name('service-types.show');
            Route::get('/{serviceType}/edit', [ClientManagementController::class, 'editServiceType'])->name('service-types.edit');
            Route::put('/{serviceType}', [ClientManagementController::class, 'updateServiceType'])->name('service-types.update');
            Route::delete('/{serviceType}', [ClientManagementController::class, 'destroyServiceType'])->name('service-types.destroy');
        });
    });

    // Revenue Management Routes
    Route::prefix('revenue')->group(function () {
        Route::get('/', [RevenueManagementController::class, 'index'])->name('revenue.index');
        Route::get('/create', [RevenueManagementController::class, 'create'])->name('revenue.create');
        Route::post('/', [RevenueManagementController::class, 'store'])->name('revenue.store');
        Route::get('/{revenueEntry}', [RevenueManagementController::class, 'show'])->name('revenue.show');
        Route::get('/{revenueEntry}/edit', [RevenueManagementController::class, 'edit'])->name('revenue.edit');
        Route::put('/{revenueEntry}', [RevenueManagementController::class, 'update'])->name('revenue.update');
        Route::delete('/{revenueEntry}', [RevenueManagementController::class, 'destroy'])->name('revenue.destroy');
        Route::get('/summary', [RevenueManagementController::class, 'getRevenueSummary'])->name('revenue.summary');
    });

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/{userToManage}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/{userToManage}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/{userToManage}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/{userToManage}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Role & Permission Management Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RolePermissionController::class, 'indexRoles'])->name('roles.index');
        Route::get('/{role}', [RolePermissionController::class, 'showRole'])->name('roles.show');
        Route::get('/create', [RolePermissionController::class, 'createRole'])->name('roles.create');
        Route::post('/', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::get('/{role}/edit', [RolePermissionController::class, 'editRole'])->name('roles.edit');
        Route::put('/{role}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
    });

    // Inventory Management Routes
    Route::prefix('inventory')->group(function () {
        Route::get('/medicines', [InventoryManagementController::class, 'indexMedicines'])->name('medicines.index');
        Route::get('/medicines/create', [InventoryManagementController::class, 'createMedicine'])->name('medicines.create');
        Route::post('/medicines', [InventoryManagementController::class, 'storeMedicine'])->name('medicines.store');
        Route::get('/medicines/{medicine}', [InventoryManagementController::class, 'showMedicine'])->name('medicines.show');
        Route::get('/medicines/{medicine}/edit', [InventoryManagementController::class, 'editMedicine'])->name('medicines.edit');
        Route::put('/medicines/{medicine}', [InventoryManagementController::class, 'updateMedicine'])->name('medicines.update');
        Route::delete('/medicines/{medicine}', [InventoryManagementController::class, 'destroyMedicine'])->name('medicines.destroy');

        Route::prefix('medicines/{medicine}/batches')->group(function () {
            Route::get('/create', [InventoryManagementController::class, 'createBatch'])->name('batches.create');
            Route::post('/', [InventoryManagementController::class, 'storeBatch'])->name('batches.store');
            Route::get('/{batch}/edit', [InventoryManagementController::class, 'editBatch'])->name('batches.edit');
            Route::put('/{batch}', [InventoryManagementController::class, 'updateBatch'])->name('batches.update');
            Route::delete('/{batch}', [InventoryManagementController::class, 'destroyBatch'])->name('batches.destroy');
        });
    });

    // Sales Routes
    Route::prefix('sales')->group(function () {
        Route::get('/search-medicines', [SalesController::class, 'searchMedicinesForSale'])->name('sales.searchMedicines');
        Route::get('/', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/create', [SalesController::class, 'create'])->name('sales.create');
        Route::post('/', [SalesController::class, 'store'])->name('sales.store');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('sales.show');
        Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit');
        Route::put('/{sale}', [SalesController::class, 'update'])->name('sales.update');
        Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy');

        // Route::get('/search-medicines', [SalesController::class, 'searchMedicinesForSale'])->name('sales.searchMedicines');
    });
});
