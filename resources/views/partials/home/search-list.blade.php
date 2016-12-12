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
    <!-- Pagination! Thanks Laravel! -->
    {{ $documents->links() }}

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

    @each('partials.home.document-list-item', $documents, 'document')

</section>
