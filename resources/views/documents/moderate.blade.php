@extends('layouts.app')

@section('pageTitle', trans('messages.document.moderate', ['document' => $document->title]))

@section('content')
    <div class="page-header">
        <h1>@lang('messages.document.moderate', ['document' => $document->title])</h1>
    </div>

    @include('components.errors')

    <h3>@lang('messages.document.comments_unhandled')</h3>
    @include('documents.partials.comment_table', ['comments' => $unhandledComments, 'document' => $document])

    <h3>@lang('messages.document.comments_handled')</h3>
    @include('documents.partials.comment_table', ['comments' => $handledComments, 'document' => $document])
@endsection
