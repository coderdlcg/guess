<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $message = $request->input('message', '');

        if (strlen($message)) {
            event(new MessageSend($message));
        }
    }
}
