@extends('frontend.layouts.main')
@section('content')
    
    @include('frontend.service.section.breadcrumb_banner', ['type' => $type, 'title' => $title])
    @include('frontend.service.section.overview', ['type' => $type])
    @include('frontend.service.section.feature', ['type' => $type])
    @include('frontend.service.section.details', ['type' => $type])
    @include('frontend.service.section.highlight', ['type' => $type])
    @include('frontend.sections.plan')
    @include('frontend.sections.gateway')
    @include('frontend.sections.blog')
@endsection
