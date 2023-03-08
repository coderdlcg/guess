<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        return view('home', compact('user'));
    }

    public function find()
    {
        $auth_user = Auth::user();
        if (!$auth_user) {
            return redirect(route('login'));
        }

        return view('find', compact('auth_user'));
    }

    public function game(Game $game)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $users = $game->users()->select('id', 'name')->get();
        $player_1 = $game->users()->where('role', Game::ROLES['player_1'])->first();
        $player_2 = $game->users()->where('role', Game::ROLES['player_2'])->first();
        $rounds = $game->rounds()->get()->all();
        $winner = 0;

        foreach ($users as $userItem) {
            if ($userItem->id === $user->id) {
                $left_player = $user;
            } else {
                $right_player = $userItem;
            }
        }

        if ($game->status === Game::STATUS['game_over']) {
            $winner = $game->whoIsWinner();
        }

        return view('game', compact([
            'game',
            'users',
            'left_player',
            'right_player',
            'player_1',
            'player_2',
            'rounds',
            'winner',
        ]));
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $myGamesId = DB::table('game_user')
            ->where('user_id', '=', $user->id)
            ->get()
            ->pluck('game_id')
            ->toArray();

        $games = DB::table('games')
            ->whereIn('games.id', $myGamesId)
            ->join('game_user', 'games.id', '=', 'game_user.game_id')
            ->where('user_id', '!=', $user->id)
            ->join('users', 'users.id', '=', 'game_user.user_id')
            ->select('games.id as game_id', 'winner', 'role', 'users.name as opponent_name', 'games.updated_at as date')
            ->orderByDesc('date')
            ->paginate(10);

        return view('history', compact('user', 'games'));
    }
}
