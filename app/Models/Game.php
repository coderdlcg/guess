<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    const STATUS = [
        'connecting_players' => 2, // подключение игроков
        'game_started'       => 1, // игра начата
        'game_over'          => 0  // игра окончена
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
