<ol class="breadcrumb small">
    <li>
        <a href="{{ route('admin.site.index') }}">
            @lang('messages.administrator')
        </a>
    </li>
    @if (strpos(Request::route()->uri, 'site'))
        <li class="active">@lang('messages.settings')</li>
    @elseif (strpos(Request::route()->uri, 'pages'))
        <li class="active">@lang('messages.admin.pages')</li>
    @elseif (strpos(Request::route()->uri, 'featured'))
        <li class="active">@lang('messages.admin.featured_documents')</li>
    @endif
</ol>
