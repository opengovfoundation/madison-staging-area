<?php

namespace App\Events;

use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SponsorCreated
{
    use SerializesModels;

    public $sponsor;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Sponsor $sponsor, User $user)
    {
        $this->sponsor = $sponsor;
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
