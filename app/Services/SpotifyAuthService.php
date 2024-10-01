<?php

declare(strict_types=1);

namespace App\Services;


use App\Models\SpotifyUser;
use Illuminate\Support\Facades\Http;

class SpotifyAuthService
{
    const SPOTIFY_TOKEN_URL = 'https://accounts.spotify.com/api/token';

    private string $client_id;
    private string $client_secret;

    public const SCOPES = 'user-read-private user-read-email user-read-playback-state user-modify-playback-state user-read-currently-playing user-library-read';

    public function __construct()
    {
        $this->client_id = config('spotify.client_id');
        $this->client_secret = config('spotify.client_secret');
    }

    public function requestAccessToken(string $code): bool
    {
        $request = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost/spotify/callback',
        ];
        $headers = [
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $response = Http::withHeaders($headers)
            ->asForm()
            ->post(self::SPOTIFY_TOKEN_URL, $request);

        if ($response->failed()) {
            return false;
        }

        $accessToken = $response->json()['access_token'];
        $refreshToken = $response->json()['refresh_token'];

        // save it to session
        session(['spotify_access_token' => $accessToken]);
        session(['spotify_refresh_token' => $refreshToken]);

        $spotifyUserService = new SpotifyUserService();
        $spotifyUserService->createLocalUser($accessToken, $refreshToken);

        return true;
    }
}
