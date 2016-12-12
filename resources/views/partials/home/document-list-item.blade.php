<article class="document-list-item">
    <header class="document-info">
        <h3 class="subheading">
            <a href="/documents/{{ $document['slug'] }}" title="{{ $document['title'] }}">
                {{ $document['title'] }}
            </a>
        </h3>
        <div class="document-sponsors">
            @if ($document['discussion_state'] == 'closed')
                <span data-toggle="tooltip" data-placement="top"
                    class="fa-stack discussion-closed"
                    title="{{ trans('messages.document.discussionstate.closed.tooltip') }}">

                    <!-- Icon for closed discussion -->
                    <i class="fa fa-comment fa-flip-horizontal fa-stack-1x"></i>
                    <i class="fa fa-ban fa-stack-2x text-danger"></i>
                </span>
            @endif

            {{ trans('messages.document.sponsoredby') }}
            <ul>
                @foreach ($document['sponsors'] as $sponsor)
                    <!-- TODO: Would be cool to link this to a sponsor view page -->
                    <li class="document-sponsor">{{ $sponsor['display_name'] }}</li>
                @endforeach
            </ul>
        </div>
        <div class="document-categories">
            <ul>
                @foreach ($document['categories'] as $category)
                    <li class="category"><span>{{ $category['name'] }}</span></li>
                @endforeach
            </ul>
            <!-- TODO: This seems hidden in original. Keep it?
            <div class="document-statuses">
                <div class="status">Status Label</div>
                @foreach ($document['statuses'] as $status)
                    <div class="category">{{ $status['label'] }}</div>
                @endforeach
            </div>
            -->
        </header>
        <div class="document-meta">
            <!-- Formerly "list-doc-info" -->
            <div class="document-info-list">
                <div class="date">
                    <span class="ng-scope">{{ trans('messages.updated') }}</span>
                    <time class="document-updated-at" datetime="{{ $document['updated_at'] }}">
                        {{ date('M d, Y', strtotime($document['updated_at'])) }}
                    </time>
                </div>
                <table class="document-stats">
                    <!-- This is hidden in the original markup, is it necessary?
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    -->
                    <tbody>
                        <tr>
                            <td>{{ $document['comment_count'] }}</td>
                            <td>{{ trans('messages.document.comments') }}</td>
                        </tr>
                        <tr>
                            <td>{{ $document['note_count'] }}</td>
                            <td>{{ trans('messages.document.notes') }}</td>
                        </tr>
                        <tr>
                            <td>{{ $document['user_count'] }}</td>
                            <td>{{ trans('messages.document.collaborators') }}</td>
                        </tr>
                    </tbody>
                </table>

                <ul class="document-dates">
                    @foreach ($document->dates as $date)
                        <li class="date">{{ $date['label'] }} on {{ date('M d, Y', strtotime($date['date'])) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="read-action">
                <a href="/documents/{{ $document['slug'] }}" class="read-more-button">{{ trans('messages.readmore') }}</a>
            </div>
        </div>
    </article>
