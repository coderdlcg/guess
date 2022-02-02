<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Game extends Model
{
    use HasFactory;

    protected $numberOfRounds = 5;
    protected $minNumber = 1;
    protected $maxNumber = 20;

    protected $fillable = [
        'name',
        'status'
    ];

    const STATUS = [
        'connecting_players' => 2, // подключение игроков
        'game_started'       => 1, // игра начата
        'game_over'          => 0  // игра окончена
    ];

    const ROLES = [
        'player_1' => 1,
        'player_2' => 2,
        'none'     => 8
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function rounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function newRound($round_id, $data, $player_1)
    {
        $player_1_data = [
            "user_id" => $player_1->id,
            "number" => $data['body']
        ];

        $round = $this->rounds()->create([
            'round_id'    => $round_id,
            'game_id'     => $this->id,
            'player_1'     => json_encode($player_1_data)
        ]);

        Log::channel('daily')->log('info', 'Game newRound()', [$round]);

        return $round;
    }

    public function updateRound($round, $data, $player_2)
    {
        $player_2_data = [
            "user_id" => $player_2->id,
            "number" => $data['body']
        ];

        $round->player_2 = json_encode($player_2_data);
        $round->save();

        Log::channel('daily')->log('info', 'Game updateRound()', [$round]);

        return $round;
    }

    public function completeRound($round)
    {
        $round->guess_number = rand($this->minNumber, $this->maxNumber);
        $round->save();

        $number_player_1 = json_decode($round->player_1);
        $number_player_2 = json_decode($round->player_2);

        $diff_player_1 = abs($round->guess_number - intval($number_player_1->number));
        $diff_player_2 = abs($round->guess_number - intval($number_player_2->number));

        $none = abs($diff_player_1 - $diff_player_2);

        if ($none === 0) {
            $round->winner = self::ROLES['none'];
            $round->save();
        } else {
            $favorit = min($diff_player_1, $diff_player_2);

            switch ($favorit) {
                case $diff_player_1:
                    $round->winner = self::ROLES['player_1'];
                    $round->save();
                    break;

                case $diff_player_2:
                    $round->winner = self::ROLES['player_2'];
                    $round->save();
                    break;
            }
        }

        $this->current_round++;
        $this->save();

        if ($this->current_round > $this->numberOfRounds) {
            // конец игры
            $this->gameOver();
        }

        return $round;
    }

    public function gameOver()
    {
        $rounds = $this->rounds()->select('winner')->get()->pluck('winner')->toArray();
        $count_win_player_1 = 0;
        $count_win_player_2 = 0;

        foreach ($rounds as $user) {
            if ($user === Game::ROLES['player_1']) {
                $count_win_player_1++;
            }
            if ($user === Game::ROLES['player_2']) {
                $count_win_player_2++;
            }
        }

        if ($count_win_player_1 > $count_win_player_2) {
            $this->winner = Game::ROLES['player_1'];
        }

        if ($count_win_player_1 < $count_win_player_2) {
            $this->winner = Game::ROLES['player_2'];
        }

        if ($count_win_player_1 === $count_win_player_2) {
            $this->winner = Game::ROLES['none'];
        }

        $this->status = Game::STATUS['game_over'];
        $this->save();

        $player_winner = $this->users()->where('role', $this->winner)->first();
        Log::channel('daily')->log('info', 'Game processing()', ['player_winner' =>$player_winner]);

        return $player_winner;
    }

    public function processing($data)
    {
        if ($this->status === Game::STATUS['game_over']) {
            return ;
        }

        Log::channel('daily')->log('info', 'Game processing()', [$data]);

        $player_1 = $this->users()->where('role', Game::ROLES['player_1'])->first();
        $player_2 = $this->users()->where('role', Game::ROLES['player_2'])->first();

        $round = $this->rounds()->where('round_id', $this->current_round)->first();

        if ($player_1->id === $data['user_id']) {
            // ход игрока $player_1
            if (!$round) {
                return $this->newRound($this->current_round, $data, $player_1);
            } else {
                return $round;
            }
        }

        if ($round && $player_2->id === $data['user_id']) {
            $round = $this->updateRound($round, $data, $player_2);
        }

        if ($round && $round->player_1 && $round->player_2) {
            return $this->completeRound($round);
        }
    }
}
