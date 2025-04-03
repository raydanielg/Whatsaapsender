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
                    <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> {{ translate("Create Plan") }} </li>
                </ol>
            </nav>
            </div>
        </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form action="{{route('admin.membership.plan.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="form-element">
            <div class="row gy-4">
              <div class="col-xxl-2 col-xl-3">
                <h5 class="form-element-title">{{ translate("Basic Informations") }}</h5>
              </div>
              <div class="col-xxl-8 col-xl-9">
                <div class="row gy-4 gx-xl-5">
                  <div class="col-md-6">
                    <div class="form-inner">
                      <label for="name" class="form-label">{{ translate("Plan Name") }}<span class="text-danger">*</span></label>
                      <input type="text" id="name" class="form-control" placeholder="{{ translate("Enter membership plan name") }}" name="name" aria-label="name" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-inner">
                      <label for="duration" class="form-label">{{ translate("Plan Duration") }}</label>
                      <div class="input-group">
                        <input type="number" min="0" name="duration" id="duration" class="form-control" placeholder="{{ translate("Enter membership plan duration") }}" />
                        <span id="reset-primary-color" class="input-group-text" role="button"> {{ translate("Days") }} </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-inner">
                      <label for="description" class="form-label">{{ translate("Plan Description") }}</label>
                      <textarea type="text" name="description" id="description" class="form-control" placeholder="{{ translate("Write description for the membership plan") }}" aria-label="description"></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-inner">
                      <label for="amount " class="form-label"> {{ translate("Amount") }} </label>
                      <div class="input-group">
                        <input type="number" min="0" step="0.01" id="amount" class="form-control" placeholder="{{ translate("Enter membership plan price") }}" aria-label="amount" name="amount"/>
                        <span id="reset-primary-color" class="input-group-text" role="button"> {{ getDefaultCurrencyCode(json_decode(site_settings('currencies'), true)) }} </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-inner">
                      <label class="form-label"> {{ translate("Carry forward") }} </label>
                      <div class="form-inner-switch">
                        <label class="pointer" for="allow_carry_forward" >{{ translate("Turn on/off pricing plan carry forward") }}</label>
                        <div class="switch-wrapper mb-1">
                          <input type="checkbox" class="switch-input" id="allow_carry_forward" name="allow_carry_forward" value="true"/>
                          <label for="allow_carry_forward" class="toggle">
                            <span></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-element">
            <div class="row gy-4">
              <div class="col-xxl-2 col-xl-3">
                <h5 class="form-element-title">{{ translate("Accessibility") }}</h5>
              </div>
              <div class="col-xxl-8 col-xl-9">
                <div class="row gy-4 gx-xl-5">
                    <div class="col-12">
                        <div class="form-inner">
                          <div class="form-inner-switch">
                            <label class="pointer" for="allow_admin_creds" >{{ translate("Allow users to use Admin Gateways or Devices") }}</label>
                            <div class="switch-wrapper mb-1">
                              <input type="checkbox" class="switch-input" id="allow_admin_creds" name="allow_admin_creds" value="true"/>
                              <label for="allow_admin_creds" class="toggle">
                                <span></span>
                              </label>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-element">
            <div class="admin-items d-none">
                <div class="step-wrapper-admin justify-content-center">
                    <ul class="progress-steps-admin">
                      <li class="step-item-admin activated active">
                        <span>{{ translate("01") }}</span> {{ translate("SMS") }}
                      </li>
                      <li class="step-item-admin">
                        <span>{{ translate("02") }}</span> {{ translate("WhatsApp") }}
                      </li>
                      <li class="step-item-admin">
                        <span>{{ translate("03") }}</span> {{ translate("Email") }}
                      </li>
                    </ul>
                </div>
                <div class="step-content-admin">
                    <div class="step-content-item-admin active">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title accessibility">{{ translate("SMS(Admin)") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                              <div class="row gy-4 gx-xl-5">
                                  
                                  <div class="col-md-6">
                                  <div class="form-inner-switch">
                                      <div>
                                      <label for="allow_admin_sms">
                                          <p class="fs-16 mb-3">{{ translate("Admin's SMS Gateways") }}</p>
                                          <span>{{ translate("Enable users to use Admin's SMS Gateways") }}</span>
                                      </label>
                                      </div>
                                      <div class="switch-wrapper mb-1">
                                      <input class="switch-input allow_admin_sms" type="checkbox" value="true" name="allow_admin_sms" id="allow_admin_sms">
                                      <label for="allow_admin_sms" class="toggle">
                                          <span></span>
                                      </label>
                                      </div>
                                  </div>
                                  </div>
                                  <div class="col-md-6">
                                  <div class="form-inner-switch">
                                      <div>
                                      <label for="allow_admin_android">
                                          <p class="fs-16 mb-3">{{ translate("Admin's Android Gateways") }}</p>
                                          <span>{{ translate("Enable users to use Admin's Android Gateways") }}</span>
                                      </label>
                                      
                                      </div>
                                      <div class="switch-wrapper mb-1">
                                      <input class="switch-input allow_admin_sms" type="checkbox" value="true" name="allow_admin_android" id="allow_admin_android">
                                      <label for="allow_admin_android" class="toggle">
                                          <span></span>
                                      </label>
                                      </div>
                                  </div>
                                  </div>
                                  <div class="col-md-6 admin-sms-credit">
                                    <div class="form-inner">
                                        <label for="sms_credit_admin" class="form-label"> {{ translate("SMS credit limit") }} </label>
                                        <div class="input-group">
                                          <input type="number" min="-1" value="-1" class="form-control" id="sms_credit_admin" name="sms_credit_admin" placeholder="{{ translate('Enter SMS Credit')}}" aria-label="Total SMS Credit" aria-describedby="basic-addon2" >
                                          <span class="input-group-text fs-14" id="sms_credit_admin"> {{ translate("Credit limit") }} </span>
                                          
                                        </div>
                                        <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                    </div>
                                  </div>
                                  <div class="col-md-6 admin-sms-per-day-credit">
                                    <div class="form-inner">
                                        <label for="sms_credit_per_day_admin" class="form-label"> {{ translate("SMS per day credit limit") }} </label>
                                        <div class="input-group">
                                          <input type="number" min="0" value="0" class="form-control" id="sms_credit_per_day_admin" name="sms_credit_per_day_admin" placeholder="{{ translate('Enter SMS Credit')}}" aria-label="SMS Credits per day" aria-describedby="basic-addon2" >
                                          <span class="input-group-text fs-14" id="sms_credit_per_day_admin"> {{ translate("Per Day") }} </span>
                                          
                                        </div>
                                        <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                                <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-admin"> {{ translate("Previous") }} </button>
                                <button type="button" class="i-btn btn--primary btn--md step-next-btn-admin"> {{ translate("Next") }} </button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-content-item-admin">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Whatsapp (Admin)") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4 gx-xl-5">
                                <div class="col-md-12">
                                <div class="form-inner-switch">
                                    <div>
                                    <label for="allow_admin_whatsapp">
                                        <p class="fs-16 mb-3"> {{ translate("Allow users to add Whatsapp Devices") }} 
                                          <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{translate("WhatsApp Cloud API does not have any effect from this option")}}"><i class="ri-information-line"></i></span>
                                        </p>
                                        <span>{{ translate("Enable unlimited Devices if you set 'Whatsapp Device Limit' value to '-1'") }}</span>
                                    </label>
                                    
                                    </div>
                                    <div class="switch-wrapper mb-1">
                                    <input type="checkbox" class="switch-input allow_admin_whatsapp" value="true" name="allow_admin_whatsapp" id="allow_admin_whatsapp" />
                                    <label for="allow_admin_whatsapp" class="toggle">
                                        <span></span>
                                    </label>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-12 d-none">
                                  <div class="form-inner">
                                      <label for="creditLimit" class="form-label"> {{ translate('Whatsapp Device Limit')}}  </label>
                                      <div class="input-group">
                                      <input value="0" type="number" min="0" class="form-control" id="whatsapp_device_limit" name="whatsapp_device_limit" placeholder="{{ translate('Users can Add upto')}}" aria-label="Whatsapp Device Limit" aria-describedby="basic-addon2">
                                      <span class="input-group-text fs-14" id="whatsapp_device_limit"> {{ translate("Device Limit") }} </span>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-md-6 admin-whatsapp-credit">
                                  <div class="form-inner">
                                      <label for="whatsapp_credit_admin" class="form-label"> {{ translate('Whatsapp Credit Limit')}} </label>
                                      <div class="input-group">
                                        <input type="number" min="-1" value="-1" class="form-control" id="whatsapp_credit_admin" name="whatsapp_credit_admin" placeholder="{{ translate('Enter Whatsapp Credit')}}" aria-label="Recipient's username" aria-describedby="basic-addon2" >
                                        <span class="input-group-text fs-14" id="whatsapp_credit_admin"> {{ translate("Credit Limit") }} </span>
                                        
                                      </div>
                                      <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                  </div>
                                </div>
                               
                                <div class="col-md-6 admin-whatsapp-per-day-credit">
                                  <div class="form-inner">
                                      <label for="whatsapp_credit_per_day_admin" class="form-label"> {{ translate("Whatsapp per day credit limit") }} </label>
                                      <div class="input-group">
                                      <input type="number" min="0" value="0" class="form-control" id="whatsapp_credit_per_day_admin" name="whatsapp_credit_per_day_admin" placeholder="{{ translate('Enter WhatsApp Credit')}}" aria-label="WhatsApp Credits per day" aria-describedby="basic-addon2" >
                                      <span class="input-group-text fs-14" id="whatsapp_credit_per_day_admin"> {{ translate("Per Day") }} </span>
                                      
                                      </div>
                                      <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                  </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                                <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-admin"> {{ translate("Previous") }} </button>
                                <button type="button" class="i-btn btn--primary btn--md step-next-btn-admin"> {{ translate("Next") }} </button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-content-item-admin">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Email (Admin)") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4 gx-xl-5">
                            
                                <div class="col-md-12">
                                <div class="form-inner-switch">
                                    <div>
                                    <label for="allow_admin_email">
                                        <p class="fs-16 mb-3"> {{ translate("Admin's Email Gateways") }} </p>
                                        <span>{{ translate("Enable users to use Admin's Email Gateways") }}</span>
                                    </label>
                                    
                                    </div>
                                    <div class="switch-wrapper mb-1">
                                    <input class="switch-input allow_admin_email" type="checkbox" value="true" name="allow_admin_email" type="checkbox" id="allow_admin_email">
                                    <label for="switch-3" class="toggle">
                                        <span></span>
                                    </label>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="col-md-6 admin-email-credit">
                                  <div class="form-inner">
                                      <label for="email_credit_admin" class="form-label">{{ translate('Email Credit Limit')}}</label>
                                      <div class="input-group">
                                      <input type="number" min="-1" value="-1" class="form-control" id="email_credit_admin" name="email_credit_admin" placeholder="{{ translate('Enter Email Credit')}}" aria-label="Recipient's username" aria-describedby="basic-addon2" >
                                      <span class="input-group-text fs-14" id="email_credit_admin"> {{ translate("Credit limit") }} </span>
                                      
                                      </div>
                                      <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                  </div>
                                </div>
                                <div class="col-md-6 admin-email-per-day-credit">
                                  <div class="form-inner">
                                      <label for="email_credit_per_day_admin" class="form-label"> {{ translate("Email per day credit limit") }} </label>
                                      <div class="input-group">
                                      <input type="number" min="0" value="0" class="form-control" id="email_credit_per_day_admin" name="email_credit_per_day_admin" placeholder="{{ translate('Enter Email Credit')}}" aria-label="Email Credits per day" aria-describedby="basic-addon2" >
                                      <span class="input-group-text fs-14" id="email_credit_per_day_admin"> {{ translate("Per Day") }} </span>
                                      
                                      </div>
                                      <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                  </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                                <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-admin"> {{ translate("Previous") }} </button>
                                <button type="button" class="i-btn btn--primary btn--md step-next-btn-admin"> {{ translate("Next") }} </button>
                                <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-items">
                <div class="step-wrapper-user justify-content-center">
                    <ul class="progress-steps-user">
                      <li class="step-item-user activated active">
                        <span>{{ translate("01") }}</span> {{ translate("SMS") }}
                      </li>
                      <li class="step-item-user">
                        <span>{{ translate("02") }}</span> {{ translate("WhatsApp") }}
                      </li>
                      <li class="step-item-user">
                        <span>{{ translate("03") }}</span> {{ translate("Email") }}
                      </li>
                    </ul>
                </div>
                <div class="step-content-user">
                    <div class="step-content-item-user active">
                        <div class="row gy-4">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title accessibility">{{ translate("SMS(User)") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4 gx-xl-5">
                              
                              <div class="col-md-6 user_android">
                                <div class="form-inner-switch">
                                  <div>
                                    <label for="allow_user_android">
                                        <p class="fs-16 mb-3">{{ translate("Allow users to add Android Gateways") }}</p>
                                        <span>{{ translate("Enable unlimited Android Gateways if you set the value to '-1'") }}</span>
                                    </label>
                                    
                                  </div>
                                  <div class="switch-wrapper mb-1">
                                    <input class="switch-input allow_user_sms" type="checkbox" value="true" name="allow_user_android" id="allow_user_android">
                                    <label for="allow_user_android" class="toggle">
                                      <span></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-inner-switch">
                                  <div>
                                    <label for="sms_gateway">
                                        <p class="fs-16 mb-3">{{ translate("Allow Users To Make Multiple SMS Gateways") }}</p>
                                        <span>{{ translate("Choose The Amount Of Gateways Users Can Create From Each SMS Gateway Type") }}</span>
                                    </label>
                                    
                                  </div>
                                  <div class="switch-wrapper mb-1">
                                    <input class="switch-input allow_user_sms sms_gateway" type="checkbox" value="true" name="sms_multi_gateway" id="sms_gateway">
                                    <label for="sms_gateway" class="toggle">
                                      <span></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 user-sms-credit">
                                <div class="form-inner">
                                  <label for="sms_credit_user" class="form-label"> {{ translate("SMS credit limit") }} </label>
                                  <div class="input-group">
                                    <input type="number" min="-1" value="-1" class="form-control" id="sms_credit_user" name="sms_credit_user" placeholder="{{ translate('Enter SMS Credit')}}" >
                                    <span class="input-group-text fs-14" id="sms_credit_user"> {{ translate("Credit limit") }} </span>
                                    
                                  </div>
                                  <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                </div>
                              </div>
                              <div class="col-md-6 user-sms-per-day-credit">
                                <div class="form-inner">
                                    <label for="sms_credit_per_day_user" class="form-label"> {{ translate("SMS per day credit limit") }} </label>
                                    <div class="input-group">
                                      <input type="number" min="0" value="0" class="form-control" id="sms_credit_per_day_user" name="sms_credit_per_day_user" placeholder="{{ translate('Enter SMS Credit')}}" aria-label="SMS Credits per day" aria-describedby="basic-addon2" >
                                      <span class="input-group-text fs-14" id="sms_credit_per_day_user"> {{ translate("Per Day") }} </span>
                                      
                                    </div>
                                    <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                </div>
                              </div>
                              <div class="col-md-12 d-none">
                                <div class="form-inner">
                                  <label for="user_android_gateway_limit" class="form-label"> {{ translate("Android Gateway Limit") }} </label>
                                  <div class="input-group">
                                    <input value="0" type="number" min="0" class="form-control" id="user_android_gateway_limit" name="user_android_gateway_limit" placeholder="{{ translate('Users can Add upto')}}" aria-label="Android Gateway Limit" aria-describedby="basic-addon2">
                                    <span class="input-group-text fs-14" id="user_android_gateway_limit"> {{ translate("Gateway limit") }} </span>
                                  </div>
                                </div>
                              </div>
                              
                              
                              <div class="col-md-9 sms_gateway_options d-none">
                                <div class="form-inner">
                                  <label for="sms_gateways" class="form-label">{{ translate("Select SMS Gateways") }}</label>
                                  <select class="form-select select2-search" data-show="5" data-placeholder="{{ translate("Choose a gateway") }}" name="sms_gateway_select" id="sms_gateways">
                                    <option value=""></option>
                                    @foreach($sms_credentials as $sms_credential)
                                        <option value="{{($sms_credential)}}">{{preg_replace('/[[:digit:]]/','', setInputLabel($sms_credential))}}</option>
                                    @endforeach
                                </select>
                                </div>
                              </div>
                              <div class="col-md-3 newSmsdata sms_gateway_options d-none">
                                <button class="i-btn btn--primary btn--md w-100 mt-md-4" type="button">
                                  <i class="ri-add-fill fs-18 "></i> {{ translate("Add new") }} </button>
                              </div>
                              <div class="newSmsDataAdd sms_gateway_options d-none"></div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                              <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-user"> {{ translate("Previous") }} </button>
                              <button type="button" class="i-btn btn--primary btn--md step-next-btn-user"> {{ translate("Next") }} </button>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="step-content-item-user">
                        <div class="row gy-4">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Whatsapp (USER)") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4 gx-xl-5">
                              <div class="col-md-12 user_whatsapp">
                                <div class="form-inner-switch">
                                  <div>
                                    <label for="allow_user_whatsapp">
                                        <p class="fs-16 mb-3"> {{ translate("Allow users to add Whatsapp Devices") }} 
                                          <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{translate("WhatsApp Cloud API does not have any effect from this option")}}"><i class="ri-information-line"></i></span>
                                        </p>
                                        <span>{{ translate("Enable unlimited Devices if you set the value to '-1'") }}</span>
                                    </label>
                                  </div>
                                  <div class="switch-wrapper mb-1">
                                    <input class="switch-input allow_user_whatsapp" type="checkbox" value="true" name="allow_user_whatsapp" type="checkbox" id="allow_user_whatsapp">
                                    <label for="allow_user_whatsapp" class="toggle">
                                      <span></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-12 d-none">
                                <div class="form-inner">
                                  <label for="user_whatsapp_device_limit" class="form-label"> {{ translate('Whatsapp Device Limit')}}  </label>
                                  <div class="input-group">
                                    <input value="0" type="number" min="0" class="form-control" id="user_whatsapp_device_limit" name="user_whatsapp_device_limit" placeholder="{{ translate('Users can Add upto')}}" aria-label="Whatsapp Device Limit" aria-describedby="basic-addon2">
                                    <span class="input-group-text fs-14" id="user_whatsapp_device_limit"> {{ translate("Device limit") }} </span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 user-whatsapp-credit">
                                <div class="form-inner">
                                  <label for="whatsapp_credit_user" class="form-label">{{translate('Whatsapp Credit Limit')}}</label>
                                  <div class="input-group">
                                    <input type="number" min="-1" value="-1" class="form-control" id="whatsapp_credit_user" name="whatsapp_credit_user" placeholder="{{ translate('Enter Whatsapp Credit')}}"  aria-describedby="basic-addon2" >
                                    <span class="input-group-text fs-14" id="whatsapp_credit_user"> {{ translate("Credit limit") }} </span>
                                    
                                  </div>
                                  <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                </div>
                              </div>
                              <div class="col-md-6 user-whatsapp-per-day-credit">
                                <div class="form-inner">
                                    <label for="whatsapp_credit_per_day_user" class="form-label"> {{ translate("WhatsApp per day credit limit") }} </label>
                                    <div class="input-group">
                                    <input type="number" min="0" value="0" class="form-control" id="whatsapp_credit_per_day_user" name="whatsapp_credit_per_day_user" placeholder="{{ translate('Enter WhatsApp Credit')}}" aria-label="WhatsApp Credits per day" aria-describedby="basic-addon2" >
                                    <span class="input-group-text fs-14" id="whatsapp_credit_per_day_user"> {{ translate("Per Day") }} </span>
                                    
                                    </div>
                                    <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                              <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-user"> {{ translate("Previous") }} </button>
                              <button type="button" class="i-btn btn--primary btn--md step-next-btn-user"> {{ Translate("Next") }} </button>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="step-content-item-user">
                        <div class="row gy-4">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Email (USER)") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4 gx-xl-5">
                            
                              <div class="col-md-12">
                                <div class="form-inner-switch">
                                  <div>
                                    <label for="multi_gateway">
                                        <p class="fs-16 mb-3"> {{ translate("Allow Users To Make Multiple Email Gateways") }} </p>
                                        <span>{{ translate("Choose The Amount Of Gateways Users Can Create From Each Email Gateway Type") }}</span>
                                    </label>
                                  </div>
                                  <div class="switch-wrapper mb-1">
                                    <input type="checkbox" value="true" name="mail_multi_gateway" id="multi_gateway" class="switch-input multiple_gateway allow_user_email">
                                    <label for="multi_gateway" class="toggle">
                                      <span></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                             
                              <div class="col-md-6 user-email-credit">
                                <div class="form-inner">
                                  <label for="email_credit_user" class="form-label"> {{ translate('Email Credit Limit')}} </label>
                                  <div class="input-group">
                                    <input type="number" min="-1" value="-1" class="form-control" id="email_credit_user" name="email_credit_user" placeholder="{{ translate('Enter Email Credit')}}" aria-label="Recipient's username" aria-describedby="basic-addon2" >
                                    <span class="input-group-text fs-14" id="email_credit_user"> {{ translate("Credit limit") }} </span>
                                    
                                  </div>
                                  <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
                                </div>
                              </div>
                              <div class="col-md-6 user-email-per-day-credit">
                                <div class="form-inner">
                                    <label for="email_credit_per_day_user" class="form-label"> {{ translate("Email per day credit limit") }} </label>
                                    <div class="input-group">
                                      <input type="number" min="0" value="0" class="form-control" id="email_credit_per_day_user" name="email_credit_per_day_user" placeholder="{{ translate('Enter Email Credit')}}" aria-label="Email Credits per day" aria-describedby="basic-addon2" >
                                      <span class="input-group-text fs-14" id="email_credit_per_day_user"> {{ translate("Per Day") }} </span>
                                      
                                    </div>
                                    <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
                                </div>
                              </div>
                              <div class="col-md-9 email_gateway_options d-none">
                                <div class="form-inner">
                                  <label for="mail_gateways" class="form-label">{{ translate('Select Email Gateway')}}</label>
                                  <select class="form-select select2-search" data-show="5" data-placeholder="{{ translate("Choose a gateway") }}" name="mail_gateway_select" id="mail_gateways">
                                    <option value=""></option>
                                    @foreach($mail_credentials as $mail_credential)
                                        <option value="{{strToLower($mail_credential)}}">{{strtoupper($mail_credential)}}</option>
                                    @endforeach
                                </select>
                                </div>
                              </div>
                              <div class="col-md-3 newEmailData email_gateway_options d-none">
                                <button class="i-btn btn--primary btn--md w-100 mt-md-4" type="button">
                                  <i class="ri-add-fill fs-18 "></i> {{ translate("Add new") }} </button>
                              </div>
                              <div class="newEailDataAdd email_gateway_options d-none"></div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                              <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-user"> {{ translate("Previous") }} </button>
                              <button type="button" class="i-btn btn--primary btn--md step-next-btn-user"> {{ translate("Next") }} </button>
                              <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
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

                var mail_gateway = $('#mail_gateways').val();
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
                                    <input type="text"  name="mail_gateways[]" class="form-control text-uppercase " value="${mail_gateway}"  placeholder="${mail_gateway.toUpperCase()}" readonly="true">
                                </div>
                                <div class="mb-2 col-lg-5">
                                    <input name="total_mail_gateway[]" class="form-control" type="number" min="0"  placeholder=" {{ translate('Total Gateways')}}">
                                </div>
                                <div class="col-lg-2 text-end">
                                    <span class="input-group-btn">
                                        <button class="i-btn btn--danger btn--sm removeEmailBtn" type="button">
                                            <i class="ri-delete-bin-2-line"></i>
                                        </button>
                                    </span>
                                </div>
                                
                            </div>`;

                $('.newEailDataAdd').append(html);
            });

            $(document).on('click', '.removeEmailBtn', function () {

                $(this).closest('.newEmaildata').remove();
            });

            $('.newSmsdata').on('click', function() {

                var sms_gateway = $('#sms_gateways').val().replace($('#sms_gateways').val().match(/(\d+)/g)[0], '').trim();  
                var existingSMSInput = $('.newSmsdata input[value="' + textFormat(['_'], sms_gateway, ' ') + '"]');
                if ($('.newSmsdata input[value="' + textFormat(['_'], sms_gateway, ' ') + '"]').length > 0) {
                    existingSMSInput.addClass('shake-horizontal');
                    
                    setTimeout(function() {
                        existingSMSInput.removeClass('shake-horizontal');
                        
                    }, 2000);
                    return;
                }
                var html = ` <div class="row newSmsdata mt-3">
                    <div class="mb-2 col-lg-5">
                        <input readonly="true" name="sms_gateways[]" class="form-control" value="${textFormat(['_'], sms_gateway, ' ')}" type="text"  placeholder="${sms_gateway.toUpperCase()}">
                    </div>
                    <div class="mb-2 col-lg-5">
                        <input name="total_sms_gateway[]" class="form-control" type="number"  placeholder=" {{ translate('Total Gateways')}}">
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
                const userItems = $(".user-items");

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
                        adminCheckboxes.forEach((checkbox) => {
                            
                            $(checkbox).prop("checked", true);
                        });
                    }
                    adminItems.removeClass("d-none").addClass("d-block");
                    userItems.removeClass("d-block").addClass("d-none");
                } else {
                    if (toggled) {
                        userCheckboxes.forEach((checkbox) => {
                            $(checkbox).prop("checked", true);
                            if (checkbox === "#multi_gateway") {
                                showEmailGatewayOption($(checkbox));
                            } else if (checkbox === "#sms_gateway") {
                                showSmsGatewayOption($(checkbox));
                            }
                        });
                        adminCheckboxes.forEach((checkbox) => $(checkbox).prop("checked", false));
                    }
                    adminItems.removeClass("d-block").addClass("d-none");
                    userItems.removeClass("d-none").addClass("d-block");
                }
            }

            var uwLimit = $("#user_whatsapp_device_limit").closest('.d-none');
            var uaLimit = $("#user_android_gateway_limit").closest('.d-none');
            var awLimit = $("#whatsapp_device_limit").closest('.d-none');

            toggleGatewayOptionVisibility(true);


            toggleLimitVisibility($("#allow_user_android"), uaLimit, $(".user_android"))
            toggleLimitVisibility($("#allow_user_whatsapp"), uwLimit, $(".user_whatsapp"));
            toggleLimitVisibility($("#allow_admin_whatsapp"), awLimit, $(".admin_whatsapp"));

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
            // $(".allow_admin_whatsapp").change(function() {

            //   if($(".allow_admin_whatsapp").is(":checked")) {
                  
            //       $(".admin-whatsapp-credit").removeClass("d-none");
            //       $(".admin-whatsapp-per-day-credit").removeClass("d-none");
            //   } else {
            //       $(".admin-whatsapp-credit").addClass("d-none");
            //       $(".admin-whatsapp-per-day-credit").addClass("d-none");
            //   }
            // });
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
            //     $(".allow_user_whatsapp").change(function() {

            //     if($(".allow_user_whatsapp").is(":checked")) {
                    
            //         $(".user-whatsapp-credit").removeClass("d-none");
            //         $(".user-whatsapp-per-day-credit").removeClass("d-none");
            //     } else {
            //         $(".user-whatsapp-credit").addClass("d-none");
            //         $(".user-whatsapp-per-day-credit").addClass("d-none");
            //     }
            // });
        })
        
	})(jQuery);
</script>
@endpush

