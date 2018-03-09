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
     * Type
     *
     * @var
     */
    public $type;

    /**
     * CharactersDiedEvent constructor.
     * @param $guild
     * @param $characters
     * @param $type
     */
    public function __construct($guild, $characters, $type)
    {
        $this->guild = $guild;
        $this->characters = $characters;
        $this->type = $type;
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
