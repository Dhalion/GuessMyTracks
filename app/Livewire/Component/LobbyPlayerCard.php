<?php

namespace App\Livewire\Component;

use App\Models\User;
use Livewire\Component;

class LobbyPlayerCard extends Component
{
    public User $player;

    public function mount(User $player)
    {
        $this->player = $player;
    }

    public function render()
    {
        // Lade die Anzahl der Tracks bei jedem Rendern neu
        $this->player->loadCount('tracks');

        return view('livewire.component.lobby-player-card', [
            'player' => $this->player,
        ]);
    }
}
