<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class SpotifyUserService
{
    const SPOTIFY_USER_URL = 'https://api.spotify.com/v1/me';

    public function getSpotifyUser(string $accessToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get(self::SPOTIFY_USER_URL);

        if ($response->failed()) {
            return [];
        }

        $body = $response->json();

        return $body ?? [];
    }

    public function createLocalUser(string $accessToken, string $refreshToken): void
    {
        $body = $this->getSpotifyUser($accessToken);

        if (empty($body)) {
            return;
        }

        $existingUser = User::where('username', $body['id'])->first();

        if ($existingUser) {
            $existingUser->spotify_access_token = $accessToken;
            $existingUser->spotify_refresh_token = $refreshToken;
            $existingUser->saveOrFail();
            return;
        }

        $user = new User();
        $user->name = $body['display_name'];
        $user->username = $body['id'];
        $user->email = $body['email'];
        $user->image_url = $body['images'][0]['url'];
        $user->spotify_access_token = $accessToken;
        $user->spotify_refresh_token = $refreshToken;
        $user->saveOrFail();

        // save the user to the session
        session(['user' => $user]);
    }
}
