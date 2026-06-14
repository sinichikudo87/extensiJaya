<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $title;
    public $message;
    public $cost;
    public $time;

    public function __construct($title, $message, $cost = null, $time = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->cost = $cost;
        $this->time = $time ?? now()->format('H:i:s');
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'new.notification';
    }
}