@extends('layouts.app')

@section('pageTitle', trans('messages.document.list'))

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.document.list') }}</h1>
    </div>

    @include('components.errors')

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
