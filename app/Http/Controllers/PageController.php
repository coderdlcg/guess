<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        // dd('p', $user);

        $data = [];
        return view('home', compact('data'));
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
        $first_move = $users->min('id');
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
            'first_move',
            'rounds',
            'winner',
        ]));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        // dd('p', $user);

        $data = [];
        return view('history', compact('data'));
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
