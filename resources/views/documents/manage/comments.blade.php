@extends('documents.manage')

@section('pageTitle', trans('messages.document.comments'))

@section('manage_content')
    <p class="text-right">
        <a href="{{ route('documents.comments.index', [$document, 'download' => 'csv', 'all' => true]) }}" class="btn btn-primary">
            @lang('messages.document.download_comments_csv')
        </a>
    </p>

    @if ($unhandledComments->count() > 0)
        <div class="panel panel-default unhandled">
            <div  class="panel-heading">
                <h3 class="panel-title">@lang('messages.document.comments_unhandled')</h3>
            </div>
            <div class="panel-body">
                @include('documents.partials.comment_table', ['comments' => $unhandledComments, 'document' => $document])
            </div>
        </div>
    @endif

    @if ($handledComments->count() > 0)
        <div class="panel panel-default handled">
            <div  class="panel-heading">
                <h3 class="panel-title">@lang('messages.document.comments_handled')</h3>
            </div>
            <div class="panel-body">
                @include('documents.partials.comment_table', ['comments' => $handledComments, 'document' => $document])
            </div>
        </div>
    @endif
@endsection
