<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\SpotifyTrackService;
use Livewire\Component;

class UserOverview extends Component
{
    private SpotifyTrackService $spotifyTrackService;
    public User $user;

    public function __construct()
    {
        $this->spotifyTrackService = new SpotifyTrackService();
    }

    public function mount()
    {
        $this->user = User::firstOrFail();
    }

    public function fetchTracks(): void
    {
        $this->spotifyTrackService->getAllUsersTracks($this->user);
    }

    public function deleteTracks(): void
    {
        $this->user->tracks()->detach();
    }

    public function render()
    {
        return view('livewire.user-overview', [
            'user' => $this->user
        ]);
    }
}
