@extends('admin.layouts.app')
@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@push('style-push')
<style type="text/css">
	.form-inner.disabled label {
        color: var(--bs-secondary-color);
        opacity: 0.65;
    }

    .form-inner.disabled .form-element-note {
        opacity: 0.65;
    }
</style>
@endpush
@section("panel")
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
      <div class="pill-tab mb-4">
        <ul class="nav" role="tablist">
         
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#gateway-usage" role="tab" aria-selected="true">
                <i class="ri-base-station-line"></i>
              {{ translate("Gateway Management") }} 
            </a>
          </li>

          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#authentication" role="tab" aria-selected="true">
              <i class="ri-notification-2-line"></i> 
              {{ translate("Authentication Settings") }} 
            </a>
          </li>

          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#onboarding" role="tab" aria-selected="false" tabindex="-1">
              <i class="ri-android-line"></i> 
              {{ translate("Onboarding Settings") }} 
            </a>
          </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane active fade show" id="gateway-usage" role="tabpanel">
            <div class="card">
                <div class="form-header">
                <h4 class="card-title">{{ translate("Gateway Management") }}</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                        @csrf
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Specify SMS API Method") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            <div class="form-inner">
                                                <label for="api_sms_method" 
                                                    class="form-label">
                                                    {{ translate("Select Default API Method") }}
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("This Method Will Be Used To Deliver Messages When API uses Admin Panel gateways")}}">
                                                        <i class="ri-information-line"></i>
                                                    </span>
                                                    <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Suggestions Note">
                                                        <button class="i-btn info--btn btn--sm d-xl-none info-note-btn"><i class="las la-info-circle"></i></button>
                                                    </div>
                                                </label>
                                                <select data-placeholder="{{translate('Select a method')}}" class="form-select select2-search" name="site_settings[api_sms_method]" id="api_sms_method">
                                                    <option value=""></option>
                                                    <option {{ \App\Enums\StatusEnum::FALSE->status() == site_settings('api_sms_method') ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("SMS Gateway (Default gateway will be used)") }}</option>
                                                    <option {{ \App\Enums\StatusEnum::TRUE->status() == site_settings('api_sms_method') ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Android Gateway (Random)") }}</option>
                                                </select>
                                                <p class="form-element-note">{{ translate("Click here to checkout the API document ") }} <a href="{{ route("admin.communication.api") }}">{{ translate("API Documentation") }}</a> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Specify SMS Gateways") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            <div class="form-inner">
                                                <label for="accessible-sms-api-gateways" 
                                                    class="form-label">
                                                    {{ translate("Default In-Application Sending Method") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate("This determines which method will be used to send user SMS Requests")}}">
                                                        <i class="ri-information-line"></i>
                                                    </span>
                                                </label>
                                                <select data-placeholder="{{translate('Select a method')}}" class="form-select select2-search" name="site_settings[in_application_sms_method]" id="in_application_sms_method">
                                                    <option value=""></option>
                                                    <option {{ \App\Enums\StatusEnum::TRUE->status() == site_settings('in_application_sms_method') ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("SMS Gateway") }}</option>
                                                    <option {{ \App\Enums\StatusEnum::FALSE->status() == site_settings('in_application_sms_method') ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="accessible-sms-api-gateways" 
                                                    class="form-label">
                                                    {{ translate("Select SMS API Gateway") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate("This selection will only effect those users who are using Admin's SMS API Gateway. Selected Gateway or Gateways will be used to deliver User's SMS Requests.")}}">
                                                        <i class="ri-information-line"></i>
                                                    </span>
                                                </label>
                                                @php
                                                    $selected_sms_api_gateways = json_decode(site_settings('accessible_sms_api_gateways'), true);
                                                @endphp
                                                <select data-placeholder="{{translate('Select API Gateways')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[accessible_sms_api_gateways][]" 
                                                    id="accessible-sms-api-gateways"
                                                    multiple>
                                                    @foreach($sms_api_gateways as $api_gateway)
                                                        <option value="{{ $api_gateway->id }}" {{ $selected_sms_api_gateways ? (in_array($api_gateway->id, $selected_sms_api_gateways) ? 'selected' : '') : '' }}>
                                                            {{ $api_gateway->type }} - {{ $api_gateway->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="form-element-note">
                                                    {{ translate("Click here to checkout your SMS API Gateways") }} 
                                                    <a href="{{ route("admin.gateway.sms.api.index") }}" target="_blank">{{ translate("SMS API Gateway") }}</a> 
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="accessible-sms-android-gateways" 
                                                    class="form-label">
                                                    {{ translate("Select SMS Android Gateway") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate("This selection will only effect those users who are using Admin's SMS Android Gateway. Selected Gateway or Gateways will be used to deliver User's SMS Requests.")}}">
                                                        <i class="ri-information-line"></i>
                                                    </span>
                                                </label>
                                                @php
                                                    $selected_sms_android_gateways = json_decode(site_settings('accessible_sms_android_gateways'), true);
                                                @endphp
                                                <select data-placeholder="{{translate('Select Android Gateways')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[accessible_sms_android_gateways][]" 
                                                    id="accessible-sms-android-gateways"
                                                    multiple>
                                                    @foreach($sms_android_gateways as $android_gateway)
                                                        <option value="{{$android_gateway->id}}" {{ $selected_sms_android_gateways ? (in_array($android_gateway->id, $selected_sms_android_gateways) ? 'selected' : '') : '' }}>{{$android_gateway->name}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="form-element-note">
                                                    {{ translate("Click here to checkout your SMS Android Gateways") }} 
                                                    <a href="{{ route("admin.gateway.sms.android.index") }}" target="_blank">{{ translate("Android Gateway") }}</a> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Specify Email Gateways") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            <div class="form-inner">
                                                <label for="accessible-email-gateways" 
                                                    class="form-label">
                                                    {{ translate("Select Email Gateway") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate("This selection will only effect those users who are using Admin's Email Gateway. Selected Gateway or Gateways will be used to deliver User's Email Requests.")}}">
                                                        <i class="ri-information-line"></i>
                                                    </span>
                                                </label>
                                                @php
                                                    $selected_email_gateways = json_decode(site_settings('accessible_email_gateways'), true);
                                                @endphp
                                                <select data-placeholder="{{translate('Select Email Gateways')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[accessible_email_gateways][]" 
                                                    id="accessible-email-gateways"
                                                    multiple>
                                                    @foreach($mail_gateways as $mail_gateway)
                                                        <option value="{{$mail_gateway->id}}" {{ $selected_email_gateways ? (in_array($mail_gateway->id, $selected_email_gateways) ? 'selected' : '') : '' }}>{{$mail_gateway->type}} - {{$mail_gateway->name}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="form-element-note">
                                                    {{ translate("Click here to checkout your Email Gateways") }} 
                                                    <a href="{{ route("admin.gateway.email.index") }}" target="_blank">{{ translate("Email Gateway") }}</a> 
                                                </p>
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
        <div class="tab-pane fade" id="authentication" role="tabpanel">
            <div class="card">
                <div class="form-header">
                <h4 class="card-title">{{ translate("Authentication Settings") }}</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                        @csrf
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Authentication") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        @foreach(json_decode(site_settings("member_authentication"), true) as $auth_key => $auth_param)

                                            <div class="col-md-6">
                                                @if($auth_key == "login_with")
                                                    <div class="form-inner">
                                                        <label for="login_with" class="form-label">{{ translate("Login With") }}</label>
                                                        <select data-placeholder="{{ translate("Choose member login parameters") }}" class="form-select select2-search" name="site_settings[member_authentication][{{ $auth_key }}][]" data-show="5" id="login_with" multiple="multiple">
                                                            <option value=""></option>
                                                            @foreach(config('setting.login_attribute')  as $auth )
                                                                <option @if(in_array($auth , json_decode(site_settings("member_authentication"), true)['login_with'] ?? [] )) selected @endif   value="{{$auth}}">{{$auth}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <div class="form-inner">
                                                        <label class="form-label"> {{ translate("Member ".textFormat(['_'], $auth_key, ' ')) }} </label>
                                                        <div class="form-inner-switch">
                                                            <label class="pointer" for="member_authentication_{{ $auth_key }}">{{ translate("Turn on/off Member ".textFormat(['_'], $auth_key, ' ')) }}</label>
                                                            <div class="switch-wrapper mb-1 checkbox-data">
                                                                <input {{ $auth_param == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}" type="checkbox" class="switch-input" id="member_authentication_{{ $auth_key }}" name="site_settings[member_authentication][{{ $auth_key }}]"/>
                                                                <label for="member_authentication_{{ $auth_key }}" class="toggle">
                                                                <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Verification Code") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-12 parent">
                                        <div class="form-inner">
                                            <label class="form-label"> {{ translate("OTP Verification") }} </label>
                                            <div class="form-inner-switch">
                                            <label class="pointer" for="registration_otp_verification">{{ translate("Turn on/off otp verification") }}</label>
                                            <div class="switch-wrapper mb-1 checkbox-data">
                                                <input {{ site_settings("registration_otp_verification") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="registration_otp_verification" name="site_settings[registration_otp_verification]"/>
                                                <label for="registration_otp_verification" class="toggle">
                                                <span></span>
                                                </label>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 child">
                                        <div class="form-inner">
                                            <label class="form-label"> {{ translate("Email OTP Verification") }} </label>
                                            <div class="form-inner-switch">
                                            <label class="pointer" for="email_otp_verification">{{ translate("Turn on/off Email otp verification") }}</label>
                                            <div class="switch-wrapper mb-1 checkbox-data">
                                                <input {{ site_settings("email_otp_verification") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="email_otp_verification" name="site_settings[email_otp_verification]"/>
                                                <label for="email_otp_verification" class="toggle">
                                                <span></span>
                                                </label>
                                            </div>
                                            </div>
                                            <p class="form-element-note text-danger">{{ translate("Requires a Default Email Gateway.")}} <a href="{{ route('admin.gateway.email.index') }}">{{ translate("Set up gateway") }}</a> </p>
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
        <div class="tab-pane fade" id="onboarding" role="tabpanel">
            <div class="card">
                <div class="form-header">
                <h4 class="card-title">{{ translate("Onboarding Settings") }}</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                        @csrf
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Rewards") }}</h5>
                                    </div>
                                    <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-md-12 parent">
                                            <div class="form-inner">
                                                <label class="form-label"> {{ translate("Onboarding Bonus") }} </label>
                                                <div class="form-inner-switch">
                                                <label class="pointer" for="onboarding_bonus">{{ translate("Turn on/off onboarding registration") }}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input {{ site_settings("onboarding_bonus") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="onboarding_bonus" name="site_settings[onboarding_bonus]"/>
                                                    <label for="onboarding_bonus" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 child">
                                            <div class="form-inner">
                                                <label for="onboarding_bonus_plan" class="form-label">{{ translate("Onboarding Reward Plan") }}</label>
                                                <select data-placeholder="{{translate('Select a plan')}}" class="form-select select2-search" name="site_settings[onboarding_bonus_plan]" data-show="5" id="onboarding_bonus_plan">
                                                    <option value=""></option>
                                                    @foreach($plans as $plan )
                                                        <option {{ site_settings("onboarding_bonus_plan") == $plan->id ? 'selected' : '' }} value="{{$plan->id}}">{{$plan->name}}</option>
                                                    @endforeach
                                                </select>
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
      </div>
    </div>
  </main>
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
@endpush
@push("script-push")

  <script>
    "use strict";
    $(document).ready(function() {
        
        let debounceTimer;
        select2_search($("#onboarding_bonus_plan").attr("data-placeholder"));
        setInitialVisibility();
        updateBackgroundClass();
        $('.parent input[type="checkbox"]').off('change').change(function() {
            toggleChildren();
        });

        $('.switch-input').on('change', function() {

            updateBackgroundClass();
        });
        $('.settingsForm').on('submit', function(e) {
            
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

    $(document).ready(function() {

        const inAppSmsMethod        = $('#in_application_sms_method');
        const apiGatewaysSelect     = $('#accessible-sms-api-gateways');
        const androidGatewaysSelect = $('#accessible-sms-android-gateways');

        function updateSelectFields() {

            const selectedValue = inAppSmsMethod.val();
            if (selectedValue === "{{ \App\Enums\StatusEnum::TRUE->status() }}") {

                apiGatewaysSelect.prop('disabled', false).closest('.form-inner').removeClass('disabled');
                androidGatewaysSelect.prop('disabled', true).closest('.form-inner').addClass('disabled');
            } else if (selectedValue === "{{ \App\Enums\StatusEnum::FALSE->status() }}") {

                apiGatewaysSelect.prop('disabled', true).closest('.form-inner').addClass('disabled');
                androidGatewaysSelect.prop('disabled', false).closest('.form-inner').removeClass('disabled');
            }
        }
        updateSelectFields();
        inAppSmsMethod.on('change', updateSelectFields);
    });
    
  </script>
@endpush
