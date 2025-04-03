@extends('frontend.layouts.main')
@section('content')
    
    @include('frontend.about.section.breadcrumb_banner', ['title' => $title])
    @include('frontend.about.section.overview')
    @include('frontend.about.section.connect')
    @include('frontend.sections.blog')
@endsection
