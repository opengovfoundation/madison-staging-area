<!-- Previously "previous-features" -->
<section class="active-documents">
    <h2 class="heading">{{ trans('messages.activelegislation') }}</h2>

    <div class="recent-activity">
        <h3>{{ trans('messages.recentactivity') }}</h3>

        <!-- TODO: Loop through recently active docs here -->
        <article>
            <a href="#" class="document-title">A Document Title!</a>
            <span class="date">
                {{ trans('messages.updated') }} 1 million days ago
            </span>
        </article>
    </div>

    <div class="move-active">
        <h3>{{ trans('messages.mostactive') }}</h3>

        <!-- TODO: Loop through most active docs here -->
        <article>
            <a href="#" class="document-title">A Document Title!</a>
            <div class="document-stats">
                <ul>
                    <li>3 {{ trans('messages.document.comments') }}</li>
                    <li>2 {{ trans('messages.document.notes') }}</li>
                </ul>
            </div>
        </article>
    </div>
</section>
