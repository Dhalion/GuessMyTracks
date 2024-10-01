<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'image_url',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'spotify_access_token',
        'spotify_refresh_token',
        'password',
        'remember_token',
    ];


    public function getSpotifyAccessTokenAttribute(): string
    {
        return Crypt::decryptString($this->attributes['spotify_access_token']);
    }

    public function setSpotifyAccessTokenAttribute(string $value): void
    {
        $this->attributes['spotify_access_token'] = Crypt::encryptString($value);
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class);
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
