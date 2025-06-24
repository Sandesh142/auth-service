<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Login\SuperAdminLoginController;
use App\Http\Controllers\Auth\ForgotPassword\SuperAdminForgotPasswordController;
use App\Http\Controllers\Auth\ForgotPassword\SuperAdminResetPasswordController;


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