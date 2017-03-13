<ol class="breadcrumb small">
    <li>
        <a href="{{ route('users.settings.edit', Auth::user()) }}">
            @lang('messages.user.settings_pages.account')
        </a>
    </li>
    @if (strpos(Request::route()->uri, 'sponsors') !== false)
        @if (strpos(Request::route()->uri, 'create'))
            <li>
                <a href="{{ route('users.sponsors.index', Auth::user()) }}">
                    @lang('messages.sponsor.list')
                </a>
            </li>
            <li class="active">@lang('messages.new')</li>
        @else
            <li class="active">@lang('messages.sponsor.list')</li>
        @endif

    @elseif (strpos(Request::route()->uri, 'account'))
        <li class="active">@lang('messages.settings')</li>

    @elseif (strpos(Request::route()->uri, 'password'))
        <li class="active">@lang('messages.user.settings_pages.password')</li>

    @elseif (strpos(Request::route()->uri, 'notifications'))
        <li class="active">@lang('messages.user.settings_pages.notifications')</li>

    @endif
</ol>
