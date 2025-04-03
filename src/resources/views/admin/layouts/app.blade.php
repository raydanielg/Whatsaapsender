@extends('admin.layouts.master')
@section('content')
	@include('admin.partials.sidebar')
    
    @include('admin.partials.topbar')
    @yield('panel')

    @include('admin.partials.footer')
    @yield('modal')
    @yield('off-canvas')
@endsection
