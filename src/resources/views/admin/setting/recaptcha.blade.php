@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ $title }}</h2>
                <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body pt-0">
                <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                    @csrf
                    <div class="form-element">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Configuration") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-inner parent">
                                            <label class="form-label"> {{ translate("CAPTCHA Verification") }} </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" for="captcha">{{ translate("Turn on/off CAPTCHA verification") }}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input {{ site_settings("captcha") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="captcha" name="site_settings[captcha]"/>
                                                    <label for="captcha" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="form-element-note text-danger">{{ translate("Enables/disables CAPTCHA verification") }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-element child d-none">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Google reCAPTCHA") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    @foreach( json_decode(site_settings("google_recaptcha"), true) as $recaptcha_parameter => $parameter_value) 
                                        <div class=" {{ $loop->first ? 'col-med-12' : 'col-md-6' }}">
                                            
                                            @if($recaptcha_parameter == 'status')

                                                <div class="form-inner child">
                                                    <label class="form-label"> {{ translate("Google reCAPTCHA") }} </label>
                                                    <div class="form-inner-switch">
                                                        <label class="pointer" for="{{ $recaptcha_parameter }}">{{ translate("Turn on/off google reCAPTCHA") }}</label>
                                                        <div class="switch-wrapper mb-1 checkbox-data">
                                                            <input {{ $parameter_value == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : ''}} type="checkbox" class="switch-input" id="{{ $recaptcha_parameter }}" name="site_settings[google_recaptcha][{{ $recaptcha_parameter }}]"/>
                                                            <label for="{{ $recaptcha_parameter }}" class="toggle">
                                                            <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            @else
                                                <div class="form-inner child">
                                                    <label for="{{ $recaptcha_parameter }}" class="form-label"> {{ translate("Google reCAPTCHA ").$recaptcha_parameter }} </label>
                                                    <input type="text" id="{{ $recaptcha_parameter }}" name="site_settings[google_recaptcha][{{$recaptcha_parameter}}]" class="form-control" placeholder="{{ translate('Enter the google reCAPTCHA ').$recaptcha_parameter }}" aria-label="{{ translate('Enter the google reCAPTCHA ').$recaptcha_parameter }}" value="{{ $parameter_value }}"/>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-element child d-none">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Applicability") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label class="form-label"> {{ translate("Registration CAPTCHA Verification") }} </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" for="captcha_with_registration">{{ translate("Turn on/off registration CAPTCHA verification") }}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input {{ site_settings("captcha_with_registration") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="captcha_with_registration" name="site_settings[captcha_with_registration]"/>
                                                    <label for="captcha_with_registration" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="form-element-note text-danger">{{ translate("Enables/disables member registration CAPTCHA verification") }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label class="form-label"> {{ translate("Login CAPTCHA Verification") }} </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" for="captcha_with_login">{{ translate("Turn on/off login CAPTCHA verification") }}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input {{ site_settings("captcha_with_login") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="captcha_with_login" name="site_settings[captcha_with_login]"/>
                                                    <label for="captcha_with_login" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="form-element-note text-danger">{{ translate("Enables/disables member login CAPTCHA verification") }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   

                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                            <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

@push('script-push')
    <script>
        "use strict";
        $(document).ready(function() {
            setInitialVisibility();
            updateBackgroundClass();
            
            $('.parent input[type="checkbox"]').change(function() {

                toggleChildren();
            });
            $('.switch-input').on('change', function() {

                updateBackgroundClass();
            });
            $('form').on('submit', function(e) {
                $('.checkbox-data').each(function() {
                    var $checkbox = $(this).find('.switch-input');
                    var $hiddenInput = $(this).find('input[type="hidden"]');

                    if ($checkbox.is(':checked')) {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::TRUE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::TRUE->status() }}');
                        }
                    } else {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::FALSE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::FALSE->status() }}');
                        }
                    }
                });
            });
        });
    </script>
@endpush
