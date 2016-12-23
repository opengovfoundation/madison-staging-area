@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.sponsors.management') }}</h1>
    </div>

    @include('components.errors')

    <table class="table">
        <thead>
            <tr>
                <th>@lang('messages.sponsors.display_name')</th>
                <th>@lang('messages.sponsors.internal_name')</th>
                <th>@lang('messages.sponsors.your_role')</th>
                <th>@lang('messages.sponsors.status')</th>
                <th>@lang('messages.created')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sponsors as $sponsor)
                <tr>
                    <td>{{ $sponsor->getDisplayName() }}</td>
                    <td>{{ $sponsor->name }}</td>
                    <td>{{ $sponsor->getMemberRole(Auth::user()->id) }}</td>
                    <td>{{ $sponsor->status }}</td>
                    <td>{{ $sponsor->created_at->diffForHumans() }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
