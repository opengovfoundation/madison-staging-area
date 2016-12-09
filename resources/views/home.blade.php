@extends('layouts.app')

@section('content')
<div class="container">
    <section class="home-feature">
        <article class="main-feature">
            <header class="document-info">
                <div class="document-thumbnail-container">
                    <img src="wat.png" alt="" class="document-thumbnail">
                </div>
                <h2 class="heading">
                    <a href="#" title="wat" rel="bookmark">
                        This is a featured document!
                    </a>
                </h2>

                <div class="document-sponsors">
                    Sponsored By:
                    <ul>
                        <!-- TODO: Would be cool to link this to a sponsor view page -->
                        <!-- TODO: Would be cool to support multiple sponsors -->
                        <li class="document-sponsor">The Best Sponsor</li>
                    </ul>
                </div>
                <div class="document-stats">
                    <ul>
                        <li>3 Comments</li>
                        <li>2 Notes</li>
                        <li>Updated Dec 7, 2016</li>
                    </ul>
                </div>

                <!-- TODO: conditional "read more" button if no introtext? -->
            </header>
            <div class="document-content">
                <div class="document-summary">
                    <p>This is some intro text! Blah blah blah.</p>
                </div>

                <a class="read-more-button" href="#" rel="bookmark" role="button"
                    title="Read More of {document title!}">Read More</a>
            </div>
        </article>
    </section>

    <!-- Previously "previous-features" -->
    <section class="active-documents">
        <h2 class="heading"></h2>
    </section>
</div>
@endsection
