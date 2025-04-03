@extends('user.layouts.master')
@section('content')
	@include('user.partials.sidebar')
    
    @include('user.partials.topbar')
    
    @yield('panel')

    @include('user.partials.footer')
    @yield('modal')
@endsection
