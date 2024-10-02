<div>
    <h1>Logged In User</h1>

    <p>Logged in as: {{ $user->name }}</p>

    <p>Email: {{ $user->email }}</p>

    <img src="{{ $user->image_url }}" alt="User Avatar">

    <div>
        <button wire:click="fetchTracks">Fetch Tracks</button>
        <button wire:click="deleteTracks">Delete all Tracks</button>
    </div>

    <p>
    <ul id="tracks">
        @foreach ($user->tracks as $track)
            <li>{{ $track->spotify_id }} - {{ $track->name }}</li>
        @endforeach
    </ul>

    </p>
</div>
