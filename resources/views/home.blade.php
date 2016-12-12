@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-6">
            <section class="home-feature">
                @each('partials/home/featured-document', $featuredDocuments, 'document')
            </section>

            @include('partials/home/active-documents', [
                'mostActiveDocuments' => $mostActiveDocuments,
                'mostRecentDocuments' => $mostRecentDocuments,
            ])
        </div>

        <div class="col-md-6">
            @include('partials/home/welcome')
            @include('partials/home/search-list', [
                'documents' => $documents
            ])
        </div>
    </div>

</div>
@endsection
