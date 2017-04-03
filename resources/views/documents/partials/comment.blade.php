<div class="media-left">
    <img class="media-object" alt="user profile image" src="{{ $comment->user->avatar }}">
</div>

<div class="media-body">
    <div class="comment-content">
        <h4 class="media-heading">
            {{ $comment->user->display_name }}
            <br>

            <a class="link" href="{{ $comment->getLink() }}"
                aria-label="{{ trans('messages.permalink') }}" role="button"
                title="{{ trans('messages.permalink') }}">
            <small>@include('components/relative-time', [ 'datetime' => $comment->created_at ])</small>
                </a>
        </h4>

        <div class="activity-actions pull-right">
            <a class="thumbs-up" onclick="$(this).trigger('madison.addAction')"
                data-action-type="likes" data-annotation-id="{{ $comment->str_id }}"
                title="{{ trans('messages.document.like') }}"
                aria-label="{{ trans('messages.document.like') }}" role="button">

                <span class="action-count">{{ $comment->likes_count }}</span>
            </a>

            <a class="flag" onclick="$(this).trigger('madison.addAction')"
                data-action-type="flags" data-annotation-id="{{ $comment->str_id }}"
                title="{{ trans('messages.document.flag') }}"
                aria-label="{{ trans('messages.document.flag') }}" role="button">

                <span class="action-count">{{ $comment->flags_count }}</span>
            </a>

            @if ($comment->annotatable_type === \App\Models\Doc::ANNOTATABLE_TYPE)
                <a class="comments" aria-label="{{ trans('messages.document.replies') }}
                    title="{{ trans('messages.document.replies') }} role="button"
                    data-comment-id="{{ $comment->str_id }}">

                    <span class="action-count">{{ $comment->comments()->count() }}</span>
                </a>
            @endif
        </div>

        @if (!empty($comment->data['quote']))
            <a onclick="anchorToHighlight('{{ $comment->str_id }}')">
                <blockquote>
                    <p>{{ $comment->data['quote'] }}</p>
                </blockquote>
            </a>
        @endif

        <p>{{ $comment->annotationType->content }}</p>
    </div>

    @if (Auth::user() && $comment->annotatable_type === \App\Models\Doc::ANNOTATABLE_TYPE)
        <div class="clearfix"></div>

        @include('documents.partials.new-comment-form', ['route' => ['documents.comments.storeReply', $comment->annotatable_id, $comment->id]])
    @endif

    <div class="comment-replies">
        @if ($comment->comments()->count() > 0)
            @each('documents/partials/comment-div', $comment->comments()->get(), 'comment')
        @endif
    </div>
</div>
