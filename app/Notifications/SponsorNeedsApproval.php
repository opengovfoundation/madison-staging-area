<?php

namespace App\Notifications;

use App\Models\Sponsor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SponsorNeedsApproval extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sponsor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Sponsor $sponsor)
    {
        $this->sponsor = $sponsor;
        $this->actionUrl = route('admin.sponsors.index');
        $this->subjectText = trans(static::baseMessageLocation().'.subject', ['name' => $this->sponsor->name]);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->subjectText)
                    ->action(trans('messages.notifications.review_sponsor'), $this->actionUrl)
                    ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'line' => $this->toLine(),
            'name' => static::getName(),
            'sponsor_id' => $this->sponsor->id,
        ];
    }

    public static function getName()
    {
        return 'madison.sponsor.needs_approval';
    }

    public static function getType()
    {
        return static::TYPE_ADMIN;
    }

    public function getInstigator()
    {
        // not strictly correct, we do currently set the user who created the
        // sponsor as an owner, but since these notifications are queued,
        // there is a small chance that a user could add another user, make
        // them an owner, and remove themselves...
        return $this->sponsor->findUsersByRole(Sponsor::ROLE_OWNER)->first();
    }
}
