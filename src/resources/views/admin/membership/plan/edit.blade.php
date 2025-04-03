@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
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
                    <a href="{{ route("admin.dashboard") }}">
                      {{ translate("Dashboard") }}
                    </a>
                </li>
                <li class="breadcrumb-item active" 
                    aria-current="page"> 
                  {{ translate("Create Plan") }} 
                </li>
                </ol>
            </nav>
            </div>
        </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form action="{{route('admin.membership.plan.update')}}" 
          method="POST" 
          enctype="multipart/form-data">
          @csrf
          <input hidden 
            type="text" 
            name="id" 
            value="{{$plan->id}}">
          @include('admin.membership.plan.partials.edit.general', ['plan' => $plan])
          @include('admin.membership.plan.partials.edit.accessibility', ['plan' => $plan])
          @include('admin.membership.plan.partials.edit.steps', ['plan' => $plan])
        </form>
      </div>
    </div>
  </div>
</main>
@endsection

@push('script-include')
    <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
    <script src="{{asset('assets/theme/global/js/pricing_plan/stage-step-admin.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/pricing_plan/stage-step-user.js')}}"></script>
@endpush


@push('script-push')
  <script>
    (function($){
      "use strict";
        select2_search($('.select2-search').data('placeholder'));
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $(document).ready(function() {

          $('.newEmailData').on('click', function() {
              
              var mail_gateway       = $('#mail_gateways').val();
              var existingEmailInput = $('.newEmaildata input[value="' + mail_gateway + '"]');
              if ($('.newEmaildata input[value="' + mail_gateway + '"]').length > 0) {
                  
                  existingEmailInput.addClass('shake-horizontal');
                  setTimeout(function() {
                      existingEmailInput.removeClass('shake-horizontal');
                  }, 2000);
                  return;
              }
              var html = `<div class="row newEmaildata mt-3">
                      <div class="mb-2 col-lg-5">
                          <input type="text"  name="mail_gateways[]" class="form-control" value="${mail_gateway}" required placeholder="${mail_gateway.toUpperCase()}" readonly="true">
                      </div>
                      <div class="mb-2 col-lg-5">
                          <input name="total_mail_gateway[]" class="form-control" type="number" required placeholder=" {{ translate('Total Gateways')}}">
                      </div>
                      <div class="col-lg-2 text-end">
                          <span class="input-group-btn">
                              <button class="i-btn btn--danger btn--sm removeEmailBtn" type="button">
                                  <i class="ri-delete-bin-2-line"></i>
                              </button>
                          </span>
                      </div>
                  </div>`;
              $('.newEmailDataAdd').append(html);
          });
          $(document).on('click', '.removeEmailBtn', function () {

              $(this).closest('.newEmaildata').remove();
          });
          $('.newSmsdata').on('click', function() {

              var sms_gateway      = $('#sms_gateways').val().replace($('#sms_gateways').val().match(/(\d+)/g)[0], '').trim();  
              var existingSMSInput = $('.newSmsdata input[value="' + sms_gateway + '"]');
              if ($('.newSmsdata input[value="' + sms_gateway + '"]').length > 0) {

                  existingSMSInput.addClass('shake-horizontal');
                  setTimeout(function() {

                      existingSMSInput.removeClass('shake-horizontal');
                  }, 2000);
                  return;
              }
              var html = `<div class="row newSmsdata mt-3">
                      <div class="mb-2 col-lg-5">
                          <input readonly="true" name="sms_gateways[]" class="form-control" value="${sms_gateway}" type="text" required placeholder="${sms_gateway.toUpperCase()}">
                      </div>
                      <div class="mb-2 col-lg-5">
                          <input name="total_sms_gateway[]" class="form-control" type="number" required placeholder=" {{ translate('Total Gateways')}}" min="0">
                      </div>
                      <div class="col-lg-2 text-end">
                          <span class="input-group-btn">
                              <button class="i-btn btn--danger btn--sm removeSmsBtn" type="button">
                              <i class="ri-delete-bin-2-line"></i>
                          </button>
                          </span>
                      </div>
                  </div>`;
              $('.newSmsDataAdd').append(html);
          });
          $(document).on('click', '.removeSmsBtn', function () {

              $(this).closest('.newSmsdata').remove();
          });
          function showEmailGatewayOption(value) {

              value.is(":checked") ? $(".email_gateway_options").removeClass("d-none").addClass("d-block") : $(".email_gateway_options").removeClass("d-block").addClass("d-none");
              value.is(":checked") ? $(".info-email").removeClass("d-block").addClass("d-none") : $(".info-email").removeClass("d-none").addClass("d-block");
          }
          function showSmsGatewayOption(value) {

              value.is(":checked") ? $(".sms_gateway_options").removeClass("d-none").addClass("d-block") : $(".sms_gateway_options").removeClass("d-block").addClass("d-none");
              value.is(":checked") ? $(".info-sms").removeClass("d-block").addClass("d-none") : $(".info-sms").removeClass("d-none").addClass("d-block");
          }
          $(".multiple_gateway").change(function() {

              showEmailGatewayOption($(this));
          });
          $(".sms_gateway").change(function() {

              showSmsGatewayOption($(this));
          });
          function toggleGatewayOptionVisibility(toggled) {

              const adminItems = $(".admin-items");
              const userItems  = $(".user-items");
              const adminCheckboxes = [
                "#allow_admin_sms",
                "#allow_admin_email",
                "#allow_admin_android",
                "#allow_admin_whatsapp"
              ];
              const userCheckboxes = [
                "#allow_user_android",
                "#allow_user_whatsapp",
                "#multi_gateway",
                "#sms_gateway"
              ];
              if ($("#allow_admin_creds").is(":checked")) {

                  if (toggled) {

                      var android_permission  = {{$plan->sms->android->is_allowed == " " ? 1 : 0}};
                      var sms_permission 	    = {{$plan->sms->is_allowed == " " ? 1 : 0}};
                      var email_permission    = {{$plan->email->is_allowed == " " ? 1 : 0}};
                      var whatsapp_permission = {{$plan->whatsapp->is_allowed == " " ? 1 : 0}};
                      if(android_permission == 0 && sms_permission == 0) {

                          $(".admin-sms-credit").addClass("d-none");
                          $(".admin-sms-per-day-credit").addClass("d-none");
                      }
                      adminCheckboxes.forEach((checkbox) => {

                          if(checkbox == "#allow_admin_android") {

                              if(android_permission == 1) {

                                $(checkbox).prop("checked", true);
                              }
                          }
                          if(checkbox == "#allow_admin_sms") {

                              if(sms_permission == 1) {

                                $(checkbox).prop("checked", true);
                              }
                          }
                          if(checkbox == "#allow_admin_email") {
                              
                              if(email_permission == 1) {

                                $(checkbox).prop("checked", true);
                              } else {

                                $(".admin-email-credit").addClass("d-none");
                                $(".admin-email-per-day-credit").addClass("d-none");
                              }
                          }
                      });
                  }
                  adminItems.removeClass("d-none").addClass("d-block");
                  userItems.removeClass("d-block").addClass("d-none");
              } else {

                  if (toggled) {

                      adminCheckboxes.forEach((checkbox) => $(checkbox).prop("checked", false));
                  }
                  adminItems.removeClass("d-block").addClass("d-none");
                  userItems.removeClass("d-none").addClass("d-block");
              }
          }
          var uwLimit = $("#user_whatsapp_device_limit").closest('.d-none');
          var uaLimit = $("#user_android_gateway_limit").closest('.d-none');
          var awLimit = $("#whatsapp_device_limit").closest('.d-none');
          toggleLimitVisibility($("#allow_user_android"), uaLimit, $(".user_android"))
          toggleLimitVisibility($("#allow_user_whatsapp"), uwLimit, $(".user_whatsapp"));
          toggleLimitVisibility($("#allow_admin_whatsapp"), awLimit, $(".admin_whatsapp"));
          if($('#allow_admin_creds').val() == "true") {

              toggleGatewayOptionVisibility(true);
          }
          toggleGatewayOptionVisibility(true);
          $("#allow_admin_creds").change(function() {

              toggleGatewayOptionVisibility(true);
              toggleLimitVisibility($("#allow_admin_whatsapp"), awLimit, $(".admin_whatsapp"));
          });
          $("#allow_admin_whatsapp").change(function() {

              toggleLimitVisibility($("#allow_admin_whatsapp"), awLimit, $(".admin_whatsapp"));
          });
          $("#allow_user_android").change(function() {

              toggleLimitVisibility($("#allow_user_android"), uaLimit, $(".user_android"));
          });
          $("#allow_user_whatsapp").change(function() {

              toggleLimitVisibility($("#allow_user_whatsapp"), uwLimit, $(".user_whatsapp"));
          });
          function toggleLimitVisibility(accessToggle, closestLimitBox,boxSize) {

              if (accessToggle.is(":checked")) {

                if (closestLimitBox.length > 0) {

                  closestLimitBox.removeClass("d-none");
                }
              } else {

                closestLimitBox.addClass("d-none");
              }
          }
          $(".allow_admin_sms").change(function() {

            if($(".allow_admin_sms").is(":checked")) {
                
              $(".admin-sms-credit").removeClass("d-none");
              $(".admin-sms-per-day-credit").removeClass("d-none");
            } else {

              $(".admin-sms-credit").addClass("d-none");
              $(".admin-sms-per-day-credit").addClass("d-none");
            }
          });
          $(".allow_admin_email").change(function() {

            if($(".allow_admin_email").is(":checked")) {
                
                $(".admin-email-credit").removeClass("d-none");
                $(".admin-email-per-day-credit").removeClass("d-none");
            } else {

                $(".admin-email-credit").addClass("d-none");
                $(".admin-email-per-day-credit").addClass("d-none");
            }
          });
          $(".allow_user_sms").change(function() {

            if($(".allow_user_sms").is(":checked")) {
                
                $(".user-sms-credit").removeClass("d-none");
                $(".user-sms-per-day-credit").removeClass("d-none");
            } else {

                $(".user-sms-credit").addClass("d-none");
                $(".user-sms-per-day-credit").addClass("d-none");
            }
          }); 
          $(".allow_user_email").change(function() {

            if($(".allow_user_email").is(":checked")) {
                
              $(".user-email-credit").removeClass("d-none");
              $(".user-email-per-day-credit").removeClass("d-none");
            } else {
              $(".user-email-credit").addClass("d-none");
              $(".user-email-per-day-credit").addClass("d-none");
            }
          });
        });
    })(jQuery);
  </script>
@endpush

