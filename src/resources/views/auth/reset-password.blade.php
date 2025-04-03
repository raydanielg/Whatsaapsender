@extends('layouts.guest')
@section('auth-content')
    <div class="auth-area">
        <h2 class="section--title text-center text-white">@lang('Reset your password')</h2>
        <form method="POST" action="{{route('password.update')}}" class="auth-form box-border box-shadow p-sm-5 p-3 rounded-3">
            @csrf
            <input type="hidden" name="token" value="{{$token->token}}">
            <input type="hidden" name="email" value="{{$token->email}}">

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
                    <i class="las la-key"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="@lang('Enter Confirm Password')" class="form--control" required="">
                </div>
            </div>

            <button type="submit" class="btn button--primary w-100 mt-2 fs--18px text-white">@lang('Reset Password')</button>

            <p class="mt-3 text-center text-white">
                @lang('Already have an account?') <a href="{{route('login')}}" class="text-white">@lang('Sign In')</a>
            </p>
        </form>
    </div>
@endsection
