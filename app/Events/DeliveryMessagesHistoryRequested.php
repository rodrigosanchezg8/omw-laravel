<?php

namespace App\Events;

use App\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeliveryMessagesHistoryRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $delivery;
    public $start_message_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Delivery $delivery, $start_message_id)
    {
        $this->delivery = $delivery;
        $this->start_message_id = $start_message_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('delivery:' . $this->delivery->id);
    }

    public function broadcastWith()
    {
        return [
            'delivery' => $this->delivery->load(['messages' => function ($query) {
                return $query->where('id', '>=', $this->start_message_id)->orderBy('id', 'asc');
            }])
        ];
    }

}
