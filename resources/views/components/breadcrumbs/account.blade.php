<ol class="breadcrumb small">
    <li>
        <a href="{{ route('users.settings.edit', Auth::user()) }}">
            @lang('messages.user.settings_pages.account')
        </a>
    </li>
    @if (strpos(Request::route()->uri, 'sponsors'))
        <li class="active">@lang('messages.sponsor.list')</li>
    @elseif (strpos(Request::route()->uri, 'account'))
        <li class="active">@lang('messages.settings')</li>
    @elseif (strpos(Request::route()->uri, 'password'))
        <li class="active">@lang('messages.user.settings_pages.password')</li>
    @elseif (strpos(Request::route()->uri, 'notifications'))
        <li class="active">@lang('messages.user.settings_pages.notifications')</li>
    @endif
</ol>
