<ol class="breadcrumb small">
    <li>
        <a href="{{ route('users.settings.edit', Auth::user()) }}">
            @lang('messages.user.settings_pages.account')
        </a>
    </li>
    <li>
        <a href="{{ route('users.sponsors.index', Auth::user()) }}">
            @lang('messages.sponsor.list')
        </a>
    </li>
    <li>
        <a href="{{ route('sponsors.documents.index', $sponsor) }}">
            {{ $sponsor->display_name }}
        </a>
    </li>
    @if (strpos(Request::route()->uri, 'members') && strpos(Request::route()->uri, 'create'))
        <li>
            <a href="{{ route('sponsors.members.index', $sponsor) }}">
                @lang('messages.sponsor.members')
            </a>
        </li>
    @endif

    @if (strpos(Request::route()->uri, 'documents'))
        <li class="active">@lang('messages.document.list')</li>

    @elseif (strpos(Request::route()->uri, 'members') && strpos(Request::route()->uri, 'create'))
        <li class="active">@lang('messages.add')</li>

    @elseif (strpos(Request::route()->uri, 'members'))
        <li class="active">@lang('messages.sponsor.members')</li>

    @elseif (strpos(Request::route()->uri, 'edit'))
        <li class="active">@lang('messages.settings')</li>

    @endif
</ol>
