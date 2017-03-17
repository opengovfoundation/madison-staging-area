<ol class="breadcrumb small">
    <li>
        <a href="{{ route('users.settings.edit', Auth::user()) }}">
            @lang('messages.user.settings_pages.account')
        </a>
    </li>
    @if (Route::currentRouteName() === 'sponsors.create')
        <li>
            <a href="{{ route('users.sponsors.index', Auth::user()) }}">
                @lang('messages.sponsor.list')
            </a>
        </li>
    @endif
    <li class="active">
        {{--sponsors.create--}}
        @if (Route::currentRouteName() === 'sponsors.create')
            @lang('messages.new')
        {{--users.sponsors.index--}}
        @elseif (Route::currentRouteName() === 'users.sponsors.index')
            @lang('messages.sponsor.list')
        {{--users.settings.account.edit--}}
        @elseif (Route::currentRouteName() === 'users.settings.account.edit')
            @lang('messages.settings')
        {{--users.settings.password.edit--}}
        @elseif (Route::currentRouteName() === 'users.settings.password.edit')
            @lang('messages.user.settings_pages.password')
        {{--users.settings.notifications.edit--}}
        @elseif (Route::currentRouteName() === 'users.settings.notifications.edit')
            @lang('messages.user.settings_pages.notifications')
        @endif
    </li>
</ol>
