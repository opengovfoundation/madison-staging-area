@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-6">
            @include('partials/home/featured-documents')
            @include('partials/home/active-documents')
        </div>

        <div class="col-md-6">
            @include('partials/home/welcome')
            @include('partials/home/search-list')
        </div>
    </div>

</div>
@endsection
