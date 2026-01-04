<?php

declare(strict_types=1);

namespace App\Services\Spotify;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SpotifyUserService
{
    public function __construct(
        private readonly SpotifyApiService $apiService
    ) {}

    /**
     * Login or create a local user from Spotify tokens.
     */
    public function loginOrRegister(array $tokens): User
    {
        // Temporarily set tokens on a dummy user to fetch profile
        $tempUser = new User([
            'spotify_access_token' => $tokens['access_token'],
            'spotify_refresh_token' => $tokens['refresh_token'],
            'spotify_token_expires_at' => now()->addSeconds($tokens['expires_in']),
        ]);

        $profile = $this->apiService->forUser($tempUser)->get(config('spotify.api.endpoints.user'));

        $user = User::updateOrCreate(
            ['spotify_id' => $profile['id']],
            [
                'name' => $profile['display_name'] ?? $profile['id'],
                'email' => $profile['email'],
                'spotify_access_token' => $tokens['access_token'],
                'spotify_refresh_token' => $tokens['refresh_token'],
                'spotify_token_expires_at' => now()->addSeconds($tokens['expires_in']),
            ]
        );

        Auth::login($user);

        return $user;
    }
}
