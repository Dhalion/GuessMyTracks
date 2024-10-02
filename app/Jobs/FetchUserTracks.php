<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SpotifyTrackService;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchUserTracks implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public int $offset = 0,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new SpotifyTrackService();
        $service->fetchAndStoreUsersTracks($this->user, $this->offset);
    }
}
