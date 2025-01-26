<?php

namespace App\Events;

use App\Models\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisitVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new event instance.
     *
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        //
        $this->video = $video;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }
}
