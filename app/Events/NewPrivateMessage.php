<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NewPrivateMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $senderId;
    private $roomId;
    private $receiverId; //we don't need to send this to the user
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($senderId,$roomId,$receiverId,$message)
    {
        $this->senderId = $senderId;
        $this->roomId = $roomId;
        $this->receiverId = $receiverId;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.".$this->roomId.".".$this->receiverId);
    }
}
