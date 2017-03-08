@extends('layouts.app')

@if ($onlyUserSponsors)
    @section('pageTitle', trans('messages.sponsor.my_sponsors'))
@else
    @section('pageTitle', trans('messages.sponsor.list'))
@endif

@section('content')
    <div class="page-header">
        @if ($onlyUserSponsors)
            @if (request()->user()->isAdmin())
                {{ Html::linkRoute('sponsors.index', trans('messages.sponsor.all_sponsors'), ['all' => 'true'], ['class' => 'btn btn-default pull-right'])}}
            @endif
            <h1>{{ trans('messages.sponsor.my_sponsors') }}</h1>
        @else
            {{ Html::linkRoute('sponsors.index', trans('messages.sponsor.my_sponsors'), [], ['class' => 'btn btn-default pull-right'])}}
            <h1>{{ trans('messages.sponsor.list') }}</h1>
        @endif
        <ol class="breadcrumb small">
            <li><a href="/madison-mockups/users/settings.html">@lang('messages.user.settings_pages.account')</a></li>
            <li class="active">@lang('messages.sponsor.list')</li>
        </ol>
    </div>

    @include('components.errors')


    <div class="row">
        <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('messages.sponsor.name')</th>
                        <th>@lang('messages.created')</th>
                        @if ($canSeeAtLeastOneStatus)
                            <th>@lang('messages.sponsor.status')</th>
                        @endif
                        <th>@lang('messages.sponsor.members')</th>
                        <th>@lang('messages.document.list')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sponsors as $sponsor)
                        <tr>
                            <td>
                                <a href="{{ route('sponsors.members.index', $sponsor) }}">
                                    {{ $sponsor->name }}
                                </a>
                            </td>
                            <td>
                                @include('components/date', [ 'datetime' => $sponsor->created_at, ])
                            </td>
                            @if ($canSeeAtLeastOneStatus)
                                <td>
                                    @if ($sponsorsCapabilities[$sponsor->id]['editStatus'])
                                        {{ Form::open(['route' => ['sponsors.status.update', $sponsor->id], 'method' => 'put']) }}
                                            {{ Form::select(
                                                'status',
                                                collect($validStatuses)->mapWithKeys_v2(function ($item) {return [$item => trans('messages.sponsor.statuses.'.$item)]; })->toArray(),
                                                $sponsor->status,
                                                [ 'onchange' => 'if (this.selectedIndex >= 0) this.form.submit();' ]
                                                )
                                            }}
                                        {{ Form::close() }}
                                    @elseif ($sponsorsCapabilities[$sponsor->id]['viewStatus'])
                                        {{ trans('messages.sponsor.statuses.'.$sponsor->status) }}
                                    @else
                                        {{-- do nothing --}}
                                    @endif
                                </td>
                            @endif
                            <td>{{ $sponsor->docs()->count() }}</td>
                            <td>{{ $sponsor->members()->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4><small>@lang('messages.sponsor.create_another_header')</small></h4>
                    <p><small>@lang('messages.sponsor.create_another_body')</small></p>
                    {{ Html::linkRoute('sponsors.create', trans('messages.sponsor.create_another'), [], ['class' => 'btn btn-default btn-xs'])}}
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        @include('components.pagination', ['collection' => $sponsors])
    </div>
@endsection
