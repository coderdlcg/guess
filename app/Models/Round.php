<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id', 'game_id', 'guess_number', 'player_1', 'player_2', 'winner'
    ];

    public function game(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
