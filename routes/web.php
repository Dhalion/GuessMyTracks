<?php

use App\Http\Controllers\Spotify\SpotifyAuthController;
use App\Livewire\Page\Main;
use Illuminate\Support\Facades\Route;

Route::get('/', Main::class)->name('page.main');

Route::get('/spotify/authorize', [SpotifyAuthController::class, 'authorize'])->name('spotify.authorize');

Route::get('/spotify/callback', [SpotifyAuthController::class, 'callback'])->name('spotify.callback');