<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification as BaseNotification;
use App\Models\NotificationPreference;

abstract class Notification
    extends BaseNotification
    implements \App\Contracts\Notification
{
    public $actionUrl;
    public $subjectText;

    public static function baseMessageLocation()
    {
        return 'messages.notifications.'.static::getName();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $preference = $notifiable->notificationPreferences()->where('event', $this->getName())->first();

        // User not subscribed to this notification? No channels
        if (!$preference) { return []; }

        // If we're not sending this immediately it goes in the database
        if ($preference->frequency === NotificationPreference::FREQUENCY_IMMEDIATELY) {
            return ['mail'];
        }

        return ['database'];
    }

    /**
     * Get the single line representation of the notification. Used in
     * notification batch emails.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toLine()
    {
        return '<a href="' . $this->actionUrl . '">' . $this->subjectText . '</a>';
    }

}
