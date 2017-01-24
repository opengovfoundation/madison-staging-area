@extends('layouts.app')

@section('content')

    <div class="page-header">
        <h1>{{ trans('messages.pages.manage') }}</h1>
    </div>

    @include('components.errors')

    {{ Html::linkRoute('pages.create', trans('messages.pages.create'), [], ['class' => 'btn btn-default'])}}

    <table class="table">
        <thead>
            <tr>
                <th>@lang('messages.pages.navigation_title')</th>
                <th>@lang('messages.pages.url')</th>
                <th>@lang('messages.pages.show_in_header')</th>
                <th>@lang('messages.pages.show_in_footer')</th>
                <th>@lang('messages.actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->nav_title }}</td>
                    <td>{{ $page->url }}</td>
                    <td>{{ $page->header_nav_link ? "X" : "" }}</td>
                    <td>{{ $page->footer_nav_link ? "X" : "" }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
