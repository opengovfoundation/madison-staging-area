@extends('layouts.app')

@section('pageTitle', trans('messages.sponsor.become'))

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>@lang('messages.sponsor.become')</h1>
            <p>@lang('messages.sponsor.info.introtext')</p>
            <p><a class="btn btn-primary btn-lg" href="#get-started" role="button">@lang('messages.get_started') &raquo;</a></p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <ol class="lead">
                    <li>{!! trans('messages.sponsor.info.step1', [ 'new_account_route' => route('register') ]) !!}</li>
                    <li>{!! trans('messages.sponsor.info.step2', [ 'new_sponsor_route' => route('sponsors.create') ]) !!}</li>
                    <li>{{ trans('messages.sponsor.info.step3') }}</li>
                    <li>{{ trans('messages.sponsor.info.step4') }}</li>
                    <li>{!! trans('messages.sponsor.info.step5', [ 'new_document_route' => route('documents.create') ]) !!}</li>
                </ol>

                <p><a class="btn btn-primary" href="/madison-mockups/users/sponsors/new.html" role="button">@lang('messages.sponsor.become')</a></p>
            </div>
            <div class="col-md-6">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/69pPKZeKC8U" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
