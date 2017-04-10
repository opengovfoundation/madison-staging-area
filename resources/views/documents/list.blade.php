@extends('layouts.app')

@section('pageTitle', trans('messages.document.list'))

@section('content')
    <div class="page-header">
        <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#queryModal">Query</button>
        <h1>{{ trans('messages.document.list') }}</h1>
    </div>

    @include('components.errors')


    <div class="modal fade" id="queryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Query</h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'documents.index', 'method' => 'get']) }}
                        {{ Form::mInput('text', 'q', trans('messages.search.title')) }}
                        {{ Form::mSelect(
                               'sponsor_id[]',
                               trans('messages.document.sponsor'),
                               $sponsors->mapWithKeys_v2(function ($item) {return [$item->id => $item->display_name]; })->toArray(),
                               null,
                               ['multiple' => true]
                               )
                        }}
                        @if (Auth::user())
                            {{ Form::mSelect(
                                   'publish_state[]',
                                   trans('messages.document.publish_state'),
                                   collect($publishStates)->mapWithKeys_v2(function ($item) {return [$item => trans('messages.document.publish_states.'.$item)]; })->toArray(),
                                   null,
                                   ['multiple' => true]
                                   )
                            }}
                        @endif
                        {{ Form::mSelect(
                               'discussion_state[]',
                               trans('messages.document.discussion_state'),
                               collect($discussionStates)->mapWithKeys_v2(function ($item) {return [$item => trans('messages.document.discussion_states.'.$item)]; })->toArray(),
                               null,
                               ['multiple' => true]
                               )
                        }}
                        {{ Form::mSelect(
                               'order',
                               trans('messages.order_by'),
                               [
                                   'created_at' => trans('messages.created'),
                                   'updated_at' => trans('messages.updated'),
                                   'title' => trans('messages.document.title'),
                                   'activity' => trans('messages.document.activity'),
                                   'relevance' => trans('messages.relevance'),
                               ])
                        }}
                        {{ Form::mSelect(
                               'order_dir',
                               trans('messages.order_by_direction'),
                               [
                                   'DESC' => trans('messages.order_by_dir_desc'),
                                   'ASC' => trans('messages.order_by_dir_asc')
                               ])
                        }}
                        {{ Form::mSelect(
                               'limit',
                               trans('messages.limit'),
                               array_combine($range = [10, 25, 50], $range)
                               )
                        }}

                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                        <a href="{{ request()->url() }}" class="btn btn-default">@lang('messages.clear')</a>
                        {{ Form::mSubmit() }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>


    @foreach ($documents as $document)
        <div class="row document-row">
            <div class="col-sm-2">
                <img src="{{ $document->getFeaturedImageUrl() }}" class="img-responsive" alt="{{ trans('messages.document.featured_image') }}">
            </div>
            <div class="col-sm-4">
                <div class="intro">
                    <h4>
                        {{ $document->title }}
                        <br>
                        <small class="text-muted">
                            @lang('messages.document.sponsoredby', ['sponsors' => $document->sponsors->implode('display_name', ', ')])
                        </small>
                    </h4>
                </div>
            </div>
            <div class="col-sm-4">
                <p>{{ $document->shortIntroText() }}</p>
            </div>
            <div class="col-sm-2">
                <div class="row">
                    <div class="col-xs-6 col-md-12">
                        <small class="text-muted">{{ $document->created_at->toDateString() }}</small>
                        <br>
                        <small class="text-muted">{{ $document->all_comments_count }} {{ trans('messages.document.comments') }}</small>
                    </div>
                    <div class="col-xs-6 col-md-12">
                        <a href="{{ route('documents.show', $document) }}" class="btn btn-default btn-xs" role="button">
                            @lang('messages.document.read')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="text-center">
        @include('components.pagination', ['collection' => $documents])
    </div>
@endsection
