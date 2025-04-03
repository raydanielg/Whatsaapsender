@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('admin.layouts.app')
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
          <a class="nav-link active" data-bs-toggle="tab" href="#core" role="tab" aria-selected="true">
            <i class="ri-settings-5-line"></i>
            {{ translate("Core Settings") }}
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#notification" role="tab" aria-selected="false" tabindex="-1">
            <i class="ri-notification-2-line"></i>
            {{ translate("Notification Settings") }}
          </a>
        </li>

        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#otherSetting" role="tab" aria-selected="false" tabindex="-1">
            <i class="ri-android-line"></i>
            {{ translate("Other Setting") }}
          </a>
        </li>
      </ul>
    </div>

    <div class="tab-content">
      <div class="tab-pane active fade show" id="core" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <div class="card-header-left">
                <h4 class="card-title">{{ translate("Core Settings") }}</h4>
            </div>
            <div class="card-header-right">
                <button class="i-btn btn--info btn--sm cron-command" type="button" data-bs-toggle="modal" data-bs-target="#cronCommand">
                  <i class="ri-timer-line"></i> {{ translate("Check Cron Commands") }}
                </button>
            </div>
          </div>

          <div class="card-body pt-0">
            <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
              @csrf
              <div class="form-element">
                <div class="row gy-4">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Site details") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="site-name" class="form-label"> {{ translate("Site name") }} <small class="text-danger">*</small></label>
                          <input type="text" id="site-name" name="site_settings[site_name]" class="form-control" placeholder="{{ translate('Enter site name') }}" aria-label="{{ translate('Enter site name') }}" value="{{ site_settings("site_name") }}"/>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="copyright" class="form-label"> {{ translate("Copyright text") }} <small class="text-danger">*</small></label>
                          <input type="text" id="copyright" name="site_settings[copyright]" class="form-control" placeholder="{{ translate('Enter copyright text') }}" aria-label="{{ translate('Enter copyright text') }}" value="{{ site_settings("copyright") }}"/>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="phone" class="form-label"> {{ translate("Phone Number") }} </label>
                          <input type="text" id="phone" name="site_settings[phone]" class="form-control" placeholder="{{ translate('Enter phone number') }}" aria-label="{{ translate('Enter phone number') }}" value="{{ site_settings("phone") }}"/>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="email" class="form-label"> {{ translate("Email Address") }} <small class="text-danger">*</small></label>
                          <input type="email" id="email" name="site_settings[email]" class="form-control" placeholder="{{ translate('Enter email address') }}" aria-label="{{ translate('Enter email address') }}" value="{{ site_settings("email") }}"/>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-inner">
                          <label for="address" class="form-label"> {{ translate("Address") }} </label>
                          <textarea class="form-control" name="site_settings[address]" id="address" rows="2" placeholder="{{ translate('Enter address') }}" aria-label="{{ translate('Enter phone number') }}">{{ site_settings("address") }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-inner">
                          <label for="google_map_iframe" class="form-label"> {{ translate("Google Map iFrame Code") }} </label>
                          <textarea class="form-control" name="site_settings[google_map_iframe]" id="google_map_iframe" rows="2" placeholder="{{ translate('Enter Google map iFrame code') }}" aria-label="{{ translate('Enter phone number') }}">{{ site_settings("google_map_iframe") }}</textarea>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-inner">
                          <label for="app_link" class="form-label">{{translate("Android APK File Link")}}</label>
                          <input type="text" name="site_settings[app_link]" id="app_link" class="form-control" placeholder="{{ translate("Enter the link for the App") }}" value="{{ site_settings("app_link") }}"/>
                          <p class="form-element-note">{{ translate("Include http/https with your link. Members will use this link to download the app") }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-element">
                <div class="row gy-4">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Site Appearance") }}</h5>
                  </div>

                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-lg-6">
                        <div class="form-inner">
                          <label class="form-label">{{ translate("Primary color") }}</label>
                          <div class="input-group">
                            <span class="input-group-text p-1">
                              <input class="border-0 color-picker" type="text" name="site_settings[primary_color]" value="{{ site_settings("primary_color") }}" />
                            </span>
                            <input type="text" class="form-control color-code" id="primary_color" name="site_settings[primary_color]" value="{{ site_settings("primary_color") }}" />
                            <span id="reset-primary-color" class="input-group-text pointer">
                              <i class="ri-refresh-line"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-inner">
                          <label class="form-label">{{ translate("Primary Text Color") }}</label>
                          <div class="input-group">
                            <span class="input-group-text p-1">
                              <input class="border-0 color-picker" type="text" name="site_settings[primary_text_color]" value="{{ site_settings("primary_text_color") }}" />
                            </span>
                            <input type="text" class="form-control color-code" id="primary_text_color" name="site_settings[primary_text_color]" value="{{ site_settings("primary_text_color") }}" />
                            <span id="reset-primary-color" class="input-group-text pointer">
                              <i class="ri-refresh-line"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-inner">
                          <label class="form-label">{{ translate("Secondary Color") }}</label>
                          <div class="input-group">
                            <span class="input-group-text p-1">
                              <input class="border-0 color-picker" type="text" name="site_settings[secondary_color]" value="{{ site_settings("secondary_color") }}" />
                            </span>
                            <input type="text" class="form-control color-code" id="secondary_color" name="site_settings[secondary_color]" value="{{ site_settings("secondary_color") }}" />
                            <span id="reset-primary-color" class="input-group-text pointer">
                              <i class="ri-refresh-line"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-inner">
                          <label class="form-label">{{ translate("Trinary Color") }}</label>
                          <div class="input-group">
                            <span class="input-group-text p-1">
                              <input class="border-0 color-picker" type="text" name="site_settings[trinary_color]" value="{{ site_settings("trinary_color") }}" />
                            </span>
                            <input type="text" class="form-control color-code" id="trinary_color" name="site_settings[trinary_color]" value="{{ site_settings("trinary_color") }}" />
                            <span id="reset-primary-color" class="input-group-text pointer">
                              <i class="ri-refresh-line"></i>
                            </span>
                          </div>
                        </div>
                      </div>

                      @foreach (Arr::get(config('setting'),'logo_keys' ,[]) as $logoKey )
                          <div class="{{ count(Arr::get(config('setting'),'logo_keys' ,[])) % 2 == 0 ? 'col-md-6' : ($loop->last ? 'col-lg-12' : 'col-lg-6') }}">
                              <div class="form-inner">
                                  <label for="{{$logoKey}}" class="form-label">
                                      {{(textFormat(['_'], $logoKey, ' '))}} <small class="text-danger" >* ({{config("setting")['file_path'][$logoKey]['size']}})</small>
                                  </label>
                                  <input class="form-control"  type="file" name="site_settings[{{$logoKey}}]" id="{{$logoKey}}" class="preview" data-size = "{{config('setting')['file_path'][$logoKey]['size']}}">
                                  <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
                              </div>
                          </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-element">
                <div class="row gy-4">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Theme Settings") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="theme_dir" class="form-label">{{ translate("Website Direction") }}</label>
                          <select data-placeholder="{{translate('Select a direction')}}" class="form-select select2-search" name="site_settings[theme_dir]" id="theme_dir">
                              <option value=""></option>
                              <option {{ site_settings('theme_dir') == \App\Enums\StatusEnum::FALSE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Left-To-Right") }}</option>
                              <option {{ site_settings('theme_dir') == \App\Enums\StatusEnum::TRUE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Right-To-Left") }}</option>
                          </select>
                          <p class="form-element-note">{{ translate("Make sure that current language is ltr/rtl compatible") }} <a href="{{ route('admin.system.language.index') }}">{{ translate("Edit Languages") }}</a></p>
                        </div>

                      </div>
                      <div class="col-md-6">
                        <div class="form-inner">
                          <label for="admin_theme" class="form-label">{{ translate("Website Theme Mode") }}</label>
                          <select data-placeholder="{{translate('Select a mode')}}" class="form-select select2-search" name="site_settings[theme_mode]" id="admin_theme">
                              <option value=""></option>
                              <option {{ site_settings('theme_mode') == \App\Enums\StatusEnum::FALSE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Dark Mode") }}</option>
                              <option {{ site_settings('theme_mode') == \App\Enums\StatusEnum::TRUE->status() ? 'selected' : '' }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Light Mode") }}</option>
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
                    <h5 class="form-element-title">{{ translate("Advanced settings") }}</h5>
                  </div>

                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-md-6">
                        <div class="form-inner">
                            <label for="time-zone" class="form-label">{{ translate("Time Zone") }}</label>
                            <select data-placeholder="{{translate('Select a time-zone')}}" class="form-select select2-search" name="site_settings[time_zone]" data-show="5" id="time-zone">
                                <option value=""></option>
                                @foreach($timeLocations as $region => $timeZones)
                                    <optgroup label="{{ $region }}">
                                        @foreach($timeZones as $timeZone)
                                            <option {{ site_settings("time_zone") == $timeZone ? 'selected' : '' }} value="{{ $timeZone }}">{{ $timeZone }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            {{-- <p class="form-element-note timezone">Selected Timezone represents: GMT+6</p> --}}

                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-inner">
                            <label for="country-code" class="form-label">{{ translate("Country Code") }}</label>
                            <select data-placeholder="{{translate('Select a country for code')}}" class="form-select select2-search" data-show="5" id="country-code" name="site_settings[country_code]">
                                <option value=""></option>
                                @foreach($countries as $code => $details)
                                    <option {{ site_settings("country_code") == $details->dial_code ? 'selected' : '' }} value="{{ $details->dial_code }}">{{$details->country. " -> ". $details->dial_code}}</option>
                                @endforeach
                            </select>

                            {{-- <p class="form-element-note country-code">Selected Country's Code is: 88</p> --}}
                        </div>
                      </div>

                      <div class="col-xl-4">
                        <div class="form-inner">
                          <label for="paginate_number" class="form-label"> {{ translate("Paginate Number") }} <small class="text-danger">*</small></label>
                          <input type="number" id="paginate_number" name="site_settings[paginate_number]" class="form-control" placeholder="{{ translate('Enter paginate value') }}" aria-label="{{ translate('Paginate Number') }}" value="{{ site_settings("paginate_number") }}"/>
                        </div>
                      </div>

                      <div class="col-xl-4 col-md-6">
                        <div class="form-inner">
                          <label class="form-label"> {{ translate("Debug Mode") }} </label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="debug_mode" >{{ translate("Turn on/off debug mode") }}</label>
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input {{ env('APP_DEBUG') ? 'checked' : '' }} name="site_settings[debug_mode]" type="checkbox" class="switch-input" id="debug_mode"/>
                              <label for="debug_mode" class="toggle">
                                <span></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-xl-4 col-md-6">
                        <div class="form-inner">
                          <label class="form-label"> {{ translate("Landing Page") }} </label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="landing_page" >{{ translate("Turn on/off landing page") }}</label>
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input {{ site_settings("landing_page") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}  type="checkbox" class="switch-input" id="landing_page" name="site_settings[landing_page]"/>
                              <label for="landing_page" class="toggle">
                                <span></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      
                      <div class="col-12">
                        <div class="form-inner">
                          <label class="form-label"> {{ translate("Maintanance Mode") }} </label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="maintenance_mode" >{{ translate("Turn on/off maintenance mode") }}</label for="maintenance_mode" >
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input type="checkbox" class="switch-input" id="maintenance_mode" name="site_settings[maintenance_mode]"  {{ site_settings("maintenance_mode") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}/>
                              <label for="maintenance_mode" class="toggle">
                                <span></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-12 maintenance-message d-none">
                        <div class="form-inner">
                          <label for="maintenance_mode_message" class="form-label"> {{ translate("Maintenance Mode Message") }} </label>
                          <textarea class="form-control" name="site_settings[maintenance_mode_message]" id="maintenance_mode_message" rows="2" placeholder="{{ translate('Enter maintenance mode message') }}" aria-label="{{ translate('Enter maintenance mode message') }}">{{ site_settings("maintenance_mode_message") }}</textarea>
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

      <div class="tab-pane fade" id="notification" role="tabpanel">
        <div class="card">
          <div class="form-header">
            <h4 class="card-title">{{ translate("Notification Settings") }}</h4>
          </div>
          <div class="card-body pt-0">
            <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
              @csrf
              <div class="form-element">
                <div class="row gy-4">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Notifications") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">

                      <div class="col-md-12">
                        <div class="form-inner">
                          <label class="form-label">{{ translate("Email Notification") }}</label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="email_notifications" >{{ translate("Turn on/off email notifications") }}</label>
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input {{ site_settings("email_notifications") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}  type="checkbox" class="switch-input" id="email_notifications" name="site_settings[email_notifications]"/>
                              <label for="email_notifications" class="toggle">
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

      <div class="tab-pane fade" id="otherSetting" role="tabpanel">
        <div class="card">
          <div class="form-header">
            <h4 class="card-title">{{ translate("Other Setting") }}</h4>
          </div>
          <div class="card-body pt-0">
            <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
              @csrf
              <div class="form-element">
                <div class="row gy-4">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Storage settings") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-12">
                        <div class="form-inner">
                          <label class="form-label"> {{ translate("Store As Webp") }} </label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="store_as_webp" >{{ translate("Enable\Disable storing image data in a webp format") }}</label>
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input {{ site_settings("store_as_webp") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="store_as_webp" name="site_settings[store_as_webp]" />
                              <label for="store_as_webp" class="toggle">
                                <span></span>
                              </label>
                            </div>
                          </div>
                          <p class="form-element-note">{{ translate("Storing files in a webp format will refuce the file size within your server") }}</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-inner">
                          <div class="input-group">
                            <label for="max_file_size" class="form-label"> {{ translate("Maximum File Upload Size") }} <small class="text-danger">*</small></label>
                            <div class="input-group">
                              <input type="number" id="max_file_size" name="site_settings[max_file_size]" class="form-control" placeholder="{{ translate('Enter the maximum size for files') }}" aria-label="{{ translate('Enter the maximum size for files') }}" value="{{ site_settings("max_file_size") }}"/>
                              <span id="reset-primary-color" class="input-group-text" role="button"> {{ site_settings("storage_unit") }} </span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-inner">
                          <div class="input-group">
                            <label for="max_file_upload" class="form-label"> {{ translate("Maximum File Upload Limit") }} <small class="text-danger">*</small></label>
                            <div class="input-group">
                              <input type="number" id="max_file_upload" name="site_settings[max_file_upload]" class="form-control" placeholder="{{ translate('Enter the maximum file upload limit') }}" aria-label="{{ translate('Enter the maximum file upload limit') }}" value="{{ site_settings("max_file_upload") }}"/>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-4">
                        <div class="form-inner">
                            <label for="mime_types" class="form-label">{{ translate("File mime types") }}</label>
                            <select data-placeholder="{{translate('Select file mime types')}}" class="form-select select2-search" name="site_settings[mime_types][]" data-show="5" id="mime_types" multiple="multiple">
                                <option value=""></option>
                                @foreach(config('setting.file_types') as $file_type)
                                    <option {{in_array($file_type, json_decode(site_settings("mime_types"), true)) ? "selected" :"" }} value="{{$file_type}}">
                                        {{$file_type}}
                                    </option>
                                @endforeach
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
                    <h5 class="form-element-title">{{ translate("Word Count") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-md-4">
                        <div class="form-inner">
                          <label for="whatsapp_word_count" class="form-label"> {{ translate("WhatsApp word count") }} </label>
                            <div class="input-group">
                              <input type="number" name="site_settings[whatsapp_word_count]" id="whatsapp_word_count" class="form-control" placeholder="{{ translate("Enter the amount of words per credit for WhatsApp") }}" value="{{ site_settings("whatsapp_word_count") }}" />
                              <span id="reset-primary-color" class="input-group-text" role="button"> {{ translate("Word") }} </span>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-inner">
                          <label for="sms_word_count" class="form-label"> {{ translate("SMS word count plain text") }} </label>
                          <div class="input-group">
                            <input type="number" name="site_settings[sms_word_count]" id="sms_word_count" class="form-control" placeholder="{{ translate("Enter the amount of words per credit for SMS (plain text)") }}" value="{{ site_settings("sms_word_count") }}" />
                            <span id="reset-primary-color" class="input-group-text" role="button"> {{ translate("Word") }} </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-inner">
                          <label for="sms_word_unicode_count" class="form-label"> {{ translate("SMS word count unicode") }} </label>
                          <div class="input-group">
                            <input type="number" name="site_settings[sms_word_unicode_count]" id="sms_word_unicode_count" class="form-control" placeholder="{{ translate("Enter the amount of words per credit for SMS (unicode text)") }}" value="{{ site_settings("sms_word_unicode_count") }}" />
                            <span id="reset-primary-color" class="input-group-text" role="button"> {{ translate("Word") }} </span>
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
                    <h5 class="form-element-title">{{ translate("Bulk Contacts") }}</h5>
                  </div>
                  <div class="col-xxl-8 col-xl-9">
                    <div class="row gy-4">
                      <div class="col-12">
                        <div class="form-inner">
                          <label class="form-label"> {{ translate("Filter Duplicate Contact") }} </label>
                          <div class="form-inner-switch">
                            <label class="pointer" for="filter_duplicate_contact" >{{ translate("Enable\Disable filtering duplicate contacts") }}</label>
                            <div class="switch-wrapper mb-1 checkbox-data">
                              <input {{ site_settings("filter_duplicate_contact") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="filter_duplicate_contact" name="site_settings[filter_duplicate_contact]" />
                              <label for="filter_duplicate_contact" class="toggle">
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

@section("modal")

<div class="modal fade" id="cronCommand" tabindex="-1" aria-labelledby="cronCommand" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Cron Command") }} </h5>
          <p>{{ translate("Last cron ran at: ").site_settings("last_cron_run") }}</p>
          <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
              <i class="ri-close-large-line"></i>
          </button>
        </div>
        <div class="modal-body modal-md-custom-height">
          <div class="row g-4">
              <div class="col-md-12">
                  <div class="form-inner">
                      <label for="cron_job_one" class="form-label">{{ translate("Cron Job One") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Set time for 1 minute. And if you have supervisor for queue-work then DO NOT SET THIS CRON JOB") }}">
                          <i class="ri-question-line"></i>
                          </span>
                      </label>
                      <div class="input-group">
                        <input disabled type="text" id="callback_url" class="form-control" value="curl -s {{route('queue.work')}}"/>
                        <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                      </div>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-inner">
                      <label for="cron_job_one" class="form-label">{{ translate("Cron Job Two") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Set time for 2 minute.") }}">
                          <i class="ri-question-line"></i>
                          </span>
                      </label>
                      <div class="input-group">
                        <input disabled type="text" id="callback_url" class="form-control" value="curl -s {{route('cron.run')}}"/>
                        <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                      </div>
                  </div>
              </div>

          </div>
      </div>
        <div class="modal-footer">
            <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
        </div>
      </div>
  </div>
</div>

@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>

@endpush
@push("script-push")

  <script>
    "use strict";
    select2_search($('.select2-search').data('placeholder'));
    ck_editor("#maintenance_mode_message");
    const initColorPicker = (color) => {
      $('.color-picker').spectrum({
          color,
          change: function (color) {
              $(this).parent().siblings('.color-code').val('#'+color.toHexString().replace(/^#?/, ''));
          }
      });
    };
    const initColorCodeInput = () => {
      $('.color-code').on('input', function () {
        const color_value = $(this).val();
        $(this).parents('.input-group').find('.color-picker').spectrum({
            color: color_value,
        });
      });
    };
    const color = $(this).data('color');
    initColorPicker(color);
    initColorCodeInput();

    $(document).ready(function() {

      $('.copy-text').click(function() {

        var message = "Text copied!";
        copy_text($(this), message);
      });
      updateBackgroundClass();
      toggleMaintenanceMessage(false);

      $('.switch-input').on('change', function() {

        updateBackgroundClass();
      });

      $('#maintenance_mode').on('change', function() {

        toggleMaintenanceMessage(true);
      });
      $('.cron-command').on('click', function() {

        const modal = $('#cronCommand');
        modal.modal('show');
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

      function toggleMaintenanceMessage(animation = false) {

        if ($('#maintenance_mode').is(':checked')) {

            if (animation) {

                $('.maintenance-message').fadeIn('medium').removeClass('d-none');
            } else {

                $('.maintenance-message').show().removeClass('d-none');
            }
        } else {

            if (animation) {

                $('.maintenance-message').fadeOut('medium', function() {

                    $(this).addClass('d-none');
                });
            } else {

                $('.maintenance-message').hide().addClass('d-none');
            }
        }
      }

    });
  </script>
@endpush
