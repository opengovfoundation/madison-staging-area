@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.sponsors.edit') }}: {{ $sponsor->getDisplayName() }}</h1>
    </div>

    @include('components.errors')

    {{ Form::model($sponsor, ['route' => ['sponsors.update', $sponsor->id], 'method' => 'put']) }}
        <div class="row">
            <div class="col-md-4">
                {{ Form::mInput('text', 'name', trans('messages.sponsors.internal_name')) }}
                {{ Form::mInput('text', 'display_name', trans('messages.sponsors.display_name')) }}
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        {{ Form::mInput('text', 'address1', trans('messages.info.address1')) }}
                    </div>
                    <div class="col-md-6">
                        {{ Form::mInput('text', 'address2', trans('messages.info.address2')) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {{ Form::mInput('text', 'city', trans('messages.info.city')) }}
                    </div>
                    <div class="col-md-2">
                        {{ Form::mInput('text', 'state', trans('messages.info.state')) }}
                    </div>
                    <div class="col-md-3">
                        {{ Form::mInput('text', 'postal_code', trans('messages.info.postal_code')) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::mInput('text', 'phone', trans('messages.info.phone')) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <hr>
        {{ Form::mSubmit() }}

    {{ Form::close() }}
@endsection
