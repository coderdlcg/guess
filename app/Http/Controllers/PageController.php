<?php

namespace App\Http\Controllers;

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

    public function game(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        // dd('p', $user);

        $data = [];
        return view('game', compact('data'));
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
