<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SpotifyTrackService;
use Illuminate\View\View;

class UserOverviewController extends Controller
{
    private SpotifyTrackService $spotifyTrackService;

    public function __construct()
    {
        $this->spotifyTrackService = new SpotifyTrackService();
    }
    public function show(): View
    {
        $user = User::first();

        $tracks = $this->spotifyTrackService->getUsersTracks($user);

        return view('user', [
            'user' => $user,
            'tracks' => $tracks,
        ]);
    }
}
