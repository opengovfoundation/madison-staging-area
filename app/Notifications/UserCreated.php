<?php

namespace App\Notifications;

use App\Models\Sponsor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sponsor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // TODO: where to send them?
        $url = route('admin.sponsors.index');

        return (new MailMessage)
                    ->subject(trans(static::baseMessageLocation().'.subject', ['name' => $this->sponsor->name]))
                    // TODO: what to say?
                    ->action(trans('messages.notifications.review_sponsor'), $url)
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
            'name' => static::getName(),
            'user_id' => $this->user->id,
        ];
    }

    public static function getName()
    {
        return 'madison.user.created';
    }

    public static function getType()
    {
        return static::TYPE_ADMIN;
    }

    public function getInstigator()
    {
        return $this->user;
    }
}
