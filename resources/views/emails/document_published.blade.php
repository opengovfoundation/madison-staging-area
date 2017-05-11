@component('mail::message')

@component('mail::button', ['url' => $url])
@lang('messages.notifications.see_document')
@endcomponent

<p style="text-align: center;">
    <a href="{{ $shareUrl }}">
        @lang('messages.notifications.madison.document.published.share_on_twitter')
    </a>
</p>

@endcomponent
