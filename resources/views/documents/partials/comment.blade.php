<div class="comment">
    <h4>
        <strong>{{ $comment->user->display_name }}</strong>
        <span class="small">{{ $comment->created_at->diffForHumans() }}</span>
    </h4>
    <p>{{ $comment->annotationType->content }}</p>
    <span class="likes">
        <span class="fa fa-thumbs-up" aria-hidden="true"></span>
        {{ $comment->likes_count }}
    </span>
    <span class="flags">
        <span class="fa fa-flag" aria-hidden="true"></span>
        {{ $comment->flags_count }}
    </span>
    <span class="replies">
        <span class="fa fa-comments" aria-hidden="true"></span>
        {{ $comment->comments()->count() }}
    </span>
    <hr>
</div>
