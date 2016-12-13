@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.edit_document') }}</h1>
    </div>

    @include('components.errors')

    {{ Form::model($document, ['route' => ['documents.update', $document->id], 'method' => 'put']) }}
        {{ Form::mInput('text', 'title', trans('messages.document_title_field')) }}
        {{ Form::mSubmit() }}
    {{ Form::close() }}
@endsection
