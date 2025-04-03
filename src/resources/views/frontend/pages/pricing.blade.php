@extends('frontend.layouts.main')
@section('content')
    
    @include('frontend.pricing.section.breadcrumb_banner', ['title' => $title])
    @include('frontend.sections.plan')
    @include('frontend.sections.gateway')
    @include('frontend.sections.blog')
@endsection
