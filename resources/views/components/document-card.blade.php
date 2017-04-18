<div class="document-card">
    @if (isset($featured) && $featured)
        <h2>
    @else
        <h3>
    @endif
        <a href="{{ route('documents.show', $document) }}">
            {{ $document->title }}
        </a>
        <br>
        <small class="text-muted">
            @lang('messages.document.sponsoredby', ['sponsors' => $document->sponsors->implode('display_name', ', ')])
        </small>
    @if (isset($featured) && $featured)
        </h2>
    @else
        </h3>
    @endif

    @if (isset($featured) && $featured)
        <p>{{ $document->shortIntroText() }}</p>
    @endif

    <p class="document-info">
        <small>{{ $document->created_at->diffForHumans()}}</small>
        <small>
            <i class="fa fa-thumbs-up"></i>
            {{ $document->support }}
        </small>
        <small>
            <i class="fa fa-thumbs-down"></i>
            {{ $document->oppose }}
        </small>
        <small>
            <i class="fa fa-comments"></i>
            {{ $document->all_comments_count }}
        </small>
    </p>
</div>
