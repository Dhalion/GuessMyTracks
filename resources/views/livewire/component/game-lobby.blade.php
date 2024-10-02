<div>
    @php
        /** @var \App\Models\Game $game */
    @endphp
    <h2>
        Game Lobby
    </h2>
    <p>
    <h3>
        Players:
    </h3>
    <ul>
        @foreach ($game->players as $player)
            @livewire('component.lobby-player-card', ['player' => $player], key($player->id))
        @endforeach
    </ul>
    </p>
    <span wire:poll>
        {{ $totalLoadedTracks }} / {{ $totalTracks }} ({{ $percentageLoaded }}%) Tracks loaded
    </span>
    <button wire:click="startGame" :disabled="{{ $percentageLoaded < 50 }}">Start Game</button>
</div>
