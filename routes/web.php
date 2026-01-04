<?php

use App\Livewire\Page\Main;
use Illuminate\Support\Facades\Route;

Route::get('/', Main::class)->name('page.main');