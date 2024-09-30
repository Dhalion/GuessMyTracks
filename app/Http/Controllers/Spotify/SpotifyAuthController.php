<?php

namespace App\Http\Controllers\Spotify;

use App\Http\Controllers\Controller;
use App\Services\SpotifyAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpotifyAuthController extends Controller
{

    const SPOTIFY_AUTH_URL = 'https://accounts.spotify.com/authorize';

    private string $client_id;
    private string $client_secret;
    private SpotifyAuthService $spotifyAuthService;

    public function __construct()
    {
        $this->client_id = config('spotify.client_id');
        $this->client_secret = config('spotify.client_secret');
        $this->spotifyAuthService = new SpotifyAuthService();
    }

    public function authorize()
    {
        $scopes = 'user-read-private user-read-email user-read-playback-state user-modify-playback-state user-read-currently-playing';

        $csrf = Str::random(40);

        $request = [
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'scope' => $scopes,
            'redirect_uri' => route('spotify.callback'),
            'state' => $csrf,
        ];

        $query = http_build_query($request);

        // Send the user to the Spotify authorization page to perform OAuth
        return redirect(self::SPOTIFY_AUTH_URL . '?' . $query);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $error = $request->query('error');
        $state = $request->query('state');

        if ($error) {
            return redirect()->route('home')->with('error', 'You did not authorize the app. Error: ' . $error);
        }

        if (!$code) {
            return redirect()->route('home')->with('error', 'No code was returned from Spotify.');
        }

        //TODO Validate the state parameter to prevent CSRF attacks

        $success = $this->spotifyAuthService->requestAccessToken($code);

        if (!$success) {
            return redirect()->route('home')->with('error', 'Failed to get access token from Spotify.');
        }

        return redirect()->route('home')->with('success', 'Successfully authorized with Spotify.');
    }
}
