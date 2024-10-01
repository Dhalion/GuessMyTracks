<?php

use App\Http\Controllers\Spotify\SpotifyAuthController;
use App\Http\Controllers\UserOverviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/spotify/authorize', [SpotifyAuthController::class, 'authorize'])->name('spotify.authorize');

Route::get('/spotify/callback', [SpotifyAuthController::class, 'callback'])->name('spotify.callback');

Route::get('/user', [UserOverviewController::class, 'show'])->name('user');
