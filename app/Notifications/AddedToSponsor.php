<?php

namespace App\Notifications;

use App\Models\SponsorMember;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddedToSponsor extends Notification implements ShouldQueue
{
    use Queueable;

    public $sponsorMember;
    public $instigator;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SponsorMember $sponsorMember, User $instigator)
    {
        $this->sponsorMember = $sponsorMember;
        $this->instigator = $instigator;
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
        $url = route('sponsors.index', ['id' => [$this->sponsorMember->sponsor->id]]);

        return (new MailMessage)
                    ->line(trans('messages.notifications.added_to_sponsor', [
                        'name' => $this->instigator->getDisplayName(),
                        'sponsor' => $this->sponsorMember->sponsor->display_name,
                        'role' => $this->sponsorMember->role,
                    ]))
                    ->action(trans('messages.notifications.see_sponsor'), 'https://laravel.com')
                    ->line(trans('messages.notifications.thank_you'));
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
            'sponsor_member_id' => $this->sponsorMember->id,
            'instigator_id' => $this->instigator->id,
        ];
    }

    public static function getName()
    {
        return 'madison.user.added_to_sponsor';
    }

    public static function getType()
    {
        return static::TYPE_USER;
    }

    public function getInstigator()
    {
        return $this->instigator;
    }
}
