<div class="col-md-3">
    <div class="list-group">
        <a href="{{ route('sponsors.documents.index', $sponsor) }}"
            class="list-group-item {{ Request::route()->getName() !== 'sponsors.documents.index' ?: 'active' }}">

            @lang('messages.document.list')
        </a>
        <a href="{{ route('sponsors.members.index', $sponsor) }}"
            class="list-group-item {{ !strpos(Request::route()->uri, 'members') ?: 'active' }}">

            @lang('messages.sponsor.members')
        </a>
        @if ($sponsor->isSponsorOwner(Auth::user()) || Auth::user()->isAdmin())
            <a href="{{ route('sponsors.edit', $sponsor) }}"
                class="list-group-item {{ Request::route()->getName() !== 'sponsors.edit' ?: 'active' }}">

                @lang('messages.settings')
            </a>
        @endif
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4><small>@lang('messages.sponsor.create_another_header')</small></h4>
            <p><small>@lang('messages.sponsor.create_another_body')</small></p>
            {{ Html::linkRoute('sponsors.create', trans('messages.sponsor.create_another'), [], ['class' => 'btn btn-default btn-xs'])}}
        </div>
    </div>
</div>
