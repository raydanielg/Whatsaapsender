@extends('layouts.guest')
@section('auth-content')
    <div class="auth-area">
        <h2 class="section--title text-center text-white">@lang('Sign Up To') <a href="{{route('home')}}" class="site-name">{{getArrayValue($setting->appearance, 'site_title')}}</a></h2>
        <form method="POST" action="{{route('register')}}" class="auth-form box-border box-shadow p-sm-5 p-3 rounded-3">
            @csrf
            <div class="form-group">
                <label for="name">@lang('Name') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" id="name" name="name" placeholder="@lang('Enter Name')" value="{{old('name')}}" class="form--control" required="">
                </div>
            </div>

            <div class="form-group">
                <label for="email">@lang('Email Address') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" id="email" name="email" placeholder="@lang('Enter Valid Email Address')" value="{{old('email')}}" class="form--control" required="">
                </div>
            </div>

            <div class="form-group">
                <label for="password">@lang('Password') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-key"></i>
                    <input type="password" id="password" name="password" placeholder="@lang('Enter Password')" class="form--control" required="">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-lock"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="@lang('Enter Confirm Password')" class="form--control" required="">
                </div>
            </div>

            @if(getArrayValue($setting->google_login, 'status') == \App\Enums\SocialStatus::ENABLE->value)
                <a href="{{url('auth/google')}}" class="btn button--google w-100 mt-2 fs--18px">@lang('Continue with Google')</a>
            @endif

            <button type="submit" class="btn button--primary text-white w-100 mt-2 fs--18px">@lang('Submit')</button>

            <p class="mt-3 text-center text-white">
                @lang('Already have an account?') <a href="{{route('login')}}" class="text-white">@lang('Sign In')</a>
            </p>
        </form>
    </div>
@endsection
