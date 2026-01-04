<?php

declare(strict_types=1);

namespace App\Services\Spotify;

use App\Exceptions\SpotifyException;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotifyApiService
{
    private string $baseUrl;
    private ?User $user = null;

    public function __construct(
        private readonly SpotifyAuthService $authService
    ) {
        $this->baseUrl = config('spotify.api.base_url');
    }

    /**
     * Set the user for the API requests.
     */
    public function forUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Perform a GET request.
     *
     * @throws SpotifyException
     */
    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    /**
     * Perform a POST request.
     *
     * @throws SpotifyException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Perform a PUT request.
     *
     * @throws SpotifyException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Perform a DELETE request.
     *
     * @throws SpotifyException
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Send the request to Spotify API.
     *
     * @throws SpotifyException
     */
    protected function request(string $method, string $endpoint, array $options = [], bool $retry = true): array
    {
        $url = $this->buildUrl($endpoint);
        $request = $this->buildRequest();

        try {
            $response = $request->send($method, $url, $options);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            if ($response->status() === 401 && $retry) {
                $user = $this->user ?? auth()->user();
                if ($user instanceof User) {
                    $this->authService->refreshToken($user);
                    return $this->request($method, $endpoint, $options, false);
                }
            }

            $this->handleErrorResponse($response, $endpoint);
        } catch (\Exception $e) {
            if ($e instanceof SpotifyException) {
                throw $e;
            }

            Log::error('Spotify API exception', [
                'message' => $e->getMessage(),
                'endpoint' => $endpoint,
            ]);

            throw SpotifyException::apiError($e->getMessage());
        }

        return [];
    }

    protected function buildUrl(string $endpoint): string
    {
        if (str_starts_with($endpoint, 'http')) {
            return $endpoint;
        }

        $endpoint = ltrim($endpoint, '/');
        return "{$this->baseUrl}/{$endpoint}";
    }

    /**
     * @throws SpotifyException
     */
    protected function buildRequest(): PendingRequest
    {
        $user = $this->user ?? auth()->user();

        if (!$user instanceof User) {
            throw new \RuntimeException('User must be set or authenticated for Spotify API requests');
        }

        $token = $user->spotify_access_token;

        if (!$token || ($user->spotify_token_expires_at && $user->spotify_token_expires_at->isPast())) {
            $token = $this->authService->refreshToken($user);
        }

        return Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
    }

    /**
     * @throws SpotifyException
     */
    protected function handleErrorResponse(Response $response, string $endpoint): void
    {
        Log::error('Spotify API error', [
            'status' => $response->status(),
            'body' => $response->body(),
            'endpoint' => $endpoint,
        ]);

        $message = $response->json()['error']['message'] ?? $response->body();
        throw SpotifyException::apiError($message, $response->status());
    }
}
