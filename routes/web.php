<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/about-us', function () {
    return view('about-us');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/contact-us', function () {
    return view('contact-us');
});

Route::get('/faqs', function () {
    return view('faqs');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms-condition');
});

Route::get('/404-error', function () {
    return view('error-404');
});

Route::get('/login', function () {
    return response()->json(['message' => 'Login route not available.'], 404);
})->name('login');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');