<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;

Route::get('/', [ChirpController::class, 'index']);
Route::get('/profile/{id}', [ProfileController::class, 'showProfile']);
Route::middleware('auth')->group(function() {
    Route::post('/chirps', [ChirpController::class, 'store']);
    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit']);
    Route::put('/chirps/{chirp}', [ChirpController::class, 'update']);
    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy']);
    Route::put('/profile/save/{id}', [ProfileController::class, 'update']);
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/profile/delete/{id}', [ProfileController::class, 'destroy']);
    Route::get('/profile', [ProfileController::class, 'show']);
});

// REGISTER ROUTES
Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', Register::class)
    ->middleware('guest');

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');
Route::post('/login', Login::class)
    ->middleware('guest'); 
    // LOGOUT
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');




Route::get('/blog', function () {
    return view('blog');
});
Route::get('/layouts/app', function () {
    return view('layouts.app');
});
Route::get('/home', function () {
    return view('home');
});

Route::get('/posts', [PostController::class, 'index']);