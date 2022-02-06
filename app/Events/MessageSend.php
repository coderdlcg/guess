<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $round;

    public function __construct($message, $round)
    {
        $this->message = $message;
        $this->round = $round;

        Log::channel('daily')->log('info', 'Event MessageSend', [$this->message, $this->round]);
        //$this->dontBroadcastToCurrentUser();
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('game.' . $this->message['game_id']);
    }
}
