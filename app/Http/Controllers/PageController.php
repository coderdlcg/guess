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
        $first_move = $users->max('id');

        $rounds = $game->rounds()->get()->all();

        foreach ($users as $userItem) {
            if ($userItem->id === $user->id) {
                $player1 = $user;
            } else {
                $player2 = $userItem;
            }
        }

        return view('game', compact('game', 'users', 'player1', 'player2', 'first_move', 'rounds'));
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
