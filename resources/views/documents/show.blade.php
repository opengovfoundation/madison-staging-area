@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ $document->title }}</h1>
    </div>

    @include('components.errors')

    @if (!empty($document->introtext))
        <div class="panel panel-default">
            <div class="panel-heading">@lang('messages.document.introtext')</div>
            <div class="panel-body">
                {!! $document->introtext !!}
            </div>
        </div>
    @endif

    <div id="page_content">
        {!! $pages->first()->rendered() !!}
    </div>

    {{ $pages->appends(request()->query())->fragment('page_content')->links() }}

    @push('scripts')
        <script src="{{ elixir('js/document.js') }}"></script>
        <script>
            loadAnnotations("#page_content", {{ $document->id }});
        </script>
    @endpush
@endsection
