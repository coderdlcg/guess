<?php

namespace App\Http\Controllers;

use App\Events\FindGame;
use App\Events\GameOver;
use App\Events\MessageSend;
use App\Events\StartGame;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{

    protected $game;
    protected $find;
    protected $cancel;


    public function processing(Request $request)
    {
        $message = $request->all();
        $user = Auth::user();

        if ($message['body'] === 'leave' && $user->id === $message['user_id']) {
            $game = User::findOrFail($user->id)->games()->where('game_id', $message['game_id'])->first();

            if ($game && $game->status !== Game::STATUS['game_over']) {
                $data = [
                    'game_id' => $game->id,
                    'winner'  => $game->leaveGame($user)
                ];
                GameOver::dispatch($data);
            }
        }

        if ($message['body'] && $message['body'] !== 'leave') {
            $game = Game::find($message['game_id']);
            $round = $game->processing($message);

            Log::channel('daily')->log('info', 'GameController processing(). current_round: '.$game->current_round, [$message, $round]);

            MessageSend::dispatch($message, $round);

            if ($game->status === Game::STATUS['game_over']) {
                $data = [
                    'game_id' => $game->id,
                    'winner'  => $game->whoIsWinner()
                ];
                GameOver::dispatch($data);
            }

            Log::channel('daily')->log('info', '-------------------------------------------------------');
        }
    }

    public function findGame(Request $request)
    {
        $data = $request->all();

        if ($data['body'] === 'find') {
            event(new FindGame($data));
        }
    }

    public function newGame(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if ($data['body'] === 'create' && count($data['users']) === 2) {
            Log::channel('daily')->log('info', "Создание игры", [$data, $user->id]);

            foreach ($data['users'] as $user) {
                $user = User::find($user['id']);

                if (!$this->game) {
                    $this->game = Game::where('status', Game::STATUS['connecting_players'])->first();
                }

                $exist_user_game = null;
                if ($this->game) {
                    // проверяем есть ли у тек пользователя уже созданная игра
                    $exist_user_game = $this->game->users()->where('user_id', $user->id)->get()->count();
                }

                if ($exist_user_game === 0) {
                    // присоединяемся к другому игроку
                    $this->game->name = $this->game->name . $user->name;
                    $this->game->status = Game::STATUS['game_started'];
                    $this->game->users()->attach($user->id, ['role' => Game::ROLES['player_2'] ]);
                    $this->game->save();

                    Log::channel('daily')->log('info', "user {$user->name} подключился к игре, {$this->game->name}", [$this->game, $user]);
                }

                if (!$this->game && !$exist_user_game) {
                    // если нет игр ожидающих игрока и нет игры созданной текущим игроком, тогда создаем новую и ждем присоединения
                    $this->game = Game::create([
                        'name' => $user->name.' VS ',
                        'status' => Game::STATUS['connecting_players']
                    ]);
                    // прикрепляем текущего польователя к игре (даем права доступа к созданной игре)
                    $this->game->users()->attach($user->id, ['role' => Game::ROLES['player_1'] ]);

                    Log::channel('daily')->log('info', "user {$user->name} создал игру ({$this->game->name}) и ожидает соперника ", [$this->game, $user]);
                }
            }

            $data = [
                'body'    => 'redirect',
                'user'    => $user,
                'game_id' => $this->game->id
            ];

            event(new StartGame($data));
        }
    }
}
