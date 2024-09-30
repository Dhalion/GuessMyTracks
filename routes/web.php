<?php

use App\Http\Controllers\Spotify\SpotifyAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/spotify/authorize', [SpotifyAuthController::class, 'authorize'])->name('spotify.authorize');

Route::get('/spotify/callback', [SpotifyAuthController::class, 'callback'])->name('spotify.callback');
