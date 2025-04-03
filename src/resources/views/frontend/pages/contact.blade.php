@extends('frontend.layouts.main')
@section('content')
    
    @include('frontend.contact.section.breadcrumb_banner', ['title' => $title])
    @include('frontend.contact.section.get_in_touch')
    @include('frontend.sections.plan')
    @include('frontend.sections.gateway')
    @include('frontend.sections.blog')
@endsection
