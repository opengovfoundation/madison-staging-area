@extends('layouts.app')

@section('pageTitle', trans('messages.admin.site_settings'))

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.admin.admin_label', ['page' => trans('messages.settings')]) }}</h1>
    </div>

    @include('components.errors')

    <div class="row">
        @include('admin.partials.admin-sidebar')

        <div class="col-md-9">
            {{ Form::model($currentSettings, ['route' => ['admin.site.update'], 'method' => 'put']) }}
                {{ Form::mSelect(
                        'madison.date_format',
                        trans('messages.admin.date_format'),
                        $dateFormats
                        )
                }}

                {{ Form::mSelect(
                        'madison.time_format',
                        trans('messages.admin.time_format'),
                        $timeFormats
                        )
                }}

                {{ Form::mSubmit() }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
