@php
$commonFixedContent = array_values(array_filter($service_menu_content, function($item) {
    return $item->section_key === 'service_menu.common.fixed_content';
}))[0] ?? null;
$smsFixedContent = array_values(array_filter($service_menu_content, function($item) {
    return $item->section_key === 'service_menu.sms.fixed_content';
}))[0] ?? null;
$whatsappFixedContent = array_values(array_filter($service_menu_content, function($item) {
    return $item->section_key === 'service_menu.whatsapp.fixed_content';
}))[0] ?? null;
$emailFixedContent = array_values(array_filter($service_menu_content, function($item) {
    return $item->section_key === 'service_menu.email.fixed_content';
}))[0] ?? null;
$smsElementContent = array_values(array_filter($service_menu_element, function($item) {
    return $item->section_key === 'service_menu.sms.element_content';
})) ?? null;
$whatsappElementContent = array_values(array_filter($service_menu_element, function($item) {
    return $item->section_key === 'service_menu.whatsapp.element_content';
})) ?? null;
$emailElementContent = array_values(array_filter($service_menu_element, function($item) {
    return $item->section_key === 'service_menu.email.element_content';
})) ?? null;
@endphp
<header class="header">
    <div class="container-fluid container-wrapper">
      <div class="header-wrapper">
        <div class="header-left">
          <a href="{{ url('/') }}" class="logo-wrapper">
            <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" alt="logo" />
          </a>
        </div>
        <div class="header-middle">
          <div class="sidebar">
            <div class="sidebar-logo">
              <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" alt="logo" />
            </div>
            <div class="sidebar-menu-wrapper">
              <nav>
                <ul>
                  <li>
                    <a href="{{ url('/') }}" class="{{ url()->current() == url('/') ? 'active' : '' }} menu-link">{{ translate("Home") }}</a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="{{ request()->routeIs('service') ? 'active' : '' }} menu-link"> {{ translate("Service") }} <span>
                        <i class="bi bi-chevron-down"></i>
                      </span>
                    </a>
                    <div class="mega-menu">
                      <div class="mega-menu-wrapper">
                        <div class="mega-menu-inner">
                          <div class="row g-lg-0 gy-4">
                            <div class="col-lg-6">
                              <div class="maga-menu-left">
                                <h5>{{getTranslatedArrayValue(@$commonFixedContent->section_value, 'heading') }}</h5>
                                <div class="nav menu-feature" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                  <a href="" class="menu-feature-item active" id="sms-tab" data-bs-toggle="pill" data-bs-target="#sms" role="tab" aria-controls="sms" aria-selected="true">
                                    <span class="menu-feature-icon">
                                      @php echo getArrayValue(@$smsFixedContent->section_value, 'icon') @endphp
                                    </span>
                                    <div class="menu-feature-content">
                                      <h6>{{getTranslatedArrayValue(@$smsFixedContent->section_value, 'heading') }}</h6>
                                      <p> {{getTranslatedArrayValue(@$smsFixedContent->section_value, 'sub_heading') }} </p>
                                    </div>
                                  </a>
                                  <a href="" class="menu-feature-item" id="whatsapp-tab" data-bs-toggle="pill" data-bs-target="#whatsapp" role="tab" aria-controls="whatsapp" aria-selected="false">
                                    <span class="menu-feature-icon">
                                      @php echo getArrayValue(@$whatsappFixedContent->section_value, 'icon') @endphp
                                    </span>
                                    <div class="menu-feature-content">
                                      <h6>{{getTranslatedArrayValue(@$whatsappFixedContent->section_value, 'heading') }}</h6>
                                      <p> {{getTranslatedArrayValue(@$whatsappFixedContent->section_value, 'sub_heading') }} </p>
                                    </div>
                                  </a>
                                  <a href="./whatsapp.html" class="menu-feature-item" id="email-tab" data-bs-toggle="pill" data-bs-target="#email" role="tab" aria-controls="email" aria-selected="false">
                                    <span class="menu-feature-icon">
                                      @php echo getArrayValue(@$emailFixedContent->section_value, 'icon') @endphp
                                    </span>
                                    <div class="menu-feature-content">
                                      <h6>{{getTranslatedArrayValue(@$emailFixedContent->section_value, 'heading') }}</h6>
                                      <p> {{getTranslatedArrayValue(@$emailFixedContent->section_value, 'sub_heading') }} </p>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            </div>
                            
                            <div class="col-lg-6">
                              <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="sms" role="tabpanel" aria-labelledby="sms-tab" tabindex="0">
                                  <div class="mega-menu-right">
                                    <div class="mega-menu-banner">
                                      
                                      <img src="{{showImage(config("setting.file_path.frontend.sms_service_image.path").'/'.getArrayValue(@$smsFixedContent->section_value, 'sms_service_image'),config("setting.file_path.frontend.sms_service_image.size"))}}" alt="{{ getArrayValue(@$banner_content->section_value, 'sms_service_image') }}" />
                                      <span class="menu-banner-shape">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                          <path d="M7.85997 12.457C7.85997 12.457 12.9701 5.1263 19.7609 1.87987" stroke="url(#paint0_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M9.38712 19.1634C9.38712 19.1634 13.3767 16.9872 17.1408 16.7217" stroke="url(#paint1_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M1.0002 8.61927C1.0002 8.61927 3.8938 5.55702 5.2524 2.13775" stroke="url(#paint2_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <defs>
                                            <linearGradient id="paint0_linear_227_2761" x1="18.6948" y1="11.7348" x2="7.77253" y2="8.62027" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_227_2761" x1="16.7082" y1="19.3247" x2="10.8251" y2="15.5942" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint2_linear_227_2761" x1="4.71231" y1="7.9775" x2="0.869026" y2="7.23457" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                          </defs>
                                        </svg>
                                      </span>
                                    </div>
                                    <ul class="d-flex flex-column gap-1 mt-20">
                                      @foreach($smsElementContent as $element)
                                        <li class="d-flex align-items-center gap-2">
                                          <i class="bi bi-check-circle-fill text-gradient fs-12"></i> {{ $element->section_value['short_feature'] }}
                                        </li>
                                      @endforeach
                                    </ul>
                                    <div class="mt-4">
                                      <a href="{{ route("service", ['type' => 'sms']) }}" class="i-btn btn--primary outline btn--md pill">  {{getTranslatedArrayValue(@$smsFixedContent->section_value, 'btn_name') }} </a>
                                    </div>
                                  </div>
                                </div>
                                <div class="tab-pane fade" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab" tabindex="0">
                                  <div class="mega-menu-right">
                                    <div class="mega-menu-banner">
                                      <img src="{{showImage(config('setting.file_path.frontend.whatsapp_service_image.path').'/'.getArrayValue(@$whatsappFixedContent->section_value, 'whatsapp_service_image'),config('setting.file_path.frontend.whatsapp_service_image.size'))}}" alt="" />
                                      <span class="menu-banner-shape">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                          <path d="M7.85997 12.457C7.85997 12.457 12.9701 5.1263 19.7609 1.87987" stroke="url(#paint0_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M9.38712 19.1634C9.38712 19.1634 13.3767 16.9872 17.1408 16.7217" stroke="url(#paint1_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M1.0002 8.61927C1.0002 8.61927 3.8938 5.55702 5.2524 2.13775" stroke="url(#paint2_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <defs>
                                            <linearGradient id="paint0_linear_227_2761" x1="18.6948" y1="11.7348" x2="7.77253" y2="8.62027" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_227_2761" x1="16.7082" y1="19.3247" x2="10.8251" y2="15.5942" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint2_linear_227_2761" x1="4.71231" y1="7.9775" x2="0.869026" y2="7.23457" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                          </defs>
                                        </svg>
                                      </span>
                                    </div>
                                    <ul class="d-flex flex-column gap-1 mt-20">
                                      @foreach($whatsappElementContent as $element)
                                        <li class="d-flex align-items-center gap-2">
                                          <i class="bi bi-check-circle-fill text-gradient fs-12"></i> {{ $element->section_value['short_feature'] }}
                                        </li>
                                      @endforeach
                                    </ul>
                                    <div class="mt-4">
                                      <a href="{{ route("service", ['type' => 'whatsapp']) }}" class="i-btn btn--primary outline btn--md pill">  {{getTranslatedArrayValue(@$whatsappFixedContent->section_value, 'btn_name') }} </a>
                                    </div>
                                  </div>
                                </div>
                                <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab" tabindex="0">
                                  <div class="mega-menu-right">
                                    <div class="mega-menu-banner">
                                      <img src="{{showImage(config('setting.file_path.frontend.email_service_image.path').'/'.getArrayValue(@$emailFixedContent->section_value, 'email_service_image'),config('setting.file_path.frontend.email_service_image.size'))}}" alt="" />
                                      <span class="menu-banner-shape">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                          <path d="M7.85997 12.457C7.85997 12.457 12.9701 5.1263 19.7609 1.87987" stroke="url(#paint0_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M9.38712 19.1634C9.38712 19.1634 13.3767 16.9872 17.1408 16.7217" stroke="url(#paint1_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <path d="M1.0002 8.61927C1.0002 8.61927 3.8938 5.55702 5.2524 2.13775" stroke="url(#paint2_linear_227_2761)" stroke-width="2" stroke-linecap="round" />
                                          <defs>
                                            <linearGradient id="paint0_linear_227_2761" x1="18.6948" y1="11.7348" x2="7.77253" y2="8.62027" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_227_2761" x1="16.7082" y1="19.3247" x2="10.8251" y2="15.5942" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                            <linearGradient id="paint2_linear_227_2761" x1="4.71231" y1="7.9775" x2="0.869026" y2="7.23457" gradientUnits="userSpaceOnUse">
                                              <stop stop-color="#FFA360" />
                                              <stop offset="0.57" stop-color="#F64B4D" />
                                              <stop offset="1" stop-color="#F25D6D" />
                                            </linearGradient>
                                          </defs>
                                        </svg>
                                      </span>
                                    </div>
                                    <ul class="d-flex flex-column gap-1 mt-20">
                                      @foreach($emailElementContent as $element)
                                        <li class="d-flex align-items-center gap-2">
                                          <i class="bi bi-check-circle-fill text-gradient fs-12"></i> {{ $element->section_value['short_feature'] }}
                                        </li>
                                      @endforeach
                                    </ul>
                                    <div class="mt-4">
                                      <a href="{{ route("service", ['type' => 'email']) }}" class="i-btn btn--primary outline btn--md pill">  {{getTranslatedArrayValue(@$emailFixedContent->section_value, 'btn_name') }} </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="{{ route("pricing") }}" class="{{ request()->routeIs('pricing') ? 'active' : '' }} menu-link">{{ translate("Pricing") }}</a>
                  </li>
                  <li>
                    <a href="{{ route("about") }}" class="{{ request()->routeIs('about') ? 'active' : '' }} menu-link">{{ translate("About") }}</a>
                  </li>
                  <li>
                    <a href="{{ route("contact") }}" class="{{ request()->routeIs('contact') ? 'active' : '' }} menu-link">{{ translate("Contact") }}</a>
                  </li>
                </ul>
              </nav>
              <div class="d-lg-none align-items-start align-items-lg-center gap-3 d-flex flex-column mt-80">
                <a href="{{route('login')}}" class="i-btn btn--primary outline btn--xl pill w-100"> {{ translate("Sign in") }} </a>
                @if(site_settings("onboarding_bonus") == \App\Enums\StatusEnum::TRUE->status())
                  <a href="{{route('login')}}" class="i-btn btn--primary bg--gradient btn--xl pill w-100"> {{ translate("Try Free") }} </a>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="header-right">
          <div class="d-lg-flex align-items-center gap-3 d-none">
            <a href="{{route('login')}}" class="i-btn btn--primary outline btn--xl pill"> {{ translate("Sign in") }} </a>
            @if(site_settings("onboarding_bonus") == \App\Enums\StatusEnum::TRUE->status())
              <a href="{{route('login')}}" class="i-btn btn--primary bg--gradient btn--xl pill"> {{ translate("Try Free") }} </a>
            @endif
          </div>
          <button class="d-lg-none icon-btn btn-lg primary-solid circle" id="menu-btn">
            <i class="bi bi-list"></i>
          </button>
        </div>
      </div>
    </div>
  </header>