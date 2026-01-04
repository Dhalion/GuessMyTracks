<?php

declare(strict_types=1);

namespace App\Http\Controllers\Spotify;

use App\Exceptions\SpotifyException;
use App\Http\Controllers\Controller;
use App\Services\Spotify\SpotifyAuthService;
use App\Services\Spotify\SpotifyUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpotifyAuthController extends Controller
{
    public function __construct(
        private readonly SpotifyAuthService $authService,
        private readonly SpotifyUserService $userService
    ) {}

    /**
     * Redirect the user to the Spotify authorization page.
     */
    public function authorize(): RedirectResponse
    {
        return redirect()->away($this->authService->getAuthorizationUrl());
    }

    /**
     * Handle the callback from Spotify.
     */
    public function callback(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $error = $request->query('error');

        if ($error) {
            return redirect()->route('login')->with('error', "Spotify access denied: {$error}");
        }

        if (!$code || !$state) {
            return redirect()->route('login')->with('error', 'Invalid request from Spotify.');
        }

        try {
            $tokens = $this->authService->handleCallback($code, $state);
            $this->userService->loginOrRegister($tokens);

            return redirect()->route('page.main')->with('success', 'Successfully connected with Spotify!');
        } catch (SpotifyException $e) {
            Log::error('Spotify authentication error', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Unexpected error during Spotify authentication', ['exception' => $e]);
            return redirect()->route('login')->with('error', 'An unexpected error occurred.');
        }
    }
}
