<?php

namespace App\Livewire\Page;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Main extends Component
{
    public function render()
    {
        return view('livewire.page.main', [
            'user' => Auth::user(),
        ]);
    }
}
