@extends('pdo::layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')
    <p class="text-center">
      {{ trans('installer_messages.final.finished') }} 
    </p>
    <br>
    <p class="text-center">
      {!! trans('installer_messages.final.admin_access') !!} 
    </p> 
    <div class="buttons">
        <a href="{{ url('/admin') }}" class="button">{{ trans('installer_messages.final.admin_portal') }}</a>
    </div>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.user_portal') }}</a>
    </div>
@endsection
