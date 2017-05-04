@component('mail::message')

@lang('messages.notifications.frequencies.' . App\Models\NotificationPreference::FREQUENCY_DAILY . '.intro')

@foreach ($notifications as $notification)
- {!! $notification->data['line'] !!}
@endforeach

@lang('messages.notifications.salutation', ['name' => config('app.name')])
@endcomponent
