<div class="comment">
    <h4>
        <strong>{{ $comment->user->display_name }}</strong>
        <span class="small">{{ $comment->created_at->diffForHumans() }}</span>
    </h4>
    <p>{{ $comment->annotationType->content }}</p>
    <hr>
</div>
