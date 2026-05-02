<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ChirpController;

Route::get('/', [ChirpController::class, 'index']);
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