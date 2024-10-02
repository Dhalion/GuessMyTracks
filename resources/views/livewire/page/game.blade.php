<div>
    @php
        /** @var \App\Models\Game $game */
    @endphp
    @if ($game && $game->game_state === 'waiting')
        @include('livewire.component.game-lobby', ['game' => $game])
    @endif

    <pre>
        {{ json_encode($game, JSON_PRETTY_PRINT) }}
    </pre>
</div>
