<?php

namespace App\Listeners;

use App\Events\SponsorCreated;
use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class SponsorCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Handle the event.
     *
     * @param  SponsorCreated  $event
     * @return void
     */
    public function handle(SponsorCreated $event)
    {
        $adminRole = Role::where('name', Role::ROLE_ADMIN)->first();
        $admins = $adminRole->users;

        Notification::send($admins, new SponsorNeedsApproval($event->sponsor));
    }
}
