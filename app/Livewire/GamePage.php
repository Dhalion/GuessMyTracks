<?php

namespace App\Livewire;

use App\Models\Game as ModelsGame;
use App\Models\User;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GamePage extends Component
{
    public ?string $gameId;
    public ?User $user;

    public function mount(?string $gameId = null)
    {
        $this->gameId = $gameId ?? null;

        // redirect to old game if game id is not provided
        if (!$this->gameId && session('gameId')) {
            return redirect()->route('game', ['gameId' => session('gameId')]);
        }

        $this->user = User::findOrFail(session('user')->id);

        if (!$this->gameId) {
            $this->startGame();
        }
    }

    public function generateQrCode()
    {
        if (!$this->gameId) {
            return null;
        }
        return QrCode::size(200)->generate(route('game', ['gameId' => $this->gameId]));
    }

    public function startGame()
    {
        $game = ModelsGame::create([
            'game_state' => 'waiting',
            'player_turn' => $this->user->id,
            'host_id' => $this->user->id,
        ]);

        $game->players()->attach($this->user, ['points' => 0]);
        $game->host()->associate($this->user);
        $game->save();

        $this->gameId = $game->id;

        // Save the game ID in the session so we can access it later
        session(['gameId' => $game->id]);

        return redirect()->route('game', ['gameId' => $game->id]);
    }

    public function render()
    {
        return view('livewire.page.game', [
            'qrCode' => $this->generateQrCode(),
        ]);
    }
}
