<?php

declare(strict_types=1);

namespace App\Services\Spotify;

use App\Exceptions\SpotifyException;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SpotifyAuthService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('spotify.client_id');
        $this->clientSecret = config('spotify.client_secret');
        $this->redirectUri = config('spotify.redirect_uri');
    }

    /**
     * Generate the authorization URL for Spotify.
     */
    public function getAuthorizationUrl(): string
    {
        $state = Str::random(40);
        Session::put('spotify_auth_state', $state);

        $query = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(' ', config('spotify.scopes')),
            'state' => $state,
            'show_dialog' => 'true',
        ]);

        return config('spotify.api.endpoints.authorize') . '?' . $query;
    }

    /**
     * Handle the callback from Spotify and exchange the code for tokens.
     *
     * @throws SpotifyException
     */
    public function handleCallback(string $code, string $state): array
    {
        $this->validateState($state);

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ])
            ->post(config('spotify.api.endpoints.auth'), [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
            ]);

        if ($response->failed()) {
            throw SpotifyException::authenticationFailed($response->body());
        }

        return $response->json();
    }

    /**
     * Refresh the access token for a user.
     *
     * @throws SpotifyException
     */
    public function refreshToken(User $user): string
    {
        if (!$user->spotify_refresh_token) {
            throw SpotifyException::authenticationFailed('No refresh token available for user.');
        }

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ])
            ->post(config('spotify.api.endpoints.auth'), [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->spotify_refresh_token,
            ]);

        if ($response->failed()) {
            throw SpotifyException::authenticationFailed($response->body());
        }

        $data = $response->json();

        $user->update([
            'spotify_access_token' => $data['access_token'],
            'spotify_refresh_token' => $data['refresh_token'] ?? $user->spotify_refresh_token,
            'spotify_token_expires_at' => now()->addSeconds($data['expires_in']),
        ]);

        return $data['access_token'];
    }

    /**
     * Validate the state parameter to prevent CSRF.
     *
     * @throws SpotifyException
     */
    private function validateState(string $state): void
    {
        $savedState = Session::pull('spotify_auth_state');

        if (!$savedState || $savedState !== $state) {
            throw SpotifyException::invalidState();
        }
    }
}
