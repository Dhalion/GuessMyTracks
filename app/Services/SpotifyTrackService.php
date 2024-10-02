<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\FetchUserTracks;
use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotifyTrackService
{

    const SPOTIFY_API_URL = 'https://api.spotify.com/v1/me/tracks';

    const BATCH_SIZE = 50;

    public function getAllUsersTracks(User $user): void
    {
        // Fetch the first 50 tracks, and read the total number of tracks
        $tracks = $this->requestUsersTracks($user);
        $totalTracks = $tracks['total'];

        User::find($user->id)->update(['total_tracks' => $totalTracks]);

        // Check if the user has total track count
        $userTrackCount = $user->tracks()->count();
        if ($userTrackCount === $totalTracks) {
            Log::info("User {$user->name} already has all tracks stored");
            return;
        }

        // Divide total number of tracks to dispatch jobs in batches
        $batches = ceil($totalTracks / self::BATCH_SIZE);

        // Dispatch jobs to fetch the rest of the tracks
        for ($i = 1; $i < $batches; $i++) {
            $offset = $i * self::BATCH_SIZE;
            FetchUserTracks::dispatch($user, $offset, self::BATCH_SIZE);
        }
    }


    public function requestUsersTracks(User $user, int $offset = 0, int $limit = 50): array
    {
        $query = http_build_query([
            'offset' => $offset,
            'limit' => $limit,
        ]);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->spotify_access_token,
        ])->get(self::SPOTIFY_API_URL, $query);

        $body = $response->json();

        if ($response->failed()) {
            return [];
        }

        $tracks = $body['items'] ?? [];
        $totalItems = $body['total'] ?? null;
        return [
            'tracks' => $tracks,
            'total' => $totalItems,
        ];
    }

    public function fetchAndStoreUsersTracks(User $user, int $offset = 0, int $limit = 50): void
    {
        Log::info("Fetching tracks for user {$user->name}");
        $tracks = $this->requestUsersTracks($user, $offset, $limit)['tracks'] ?? [];

        foreach ($tracks as $track) {
            // Store the track in the database
            $trackReleaseDate = $track['track']['album']['release_date'];
            $releasePrecision = $track['track']['album']['release_date_precision'];

            $newTrack = Track::updateOrCreate(
                ['spotify_id' => $track['track']['id']],
                [
                    'name' => $track['track']['name'],
                    'artist' => implode(',', array_map(function ($artist) {
                        return $artist['name'];
                    }, $track['track']['artists'])),
                    'album' => $track['track']['album']['name'],
                    'image_url' => $track['track']['album']['images'][0]['url'] ?? null,
                    'spotify_uri' => $track['track']['uri'],
                    'release_date' => $this->parseDate($trackReleaseDate, $releasePrecision),
                ]
            );
            $newTrack->users()->syncWithoutDetaching([$user->id]);

            // Log
            Log::info("Stored track {$newTrack->name} for user {$user->name}");
        }
    }

    private function parseDate(string $date, string $precision): string
    {
        if ($precision === 'year') {
            return $date . '-01-01';
        }

        if ($precision === 'month') {
            return $date . '-01';
        }

        return $date;
    }
}
