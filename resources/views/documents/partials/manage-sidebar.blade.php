<div class="col-md-3">
    <div class="list-group">
        <a href="{{ route('documents.edit', $document) }}"
            class="list-group-item {{ active(['documents.edit']) }}">

            @lang('messages.settings')
        </a>
        <a href="{{ route('documents.moderate', $document) }}"
            class="list-group-item {{ active(['documents.moderate']) }}">

            @lang('messages.document.comments')
        </a>
    </div>
</div>
