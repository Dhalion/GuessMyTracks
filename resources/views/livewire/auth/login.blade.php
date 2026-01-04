<div class="flex justify-center items-center min-h-screen bg-base-200">
    <div class="w-96 flex flex-col gap-4">
        @if(session('error'))
            <x-alert icon="o-exclamation-triangle" class="alert-error">
                {{ session('error') }}
            </x-alert>
        @endif

        <x-card title="Login to GuessMyTracks" class="shadow-xl">
            <div class="flex flex-col gap-6">
                <p class="text-center text-base-content/70">
                    Connect your Spotify account to start guessing tracks and challenging your friends.
                </p>
                
                <x-button 
                    label="Continue with Spotify" 
                    icon="o-musical-note" 
                    class="btn-primary w-full" 
                    link="{{ route('spotify.authorize') }}" 
                    external
                />
            </div>
        </x-card>
    </div>
</div>
