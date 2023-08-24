@extends('Front::layouts.master')

@section('content')
<main id="index">
<article class="container article">
    @include('Front::layouts.header-ads')
    @include('Front::layouts.top-info')
    @include('Front::layouts.latest-courses')
    @include('Front::layouts.popular-courses')
</article>
</main>
@include('Front::layouts.latest-articles')
@endsection
