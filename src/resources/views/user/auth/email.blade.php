@extends('user.layouts.auth')
@section('content')
@php
    $socialProviders = json_decode(site_settings('social_login_with'),true);
    $mediums = [];
    foreach($socialProviders as $key=>$login_medium){
        if($login_medium['status'] == App\Enums\StatusEnum::TRUE->status()){
            array_push($mediums, str_replace('_oauth',"",$key));
        }
    }
    $googleCaptcha = (object) json_decode(site_settings("google_recaptcha"));
    
@endphp
<section class="auth">
    <div class="container-fluid px-0">
      <div class="auth-wrapper">
        <div class="row g-0">
          @include('frontend.auth.partials.content')
          <div class="col-lg-6 order-lg-1 order-0">
            <div class="auth-right">
              <a href="{{ url('/') }}">
                <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="">
              </a>
              <div class="auth-form-wrapper">
                <h3>{{ translate("Verify your Email") }}</h3>
                <form action="{{route('user.store.email.verification')}}" id="login-form" method="POST">
                    @csrf
                  <div class="form-element">
                    <label for="code" class="form-label">{{ translate("Verification Code") }}</label>
                    <input type="text" name="code" placeholder="{{ translate('Enter Verification Code')}}" id="code" aria-describedby="code" class="form-control"/>
                  </div>
                  <button 
                @if(site_settings('captcha') == \App\Enums\StatusEnum::TRUE->status() && site_settings('captcha_with_login') == \App\Enums\StatusEnum::TRUE->status() && $googleCaptcha->status == \App\Enums\StatusEnum::TRUE->status())
                        class="g-recaptcha i-btn btn--primary bg--gradient btn--xl rounded-3 w-100 mt-2"
                        data-sitekey="{{$googleCaptcha->key}}"
                        data-callback='onSubmit'
                        data-action='register'
                    @else
                        class="i-btn btn--primary bg--gradient btn--xl rounded-3 w-100 mt-2"
                    @endif
                    type="submit">{{ translate("Sign In") }} 
                    <i class="ri-arrow-right-line fs-18"></i>
                </button>
                </form>
                
                <div class="mt-3">
                    <div class="auth-form-bottom">
                        @if(site_settings("social_login") == \App\Enums\StatusEnum::TRUE->status())
                        <div class="divider">
                            <span>{{ translate('Or continue with')}}</span>
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-6">
                            <a href="{{url('auth/google')}}" class="sign-option-btn">
                                <span class="sign-option-logo">
                                    <i class="fa-brands fa-google fs-5"></i>
                                </span> {{ translate("Google") }} </a>
                            </div>
                        </div>
                        @endif
                        <div class="mt-20 text-center">
                            <p class="fw-semibold"> {{translate('Please check including your Junk/Spam Folder. if not found, you can')}} <a href="{{route('user.email.verification')}}" class="text-primary text-decoration-underline">{{translate('Resend code')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
@push('script-push')
@if(site_settings('captcha') == \App\Enums\StatusEnum::TRUE->status() && site_settings('captcha_with_login') == \App\Enums\StatusEnum::TRUE->status() && $googleCaptcha->status == \App\Enums\StatusEnum::TRUE->status())
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            'use strict'
            function onSubmit(token) {
                document.getElementById("login-form").submit();
            }
        </script>
    @endif
@endpush