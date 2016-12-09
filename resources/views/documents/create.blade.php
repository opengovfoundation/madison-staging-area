@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.create_document') }}</h1>
    </div>

    @include('components.errors')

    {{ Form::open(['route' => ['documents.store']]) }}
        {{ Form::mInput('text', 'title', trans('messages.document_title_field')) }}
        {{ Form::mSubmit(trans('messages.submit')) }}
    {{ Form::close() }}
@endsection
