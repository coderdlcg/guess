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

    public function game(Request $request,  Game $game)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        return view('game', compact('game'));
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
