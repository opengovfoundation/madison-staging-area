<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShouldSendNotification
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
     * @param  NotificationSending  $event
     * @return void
     */
    public function handle(NotificationSending $event)
    {
        // // determine if the recipient wants notifications from this kind of event
        // $recipientNotificationPreference = [];
        // foreach ($message->getRecipients() as $recipient) {
        //     // if the event is not in the set of valid notifications for the
        //     // recipient, then skip it
        //     if (empty(NotificationPreference::getValidNotificationsForUser($recipient)[$event::getName()])) {
        //         continue;
        //     }

        //     $recipientNotificationPreference[$recipient->id] = NotificationPreference
        //         ::where('user_id', $recipient->id)
        //         ->where('event', $event::getName())
        //         ->get();
        // }

        // if (empty($recipientNotificationPreference)) {
        //     // they don't want notifications for this event type
        //     return;
        // }

    //     foreach ($recipientNotificationPreference as $userId => $prefs) {
    //         foreach ($prefs as $pref) {
    //             switch ($pref->type) {
    //                 case NotificationPreference::TYPE_EMAIL:
    //                     $recipient = User::find($userId);

    //                     // if the email is not verified, don't send a message to
    //                     // it regardless of if the user has it selected
    //                     if (!empty($recipient->token) || empty($recipient->email)) {
    //                         continue;
    //                     }

    //                     $this->mailer->raw($message->getBody(), function ($swiftMessage) use ($message, $recipient) {
    //                         $swiftMessage->setContentType('text/html');
    //                         $swiftMessage->subject($message->getSubject());
    //                         $swiftMessage->from('sayhello@opengovfoundation.org', 'Madison');
    //                         $swiftMessage->to($recipient->email);
    //                     });
    //                     break;
    //                 case NotificationPreference::TYPE_TEXT:
    //                     // unsupported
    //                 default:
    //                     // do nothing
    //             }
    //         }
    //     }
    // }
}
