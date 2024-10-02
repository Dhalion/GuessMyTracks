<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'game_state',
        'player_turn',
        'players',
        'host_id',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'game_user')
            ->withPivot('points')
            ->withTimestamps();
    }

    public function playerTurn()
    {
        return $this->belongsTo(User::class,);
    }
}
