@extends('frontend.layouts.default')

@include('frontend.layouts.navigation')

@section('title'){{ $page->title }} - @stop

@section('content')
    <div class="page-header">
        <h1>{{ $page->title }}</h1>
    </div>
    <div class="page-content">
        {!! $page->content !!}
    </div>
@stop