@extends('layouts.guest')
@section('auth-content')
    <div class="auth-area">
        <h2 class="section--title text-center text-white">@lang('Reset Password')</h2>
        <form method="POST" action="{{ route('password.email') }}" class="auth-form box-border box-shadow p-sm-5 p-3 rounded-3">
            @csrf
            <div class="form-group">
                <label for="email">@lang('Email Address') <sup class="text--danger">*</sup></label>
                <div class="auth-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" id="email" name="email" placeholder="@lang('Enter Valid Email Address')" value="{{old('email')}}" class="form--control" required="">
                </div>
            </div>
            <button type="submit" class="btn button--primary text-white w-100 mt-2 fs--18px">@lang('Submit')</button>
        </form>
    </div>
@endsection
