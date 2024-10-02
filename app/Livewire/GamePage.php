<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GamePage extends Component
{
    public ?string $gameId;
    public ?User $user;

    public Game $game;

    public function mount(?string $gameId = null)
    {
        $this->gameId = $gameId ?? null;

        // redirect to old game if game id is not provided
        if (!$this->gameId && session('gameId')) {
            return redirect()->route('game', ['gameId' => session('gameId')]);
        }

        // Get the user from the session
        $this->user = User::findOrFail(session('user')->id);

        // Start new game
        if (!$this->gameId) {
            $this->startGame();
        }

        // Get the game from the database
        $this->game = Game::with(
            [
                'players' => function ($query) {
                    $query->withCount('tracks');
                },
                'host',
                'playerTurn'
            ]
        )->findOrFail($this->gameId);
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
        $this->game = Game::create([
            'game_state' => 'waiting',
            'player_turn' => $this->user->id,
            'host_id' => $this->user->id,
        ]);

        $this->game->players()->attach($this->user, ['points' => 0]);
        $this->game->host()->associate($this->user);
        $this->game->save();


        // Save the game ID in the session so we can access it later
        session(['gameId' => $this->game->id]);

        return redirect()->route('game', ['gameId' => $this->game->id]);
    }

    private function getLoadedTrackCount()
    {
        $trackCount = 0;
        foreach ($this->game->players as $player) {
            $player->loadCount('tracks');
            $trackCount += $player->tracks_count;
        }
        return $trackCount;
    }

    private function getTotalTrackCount()
    {
        $trackCount = 0;
        foreach ($this->game->players as $player) {
            /** @var User $player */
            $trackCount += $player->total_tracks;
        }
        return $trackCount;
    }

    public function render()
    {
        return view('livewire.page.game', [
            'qrCode' => $this->generateQrCode(),
            'totalLoadedTracks' => $this->getLoadedTrackCount(),
            'totalTracks' => $this->getTotalTrackCount(),
            'percentageLoaded' => round($this->getLoadedTrackCount() / $this->getTotalTrackCount() * 100),
        ]);
    }
}
