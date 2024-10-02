<li wire:poll>
    @php
        /** @var \App\Models\User $player */
    @endphp
    {{ $player->name }} ({{ $player->tracks_count }} / {{ $player->total_tracks }} Tracks loaded)
</li>
