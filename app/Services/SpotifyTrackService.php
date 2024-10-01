<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class SpotifyTrackService
{

    const SPOTIFY_API_URL = 'https://api.spotify.com/v1/me/tracks';

    public function getUsersTracks(User $user)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->spotify_access_token,
        ])->get(self::SPOTIFY_API_URL);

        $body = $response->json();

        if ($response->failed()) {
            return [];
        }


        return $body['items'] ?? [];
    }
}
