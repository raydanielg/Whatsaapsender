@extends('layouts.guest')
@section('auth-content')
    <div class="auth-area">
        <h2 class="section--title text-center text-white">{{__($setTitle)}}</h2>
        <form method="POST" action="{{route('user.email.verification')}}" class="auth-form box-border box-shadow p-sm-5 p-3 rounded-3">
            @csrf
            <div class="form-group">
                <label for="email_verified_code">@lang('Verify Code') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-key"></i>
                    <input type="text" id="email_verified_code" name="email_verified_code" placeholder="@lang('Enter Code')" class="form--control" required="">
                </div>
            </div>
            <button type="submit" class="btn button--primary w-100 mt-2 fs--18px text-white">@lang('Submit')</button>
        </form>
    </div>
@endsection
