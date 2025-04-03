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
@section('panel')
<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="page-header-left">
          <h2>{{ translate('User information')}}</h2>
          <div class="breadcrumb-wrapper">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="{{ route('admin.dashboard') }}">{{ translate("Dashboard") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  {{ translate("User Profile") }}
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      
      <div class="row g-4">
        <div class="col-12">
          <div class="row g-4">
            <div class="col-xxl-3 col-lg-6">
              <div class="card card-height-100">
                <div class="card-header pb-0">
                  <div class="card-header-left">
                    <h4 class="card-title">{{ translate("Basic Information") }}</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="profile-content">
                    <div class="d-flex align-items-start gap-3">
                      <span class="customer-img">
                        <img src="{{showImage(filePath()['profile']['user']['path'].'/'.$user->image)}}" alt="{{ translate('Profile Image')}}" class="rounded w-100 h-100">
                      </span>
                      <div>
                        <h5 class="fs-16 mb-1 d-flex align-items-start gap-2 flex-wrap"> {{$user->name}}
                            {{-- <span class="i-badge dot success-soft pill">online</span> --}}
                        </h5>
                        <a class="text-muted fs-14" href="mailto:noah@gmail.com">{{$user->email}}</a>
                        <p class="text-muted fs-14"> {{translate('Joining Date')}} {{getDateTime($user->created_at,'d M, Y h:i A')}} </p>
                      </div>
                    </div>
                    <ul class="mt-4 d-flex flex-column gap-1">
                      <li class="d-flex align-items-center justify-content-between gap-3">
                        <span class="fs-14 i-badge dot info-soft bg-transparent">
                          <span class="text-dark">{{ translate("SMS") }}</span>
                        </span>
                        <span class="fs-14"> {{$user->sms_credit}} {{ translate('credit')}} </span>
                      </li>
                      <li class="d-flex align-items-center justify-content-between gap-3">
                        <span class="fs-14 i-badge dot danger-soft bg-transparent">
                          <span class="text-dark">{{ translate("Email") }}</span>
                        </span>
                        <span class="fs-14">{{ $user->email_credit}} {{ translate('credit')}} </span>
                      </li>
                      <li class="d-flex align-items-center justify-content-between gap-3">
                        <span class="fs-14 i-badge dot success-soft bg-transparent">
                          <span class="text-dark">{{ translate("Whatsapp") }}</span>
                        </span>
                        <span class="fs-14"> {{$user->whatsapp_credit}} {{ translate('credit')}} </span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-3 col-lg-6">
              <div class="card feature-card">
                <div class="card-header pb-0">
                  <div class="card-header-left">
                    <h4 class="card-title">{{ translate("SMS Statistics") }}</h4>
                  </div>
                  <div class="card-header-right">
                    <span class="fs-3 text-info">
                      <i class="ri-message-2-line"></i>
                    </span>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row g-2">
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-info">
                            <i class="ri-message-2-line"></i>
                          </span>
                          <small>{{ translate("All") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['sms']['all']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-success">
                            <i class="ri-mail-check-line"></i>
                          </span>
                          <small>{{ translate("Success") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['sms']['success']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-warning">
                            <i class="ri-hourglass-fill"></i>
                          </span>
                          <small>{{ translate("Pending") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['sms']['pending']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-danger">
                            <i class="ri-mail-close-line"></i>
                          </span>
                          <small>{{ translate("Failed") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['sms']['failed']}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-3 col-lg-6">
              <div class="card feature-card">
                <div class="card-header pb-0">
                  <div class="card-header-left">
                    <h4 class="card-title">{{ translate("Email Statistics") }}</h4>
                  </div>
                  <div class="card-header-right">
                    <span class="fs-3 text-danger">
                      <i class="ri-mail-line"></i>
                    </span>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row g-2">
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-info">
                            <i class="ri-mail-line"></i>
                          </span>
                          <small>{{ translatE("All") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['email']['all']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-success">
                            <i class="ri-mail-check-line"></i>
                          </span>
                          <small>{{ translate("Success") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['email']['success']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-warning">
                            <i class="ri-hourglass-fill"></i>
                          </span>
                          <small>{{ translate("Pending") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['email']['pending']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-danger">
                            <i class="ri-mail-close-line"></i>
                          </span>
                          <small>{{ translate("Failed") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['email']['failed']}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-3 col-lg-6">
              <div class="card feature-card">
                <div class="card-header pb-0">
                  <div class="card-header-left">
                    <h4 class="card-title">{{ translate("Whatsapp Statistics") }}</h4>
                  </div>
                  <div class="card-header-right">
                    <span class="fs-3 text-success">
                      <i class="ri-whatsapp-line"></i>
                    </span>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row g-2">
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-info">
                            <i class="ri-whatsapp-line"></i>
                          </span>
                          <small>{{ translate("All") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['whats_app']['all']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-success">
                            <i class="ri-mail-check-line"></i>
                          </span>
                          <small>{{ translate("Success") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['whats_app']['success']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-warning">
                            <i class="ri-hourglass-fill"></i>
                          </span>
                          <small>{{ translate("Pending") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['whats_app']['pending']}}</p>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="feature-status">
                        <div class="feature-status-left">
                          <span class="feature-icon text-danger">
                            <i class="ri-mail-close-line"></i>
                          </span>
                          <small>{{ translate("Failed") }}</small>
                        </div>
                        <p class="feature-status-count">{{$logs['whats_app']['failed']}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card">
            <div class="form-header">
              <h4 class="card-title">{{ translate("Update user profile information") }}</h4>
            </div>
            <div class="card-body pt-0">
                <form action="{{route('admin.user.update', $user->id)}}" method="POST" enctype="multipart/form-data" class="user-details-form">
                    @csrf
                <div class="form-element">
                  <div class="row gy-4">
                    <div class="col-xxl-2 col-xl-3">
                      <h5 class="form-element-title">{{ translate("Update details") }}</h5>
                    </div>
                    <div class="col-xxl-8 col-xl-9">
                      <div class="row g-4">
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="name" class="form-label">{{ translate('Name')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{@$user->name}}" placeholder="{{ translate('Enter Name')}}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="email" class="form-label">{{ translate('Email')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="email" id="email" class="form-control" value="{{@$user->email}}" >
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="address" class="form-label">{{ translate('Address')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="address" id="address" class="form-control" value="{{@$user->address->address}}" placeholder="{{ translate('Enter Address')}}">
                            <p class="form-element-note">{{ translate("Put user address") }}</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="city" class="form-label">{{ translate('City')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="city" id="city" class="form-control" value="{{@$user->address->city}}" placeholder="{{ translate('Enter City')}}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="state" class="form-label">{{ translate('State')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="state" id="state" class="form-control" value="{{@$user->address->state}}" placeholder="{{ translate('Enter State')}}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="zip" class="form-label">{{ translate('Zip')}} <sup class="text--danger">*</sup></label>
                            <input type="text" name="zip" id="zip" class="form-control" value="{{@$user->address->zip}}" placeholder="{{ translate('Enter Zip')}}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-inner">
                            <label for="pricing_plan" class="form-label">{{ translate("User's Pricing Plan")}} <sup class="text--danger">*</sup></label>
                            <select class="form-select select2-search" data-placeholder="{{ translate("Select a pricing plan") }}" data-show="5" name="pricing_plan" id="pricing_plan">
                              <option value=""></option>
                              @foreach($pricing_plans as $identifier => $name)
                                    <option value="{{ $identifier }}" @if($user->runningSubscription()?->currentPlan() && $user->runningSubscription()?->currentPlan()->id == $identifier) selected @endif>{{ $name}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item">
                                <label for="status" class="form-label">{{ translate('Status')}} <sup class="text--danger">*</sup></label>
                                <select class="form-select select2-search" data-placeholder="{{ translate("Select a status") }}" name="status" id="status">
                                    <option value=""></option>
                                    <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}" @if($user->status == \App\Enums\StatusEnum::TRUE->status()) selected @endif>{{ translate('Active')}}</option>
                                    <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}" @if($user->status == \App\Enums\StatusEnum::FALSE->status() ) selected @endif>{{ translate('Banned')}}</option>
                                </select>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-element">
                  <div class="row gy-4">
                      <div class="col-xxl-2 col-xl-3">
                          <h5 class="form-element-title">{{ translate("Admin gateway access") }}</h5>
                      </div>
                      <div class="col-xxl-8 col-xl-9">
                          <div class="row gy-4">
                              <div class="col-12">
                                  <div class="form-inner">
                                    <label class="form-label"> 
                                      {{ translate("Toggle User Specifc Admin Gateway Access") }} 
                                      <span data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-title="{{ translate("Turning this toggle on will ignore the default admin gateway access and use these settings instead")}}">
                                        <i class="ri-information-line"></i>
                                      </span>
                                    </label>
                                    <div class="form-inner-switch">
                                      <label class="pointer" for="specific_gateway_access">{{ translate("Turn on/off user Specific Gateway Access") }}</label>
                                      <div class="switch-wrapper mb-1 checkbox-data">
                                          <input {{ isset($user->gateway_credentials->specific_gateway_access) && $user->gateway_credentials->specific_gateway_access == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="specific_gateway_access" name="specific_gateway_access"/>
                                          <label for="specific_gateway_access" class="toggle">
                                          <span></span>
                                          </label>
                                      </div>
                                    </div>
                                    <p class="form-element-note mt-3"> <a target="_blank" class="text-primary" href="{{ route("admin.system.setting", ["type" => "member"]) }}">{{ translate("Click here") }}</a> {{ translate(" then navigate to the 'Gateway Management' tab to update default gateways") }}</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="form-element admin-gateway-access d-none">
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
                                              data-bs-title="{{ translate("This determines which method will be used to send User: ").$user->name.translate(", SMS Requests")}}">
                                              <i class="ri-information-line"></i>
                                          </span>
                                      </label>
                                      <select data-placeholder="{{translate('Select a method')}}" class="form-select select2-search" name="in_application_sms_method" id="in_application_sms_method">
                                          <option value=""></option>
                                          <option {{ isset($user->gateway_credentials->in_application_sms_method) && $user->gateway_credentials->in_application_sms_method && $user->gateway_credentials->in_application_sms_method == \App\Enums\StatusEnum::TRUE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("SMS Gateway") }}</option>
                                          <option {{ isset($user->gateway_credentials->in_application_sms_method) && $user->gateway_credentials->in_application_sms_method == \App\Enums\StatusEnum::FALSE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
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
                                              data-bs-title="{{ translate("This selection will only effect User: ").$user->name.translate(", while they are using Admin's SMS API Gateway. Selected Gateway or Gateways will be used to deliver User: ").$user->name.translate(", SMS Requests.")}}">
                                              <i class="ri-information-line"></i>
                                          </span>
                                      </label>
                                     
                                      @php
                                          $selected_sms_api_gateways = isset($user->gateway_credentials->accessible_sms_api_gateways) ? (array)$user->gateway_credentials->accessible_sms_api_gateways : [];
                                      @endphp
                                      <select data-placeholder="{{translate('Select API Gateways')}}" 
                                          class="form-select select2-search" 
                                          name="accessible_sms_api_gateways[]" 
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
                                              data-bs-title="{{ translate("This selection will only effect User: ").$user->name.translate(", while they are using Admin's SMS Android Gateway. Selected Gateway or Gateways will be used to deliver User: ").$user->name.translate(", SMS Requests.")}}">
                                              <i class="ri-information-line"></i>
                                          </span>
                                      </label>
                                      @php
                                          $selected_sms_android_gateways = isset($user->gateway_credentials->accessible_sms_android_gateways) ? (array)$user->gateway_credentials->accessible_sms_android_gateways : [];
                                      @endphp
                                      <select data-placeholder="{{translate('Select Android Gateways')}}" 
                                          class="form-select select2-search" 
                                          name="accessible_sms_android_gateways[]" 
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
                <div class="form-element admin-gateway-access d-none">
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
                                                data-bs-title="{{ translate("This selection will only effect User: ").$user->name.translate(", while they are using Admin's Email Gateway. Selected Gateway or Gateways will be used to deliver User: ").$user->name.translate(", Email Requests.")}}">
                                                <i class="ri-information-line"></i>
                                            </span>
                                        </label>
                                        @php

                                            $selected_email_gateways = isset($user->gateway_credentials->accessible_email_gateways) ? (array)$user->gateway_credentials->accessible_email_gateways : [];
                                        @endphp
                                        <select data-placeholder="{{translate('Select Email Gateways')}}" 
                                            class="form-select select2-search" 
                                            name="accessible_email_gateways[]" 
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
                <div class="form-action">
                  <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Update") }} </button>
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
    select2_search($('.select2-search').data('placeholder'));
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

      $('.user-details-form').on('submit', function(e) {
            
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
      function checkSpecificGatewayAccess() {
        if ($('#specific_gateway_access').is(':checked')) {
            $('.admin-gateway-access').removeClass('d-none');
        } else {
            $('.admin-gateway-access').addClass('d-none');
        }
      }
      checkSpecificGatewayAccess();
      $('#specific_gateway_access').on('change', function() {
        checkSpecificGatewayAccess();
      });
    });


  </script>
@endpush
