<div class="col-md-4">
    <div class="thumbnail doc-card">
        <a href="{{ route('documents.show', $document) }}">
            <img src="{{ $document->getFeaturedImageUrl() }}" class="img-responsive">
        </a>
        <div class="caption">
            <div class="intro">
                <h4>
                    {{ $document->title }}
                    <br>
                    <small class="text-muted">
                        {{ trans('messages.document.sponsoredby') }} {{ join(', ', $document->sponsors()->pluck('display_name')->toArray()) }}
                    </small>
                </h4>
                <p>{{ $document->introtext }}</p>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">{{ $document->created_at->toDateString() }}</small>
                    <br>
                    <small class="text-muted">{{ $document->comment_count }} {{ trans('messages.document.comments') }}</small>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-default btn-sm pull-right" role="button">@lang('messages.document.read')</a>
                </div>
            </div>
        </div>
    </div>
</div>
