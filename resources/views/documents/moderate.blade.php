@extends('layouts.app')

@section('pageTitle', trans('messages.document.moderate', ['document' => $document->title]))

@section('content')
    <div class="page-header">
        <h1>@lang('messages.document.moderate', ['document' => $document->title])</h1>
    </div>

    @include('components.errors')

    <table class="table">
        <thead>
            <tr>
                <th>@lang('messages.user.user')</th>
                <th>@lang('messages.type')</th>
                <th>@lang('messages.document.comment')</th>
                <th>@lang('messages.document.like')</th>
                <th>@lang('messages.document.flag')</th>
                <th>@lang('messages.document.replies')</th>
                <th>@lang('messages.created')</th>
                <th>@lang('messages.actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($flaggedComments as $comment)
                <tr>
                    <td>{{ $comment->user->display_name }}</td>
                    <td>{{ $comment->annotation_type_type === "range" ? trans('messages.document.note') : trans('messages.document.comment') }}</td>
                    <td>{{ str_limit($comment->annotationType->content, 100, ' ...') }}</td>
                    <td>{{ $comment->likes_count }}</td>
                    <td>{{ $comment->flags_count }}</td>
                    <td>{{ $comment->comments_count }}</td>
                    <td>{{ $comment->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ $comment->getLink() }}" class="btn btn-default">
                                {{ trans('messages.view') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
