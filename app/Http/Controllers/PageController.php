<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        return view('home');
    }

    public function find(Request $request)
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

        $games = DB::table('games')
            ->join('game_user', 'games.id', '=', 'game_user.game_id')
            ->join('users', 'users.id', '=', 'game_user.user_id')
            ->where('user_id', '!=',$user->id)
            ->select('games.id as game_id', 'winner', 'role', 'users.name as opponent_name', 'games.updated_at as date')
            ->orderByDesc('date')
            ->paginate(10);

        return view('history', compact('user', 'games'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
