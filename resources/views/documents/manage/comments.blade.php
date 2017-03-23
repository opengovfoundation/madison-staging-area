@extends('layouts.app')

@section('pageTitle', trans('messages.document.moderate', ['document' => $document->title]))

@section('content')
    <div class="page-header">
        <h1>@lang('messages.document.moderate_document', ['document' => $document->title])</h1>
        @include('components.breadcrumbs.document', ['sponsor' => $document->sponsors()->first(), 'document' => $document])
    </div>

    @include('components.errors')

    <div class="row">
        @include('documents.partials.manage-sidebar')

        <div class="col-md-9">
            {{-- TODO: make better --}}
            <a href="{{ route('documents.comments.index', [$document, 'download' => 'csv']) }}">@lang('messages.document.download_comments_csv')</a>

            @if ($unhandledComments->count() > 0)
                <div class="unhandled">
                    <h3>@lang('messages.document.comments_unhandled')</h3>
                    @include('documents.partials.comment_table', ['comments' => $unhandledComments, 'document' => $document])
                </div>
            @endif

            @if ($handledComments->count() > 0)
                <div class="handled">
                    <h3>@lang('messages.document.comments_handled')</h3>
                    @include('documents.partials.comment_table', ['comments' => $handledComments, 'document' => $document])
                </div>
            @endif
        </div>
    </div>
@endsection
