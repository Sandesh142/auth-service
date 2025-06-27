<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Login\SuperAdminLoginController;
use App\Http\Controllers\Auth\Login\AdminLoginController;
use App\Http\Controllers\Auth\Login\StaffLoginController;
use App\Http\Controllers\Auth\Login\ClientLoginController;
use App\Http\Controllers\Auth\ForgotPassword\SuperAdminForgotPasswordController;
use App\Http\Controllers\Auth\ForgotPassword\SuperAdminResetPasswordController;
use App\Http\Controllers\Auth\Register\Admin\AdminRegisterController;
use App\Http\Controllers\Auth\Logout\UserLogoutController;
use App\Http\Controllers\Auth\ForgotPassword\UserForgotPasswordController;
use App\Http\Controllers\Auth\ForgotPassword\UserResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('superadmin')->group(function () {
    Route::post('/login', [SuperAdminLoginController::class, 'login']);
    Route::post('/logout', [SuperAdminLoginController::class, 'logout'])->middleware('auth:superadmin');
    // Route::get('/me', [SuperAdminLoginController::class, 'me'])->middleware('auth:superadmin');
});

Route::middleware('auth:sanctum')->get('/superadmin/me', function (Request $request) {
    return response()->json($request->user());
});

Route::prefix('superadmin/password')->group(function () {
    Route::post('/forgot', [SuperAdminForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [SuperAdminResetPasswordController::class, 'reset']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('staff')->group(function () {
    Route::post('/login', [StaffLoginController::class, 'login']);
    Route::post('/logout', [StaffLoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('client')->group(function () {
    Route::post('/login', [ClientLoginController::class, 'login']);
    Route::post('/logout', [ClientLoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::post('/admin/register', [AdminRegisterController::class, 'initialRegister']);

Route::prefix('user')->group(function () {
    Route::get('/me', [AdminRegisterController::class, 'me'])->middleware('auth:sanctum');
});

Route::post('/logout', [UserLogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('user/password')->group(function () {
    Route::post('/forgot', [UserForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [UserResetPasswordController::class, 'reset']);
});