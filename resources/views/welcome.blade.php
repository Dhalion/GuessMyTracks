<html>

<head>
    <title>GuessMyTracks</title>
</head>

<body>
    <h1>Welcome to GuessMyTracks</h1>

    <p>GuessMyTracks is a game where you have to guess the name of the song based on a short preview of the song.</p>

    <p>Click the button below to start playing!</p>

    <a href="{{ route('spotify.authorize') }}">Start playing</a>

    <div>
        <h2>Credentials:</h2>
        @php
            $session = session();
        @endphp
        {{-- show accesstoken from session --}}
        <p>Access Token: {{ $session->get('spotify_access_token') }}</p>
        {{-- show refreshtoken from session --}}
        <p>Refresh Token: {{ $session->get('spotify_refresh_token') }}</p>
    </div>

    <a href="{{ route('user.overview') }}">Go To User</a>
</body>

</html>
