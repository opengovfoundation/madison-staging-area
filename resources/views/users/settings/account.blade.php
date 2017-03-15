@extends('users.settings')

@section('pageTitle', trans('messages.user.settings_pages.account'))

@section('settings_content')
    {{ Form::model($user, ['route' => ['users.settings.account.update', $user->id], 'method' => 'put']) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::mInput('text', 'fname', trans('messages.user.fname'), null, ['required' => '']) }}
            </div>
            <div class="col-md-6">
                {{ Form::mInput('text', 'lname', trans('messages.user.lname'), null, ['required' => '']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ Form::mInput('email', 'email', trans('messages.user.email'), null, ['required' => ''], trans('messages.user.email_help')) }}
            </div>
        </div>
        <hr>
        {{ Form::mSubmit(trans('messages.save')) }}
    {{ Form::close() }}
@endsection
