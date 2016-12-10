<section class="document-search-form-container">
    <h3>{{ trans('messages.search.title') }}</h3>

    <!-- TODO: Hook up this form to a controller method -->
    <form action="" id="document-search-form" method="post">
        <input type="text" name="search-text"
            placeholder="{{ trans('messages.search.placeholder') }}">

        <button class="document-search-button">
            {{ trans('messages.submit') }}
        </button>
    </form>
</section>

<section class="document-search-results">
    <!-- TODO: Real pagination! -->
    <ul class="pagination">
        <li>
            <a href="#" title="{{ trans('messages.pagination.first.title') }}">
                {{ trans('messages.pagination.first.label') }}
            </a>
        </li>
        <li>
            <a href="#" title="{{ trans('messages.pagination.previous.title') }}">
                {{ trans('messages.pagination.previous.label') }}
            </a>
        </li>

        <li>
            <a href="#" title="{{ trans('messages.pagination.page') }}">1</a>
        </li>

        <li>
            <a href="#" title="{{ trans('messages.pagination.next.title') }}">
                {{ trans('messages.pagination.next.label') }}
            </a>
        </li>
        <li>
            <a href="#" title="{{ trans('messages.pagination.last.title') }}">
                {{ trans('messages.pagination.last.label') }}
            </a>
        </li>
    </ul>

    <h2>{{ trans('messages.recentlegislation') }}</h2>

    <!-- TODO: Only show this if category filter is on -->
    <div class="category-filter">
        {{ trans('messages.document.categories') }}
        <div class="category" role="button">Selected Category!</div>
        <div class="clear-category" role="button">{{ trans('messages.clear') }}</div>
    </div>
    <div class="search-filter">
        {{ trans('messages.searchdisplay') }}
        <span class="search-query">This is a search query</span>
        <div class="clear-search" role="button">{{ trans('messages.clear') }}</div>
    </div>

    <!-- TODO: Loop through returned articles here -->
    <article class="document-list-item">
        <header class="document-info">
            <h3 class="subheading">
                <a href="#" title="DOCUMENT TITLE HERE">
                    The Title of a Document
                </a>
            </h3>
            <div class="document-sponsors">
                <!-- TODO: Make this conditional on closed discussion state -->
                <span data-toggle="tooltip" data-placement="top"
                    class="fa-stack discussion-closed"
                    title="{{ trans('messages.document.discussionstate.closed.tooltip') }}">

                    <!-- Icon for closed discussion -->
                    <i class="fa fa-comment fa-flip-horizontal fa-stack-1x"></i>
                    <i class="fa fa-ban fa-stack-2x text-danger"></i>
                </span>

                {{ trans('messages.document.sponsoredby') }}
                <ul>
                    <!-- TODO: Would be cool to link this to a sponsor view page -->
                    <!-- TODO: Would be cool to support multiple sponsors -->
                    <li class="document-sponsor">The Best Sponsor</li>
                </ul>
            </div>
            <div class="document-categories">
                <ul>
                    <li class="category"><span>Look! A Category!</li>
                    <li class="category"><span>And Another!</span></li>
                </ul>
            </div>
            <div class="document-statuses">
                <div class="status">Status Label</div>
            </div>
        </header>
    </article>

</section>
<div class="document-meta">
    <!-- Formerly "list-doc-info" -->
    <div class="document-info-list">
        <div class="date">
            <span class="ng-scope">{{ trans('messages.updated') }}</span>
            <time class="document-updated-at" datetime="2016-12-07T20:03:23+00:00">
                Dec 7, 2016
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
                    <td>999</td>
                    <td>{{ trans('messages.document.comments') }}</td>
                </tr>
                <tr>
                    <td>999</td>
                    <td>{{ trans('messages.document.notes') }}</td>
                </tr>
                <tr>
                    <td>999</td>
                    <td>{{ trans('messages.document.collaborators') }}</td>
                </tr>
            </tbody>
        </table>

        <ul class="document-dates">
            <li class="date">Some Event on Dec 100, 1999</li>
        </ul>
    </div>
    <div class="read-action">
        <a href="#" class="read-more-button">{{ trans('messages.readmore') }}</a>
    </div>
</div>
