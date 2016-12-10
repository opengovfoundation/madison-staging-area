<section class="home-feature">
    <article class="main-feature">
        <header class="document-info">
            <div class="document-thumbnail-container">
                <img src="wat.png" alt="" class="document-thumbnail">
            </div>
            <h2 class="heading">
                <a href="#" title="{document title!}" rel="bookmark">
                    This is a featured document!
                </a>
            </h2>

            <div class="document-sponsors">
                {{ trans('messages.document.sponsoredby') }}
                <ul>
                    <!-- TODO: Would be cool to link this to a sponsor view page -->
                    <!-- TODO: Would be cool to support multiple sponsors -->
                    <li class="document-sponsor">The Best Sponsor</li>
                </ul>
            </div>
            <div class="document-stats">
                <ul>
                    <li>3 {{ trans('messages.document.comments') }}</li>
                    <li>2 {{ trans('messages.document.notes') }}</li>
                    <li>{{ trans('messages.updates') }} Dec 7, 2016</li>
                </ul>
            </div>

            <!-- TODO: conditional "read more" button if no introtext? -->
        </header>
        <div class="document-content">
            <div class="document-summary">
                <p>This is some intro text! Blah blah blah.</p>
            </div>

            <a class="read-more-button" href="#" rel="bookmark" role="button"
                title="{document title!}">{{ trans('messages.readmore') }}</a>
        </div>
    </article>
</section>
