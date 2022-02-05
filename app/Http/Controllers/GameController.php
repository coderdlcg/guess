<?php

namespace App\Http\Controllers;

use App\Events\GameOver;
use App\Events\MessageSend;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    const SETTINGS_GAME = [
        'time_wait' => 60,
        'step_check' => 1
    ];

    protected $game;
    protected $find;
    protected $cancel;


    public function processing(Request $request)
    {
        $message = $request->all();

        if ($message['body']) {
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

    public function find_game(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $this->find = isset($request->post()['find']);

        info("find_game()", ['find' => $this->find, 'user' => $user]);

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

            return redirect('/game/'.$this->game->id);
        }

        if (!$this->game && !$exist_user_game) {
            // если нет игр ожидающих игрока и нет игры созданной текущим игроком, тогда создаем новую и ждем присоединения
            $this->game = Game::create([
                'name' =>$user->name.' VS ',
                'status' => Game::STATUS['connecting_players']
            ]);
            // прикрепляем текущего польователя к игре (даем права доступа к созданной игре)
            $this->game->users()->attach($user->id, ['role' => Game::ROLES['player_1'] ]);

            Log::channel('daily')->log('info', "user {$user->name} создал игру и ождает соперника {$this->game->name}", [$this->game, $user]);
        }

        $time_wait = self::SETTINGS_GAME['time_wait'];
        while ($time_wait && !$this->cancel) {
            $this->game = Game::find($this->game->id);
            $exist_user_game = $this->game->users()->where('user_id', $user->id)->get()->count();

            if ($this->game->status == Game::STATUS['game_started'] && $exist_user_game > 0) {
                Log::channel('daily')->log('info', "user {$user->name} дождался соперника, игра {$this->game->name} началась", [$this->game, $user]);
                Log::channel('daily')->log('info', "-------------------------------------------------------");

                return redirect('/game/' . $this->game->id);
            }

            Log::channel('daily')->log('info', "user {$user->name} ожидает соперника", [$this->game, $user]);

            $time_wait--;
            sleep(self::SETTINGS_GAME['step_check']);
        }

        if ($this->cancel || $time_wait === 0) {
            if ($this->game) {
                $exist_user_game = $this->game->users()->where('user_id', $user->id)->get()->count();
                if ($exist_user_game > 0) {
                    $this->game->users()->detach($user->id);
                    $this->game->delete();

                    Log::channel('daily')->log('info', "для user {$user->name} не найден соперник, игра удалена", [$this->game, $user]);
                }
            }

            Log::channel('daily')->log('info', "-------------------------------------------------------");
        }

        return redirect(route('home'));
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $this->cancel = isset($request->post()['cancel']);

        info("cancel()", ['cancel' => $this->cancel, 'user' => $user]);
        Log::channel('daily')->log('info', "user {$user->name} отменил игру", ['cancel' => $this->cancel, $user]);

        return redirect(route('home'));
    }
}
