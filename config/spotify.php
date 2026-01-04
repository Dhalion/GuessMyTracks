<?php

return [
    'client_id' => env('SPOTIFY_CLIENT_ID'),
    'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    'redirect_uri' => env('SPOTIFY_REDIRECT_URI', 'http://localhost/spotify/callback'),
    'scopes' => [
        'user-read-private',
        'user-read-email',
        'user-read-playback-state',
        'user-modify-playback-state',
        'user-read-currently-playing',
        'user-library-read',
    ],
    'api' => [
        'base_url' => 'https://api.spotify.com/v1',
        'endpoints' => [
            'auth' => 'https://accounts.spotify.com/api/token',
            'authorize' => 'https://accounts.spotify.com/authorize',
            'user' => '/me',
            'tracks' => '/me/tracks',
        ]
    ]
];