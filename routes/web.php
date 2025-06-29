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
    return view('landing-pages.home');
});

Route::get('/about-us', function () {
    return view('landing-pages.about-us');
});

Route::get('/services', function () {
    return view('landing-pages.services');
});

Route::get('/contact-us', function () {
    return view('landing-pages.contact-us');
});

Route::get('/faqs', function () {
    return view('landing-pages.faqs');
});

Route::get('/privacy', function () {
    return view('landing-pages.privacy');
});

Route::get('/terms', function () {
    return view('landing-pages.terms-condition');
});

Route::get('/404-error', function () {
    return view('landing-pages.error-404');
});

Route::get('/login', function () {
    return response()->json(['message' => 'Login route not available.'], 404);
})->name('login');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');