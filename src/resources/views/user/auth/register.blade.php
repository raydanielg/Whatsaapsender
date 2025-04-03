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
                <h3>{{ translate("Sign in") }}</h3>
                <form action="{{route('register.store')}}" method="POST" id="login-form" class="auth-form">
                    @csrf
                    <div class="form-element">
                        <label for="name" class="form-label">{{ translate("Name") }}</label>
                        <input type="name" name="name" value="{{old('name')}}" placeholder="{{ translate('Enter your name')}}" id="user" aria-describedby="user name" class="form-control"/>
                    </div>
                    <div class="form-element">
                        <label for="email" class="form-label">{{ translate("Email Address") }}</label>
                        <input type="email" name="email" value="{{old('email')}}" placeholder="{{ translate('Enter your email address')}}" id="user" aria-describedby="user email" class="form-control"/>
                    </div>
                    <div class="form-element">
                        <label for="password" class="form-label">{{ translate("Password") }}</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" aria-label="password" />
                    </div>
                    <div class="form-element">
                        <label for="password_confirmation" class="form-label">{{ translate("Confirm your Password") }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Enter password confirmation" aria-label="password_confirmation" />
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
                    type="submit">{{ translate("Register") }} 
                    <i class="ri-arrow-right-line fs-18"></i>
                </button>
                </form>
                
                <div class="mt-3">
                    <div class="auth-form-bottom">
                       
                        <div class="mt-20 text-center">
                            <p class="fw-semibold"> {{ translate("Already have an account?") }} <a class="text-primary text-decoration-underline" href="{{route('login')}}" >{{ translate('Sign In To')}} {{ucfirst(site_settings("site_name"))}}?</a>
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
            console.log("hello")
            function onSubmit(token) {
                document.getElementById("login-form").submit();
            }
        </script>
    @endif
@endpush