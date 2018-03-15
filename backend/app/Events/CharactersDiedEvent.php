<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CharactersDiedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Guild
     *
     * @var
     */
    public $guild;

    /**
     * Characters
     *
     * @var
     */
    public $characters;

    /**
     * CharactersDiedEvent constructor.
     * @param $guild
     * @param $characters
     */
    public function __construct($guild, $characters)
    {
        $this->guild = $guild;
        $this->characters = $characters;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('discord');
    }
}
