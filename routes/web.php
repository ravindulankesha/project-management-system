<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('homepage');
});

Route::middleware('auth')->get('/landingPage', function () {
    return view('landingPage');
})->name('landingPage');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class)->except(['show', 'edit']);
    Route::resource('projects', ProjectController::class)->except(['show', 'edit']);
});

Route::get('/home', function () {
    return view('homepage');
})->name('home');

