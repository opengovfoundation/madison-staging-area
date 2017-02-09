<?php

namespace App\Listeners;

use App\Mail\EmailVerification;
use App\Mail\Welcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class RegisteredUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // send welcome email
        Mail
            ::to($event->user)
            ->send(new Welcome($event->user));

        // send email to user for email verification
        Mail
            ::to($event->user)
            ->send(new EmailVerification($event->user));
    }
}
