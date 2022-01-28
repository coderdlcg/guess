<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function messages_sync(Request $request)
    {
        MessageSend::dispatch($request->all());
    }
}
