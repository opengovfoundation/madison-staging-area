@extends('layouts.app')

@section('pageTitle', trans('messages.admin.site_settings'))

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.admin.admin_label', ['page' => trans('messages.settings')]) }}</h1>
        @include('components.breadcrumbs.admin')
    </div>

    @include('components.errors')

    <div class="row">
        @include('admin.partials.admin-sidebar')

        <div class="col-md-9">
            {{ Form::model($currentSettings, ['route' => ['admin.site.update'], 'method' => 'put']) }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('messages.admin.date_time_settings_title')</h3>
                    </div>
                    <div class="panel-body">
                        {{ Form::mSelect(
                                'madison.date_format',
                                trans('messages.admin.madison.date_format'),
                                $options['madison.date_format']['choices'],
                                null,
                                [],
                                trans('messages.admin.madison.date_format_help') !== 'messages.admin.madison.date_format_help'
                                       ? trans('messages.admin.madison.date_format_help')
                                       : null
                                )
                        }}
                        {{ Form::mSelect(
                                'madison.time_format',
                                trans('messages.admin.madison.time_format'),
                                $options['madison.time_format']['choices'],
                                null,
                                [],
                                trans('messages.admin.madison.time_format_help') !== 'messages.admin.madison.time_format_help'
                                       ? trans('messages.admin.madison.time_format_help')
                                       : null
                                )
                        }}
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('messages.admin.google_analytics_settings_title')</h3>
                    </div>
                    <div class="panel-body">
                        {{ Form::mInput(
                                'text',
                                'madison.google_analytics_property_id',
                                trans('messages.admin.madison.google_analytics_property_id'),
                                null,
                                [ 'placeholder' =>
                                    !empty($options['madison.google_analytics_property_id']['placeholder'])
                                        ? $options['madison.google_analytics_property_id']['placeholder']
                                        : ''
                                ],
                                trans('messages.admin.madison.google_analytics_property_id_help') !== 'messages.admin.madison.google_analytics_property_id_help'
                                       ? trans('messages.admin.madison.google_analytics_property_id_help')
                                       : null
                                )

                        }}
                    </div>
                </div>
                {{ Form::mSubmit() }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
