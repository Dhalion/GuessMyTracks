<div class="p-10">
    <x-header title="Dashboard" separator />

    @if($user)
        <div class="grid gap-5 lg:grid-cols-2">
            <x-card title="My Profile" separator shadow>
                <div class="flex items-center gap-4">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-16">
                            <span class="text-xl">{{ $user->initials() }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xl font-bold">{{ $user->name }}</div>
                        <div class="text-sm opacity-70">{{ $user->email }}</div>
                        <div class="text-xs opacity-50 mt-1">Spotify ID: {{ $user->spotify_id }}</div>
                    </div>
                </div>
                
                <x-slot:actions>
                    <x-button label="Logout" icon="o-power" class="btn-error btn-sm" link="/logout" />
                </x-slot:actions>
            </x-card>

            <x-card title="Spotify Status" separator shadow>
                <div class="stats shadow w-full">
                    <div class="stat">
                        <div class="stat-title">Token Status</div>
                        <div class="stat-value text-success text-lg">Active</div>
                        <div class="stat-desc">Expires: {{ $user->spotify_token_expires_at?->diffForHumans() }}</div>
                    </div>
                </div>
            </x-card>
        </div>
    @else
        <x-alert icon="o-exclamation-triangle" class="alert-warning">
            You are not logged in.
            <x-slot:actions>
                <x-button label="Login" link="/login" />
            </x-slot:actions>
        </x-alert>
    @endif
</div>
