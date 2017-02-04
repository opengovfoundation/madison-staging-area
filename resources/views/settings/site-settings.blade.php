@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.setting.admin_label') }} {{ trans('messages.settings') }}</h1>
    </div>

    @include('components.errors')

    <div class="row">
        @include('settings.partials.admin-sidebar')

        <div class="col-md-9">
            <!-- TODO -->
        </div>
    </div>
@endsection
