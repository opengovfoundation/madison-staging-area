<div class="new-comment-form">
    <div class="collapsed-content">
        <div class="media">
            <div class="media-left">
                <img class="media-object" alt="user profile image" src="{{ Auth::user()->avatar }}">
            </div>
            <div class="media-body media-middle">
                <button class="btn btn-link" onclick="toggleNewCommentForm(this)">
                    @lang('messages.document.add_comment')
                </button>
            </div>
        </div>
    </div>

    <div class="expanded-content hide">
        <div class="media">
            <div class="media-left">
                <img class="media-object" alt="user profile image" src="{{ Auth::user()->avatar }}">
            </div>
            <div class="media-body media-middle">
                {{ Auth::user()->display_name }}
            </div>
        </div>

        {{ Form::open(['route' => $route, 'class' => 'comment-form']) }}
            {{ Form::mInput(
                'textarea',
                'text',
                trans('messages.document.add_comment'),
                null,
                [ 'rows' => 3, 'label-sr-only' => true ]
            ) }}
            <button type="button" class="btn btn-default" onclick="toggleNewCommentForm(this)">@lang('messages.cancel')</button>
            {{ Form::mSubmit() }}
        {{ Form::close() }}
    </div>
</div>
