@extends('layouts.guest')
@section('auth-content')
    <div class="auth-area">
        <h3 class="section--title text-center text-white">@lang('Sign In To') <a href="{{route('home')}}" class="site-name">{{getArrayValue($setting->appearance, 'site_title')}}</a></h3>
        <form method="POST" action="{{route('login')}}" class="auth-form box-border box-shadow p-sm-5 p-3 rounded-3">
            @csrf
            <div class="form-group">
                <label for="email">@lang('Email Address')<sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" id="email" name="email" placeholder="@lang('Enter Email Address')" class="form--control" required="">
                </div>
            </div>

            <div class="form-group">
                <label for="password">@lang('Password') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-key"></i>
                    <input type="password" id="password" name="password" placeholder="@lang('Enter Password')" value="{{old('name')}}" class="form--control" required="">
                </div>
            </div>


            @if(getArrayValue($setting->google_login, 'status') == \App\Enums\SocialStatus::ENABLE->value)
                <a href="{{url('auth/google')}}" class="btn button--google w-100 mt-2 fs--18px">@lang('Continue with Google')</a>
            @endif

            <button type="submit" class="btn button--primary w-100 mt-2 fs--18px text-white">@lang('Login')</button>
            @if ($setting->registration_status == App\Enums\RegistrationStatus::ON->value)
                <p class="mt-3 text-center text-white">
                    <a href="{{route('register')}}" class="text-white">@lang('Create An Account')</a>
                </p>
            @endif

            <p class="mt-3 text-center">
                <a href="{{route('password.request')}}" class="text-white">@lang('Forgot Your Password')</a>
            </p>
        </form>
    </div>
@endsection
