<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        // Broadcast on a private channel scoped to the booking id
        return new PrivateChannel('booking.' . $this->message->booking_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'booking_id' => $this->message->booking_id,
            'sender_id' => $this->message->sender_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
            ],
            'body' => $this->message->body,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
