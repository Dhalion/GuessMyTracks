<?php

use App\Http\Controllers\Spotify\SpotifyAuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Page\Main;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', Main::class)->name('page.main');
    Route::get('/welcome', Welcome::class)->name('welcome'); // Keep the demo for reference if needed
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});

Route::get('/spotify/authorize', [SpotifyAuthController::class, 'authorize'])->name('spotify.authorize');
Route::get('/spotify/callback', [SpotifyAuthController::class, 'callback'])->name('spotify.callback');
