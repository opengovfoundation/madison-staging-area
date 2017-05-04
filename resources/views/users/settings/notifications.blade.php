@extends('users.settings')

@section('pageTitle', trans('messages.user.settings_pages.notifications'))

@section('settings_content')
    {{ Form::open(['route' => ['users.settings.notifications.update', $user->id], 'method' => 'put']) }}
        @foreach($notificationPreferenceGroups as $group => $notificationPreferences)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">@lang('messages.notifications.groups.'.$group)</h3>
                </div>
                <div class="panel-body">
                    @foreach($notificationPreferences as $notificationClass => $value)
                        @php($targetNotification = request()->input('notification') === $notificationClass::getName())
                        <div class="row">
                            <div class="col-xs-12 col-md-9 text-right {{ $targetNotification ? 'anchor-target' : '' }}">
                               @lang($notificationClass::baseMessageLocation().'.preference_description')
                            </div>
                            <div class="col-xs-12 col-md-3">
                                {{ Form::mSelect(
                                    $notificationClass::getName(),
                                    null,
                                    collect($frequencyOptions)->mapWithKeys_v2(function ($f) { return [$f => trans('messages.notifications.frequencies.'.$f.'.label')]; })->toArray(),
                                    $value,
                                    [
                                        'label-sr-only' => true,
                                        'class' => 'no-select2',
                                    ]
                                ) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <hr>
        {{ Form::mSubmit(trans('messages.save')) }}
    {{ Form::close() }}
@endsection
