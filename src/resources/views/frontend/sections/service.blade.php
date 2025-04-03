@php
$fixedSmsContent = array_values(array_filter($service_breadcrumb_content, function($item) {
    return $item->section_key === "service_breadcrumb.sms.fixed_content";
}))[0] ?? null;
$fixedWhatsappContent = array_values(array_filter($service_breadcrumb_content, function($item) {
    return $item->section_key === "service_breadcrumb.whatsapp.fixed_content";
}))[0] ?? null;
$fixedEmailContent = array_values(array_filter($service_breadcrumb_content, function($item) {
    return $item->section_key === "service_breadcrumb.email.fixed_content";
}))[0] ?? null;
@endphp
<section id="service-section" class="service pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row">
        <div class="col-xxl-6 col-xl-8 col-md-10">
          <div class="section-title">
            <h3> {{getTranslatedArrayValue(@$service_menu_common->section_value, 'heading') }} <span>
              <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
        </div>
      </div>
      @if($fixedSmsContent)
        <div class="service-list">
          <div class="service-item">
            <div class="row align-items-center g-4">
              <div class="col-xxl-3">
                <div class="service-title">
                  <span>{{ translate("01") }}</span>
                  <h4>{{ translate($fixedSmsContent->section_value['heading']) }}</h4>
                </div>
              </div>
              <div class="col-xxl-6 col-lg-7">
                <p class="service-description"> {{ translate($fixedSmsContent->section_value['sub_heading']) }} </p>
              </div>
              <div class="col-xxl-3 col-lg-5">
                <div class="service-action">
                  <div class="service-img">
                    <img src="{{showImage(config("setting.file_path.frontend.service_breadcrumb_image.path").'/'.getArrayValue(@$fixedSmsContent->section_value, 'service_breadcrumb_image'),config("setting.file_path.frontend.service_breadcrumb_image.size"))}}" alt="{{ getArrayValue(@$fixedSmsContent->section_value, 'service_breadcrumb_image') }}" />
                  </div>
                  <a href="{{ route('service', ['type' => 'sms']) }}" class="service-btn">
                    <p>{{ translate("Read More") }}</p>
                    <span class="service-btn-icon">
                      <i class="bi bi-arrow-up-right"></i>
                    </span>
                  </a>
                </div>
              </div>
            </div>
        </div>
      @endif
      @if($fixedWhatsappContent)
        <div class="service-list">
          <div class="service-item">
            <div class="row align-items-center g-4 mt-3">
              <div class="col-xxl-3">
                <div class="service-title">
                  <span>{{ translate("02") }}</span>
                  <h4>{{ translate($fixedWhatsappContent->section_value['heading']) }}</h4>
                </div>
              </div>
              <div class="col-xxl-6 col-lg-7">
                <p class="service-description"> {{ translate($fixedWhatsappContent->section_value['sub_heading']) }} </p>
              </div>
              <div class="col-xxl-3 col-lg-5">
                <div class="service-action">
                  <div class="service-img">
                    <img src="{{showImage(config("setting.file_path.frontend.service_breadcrumb_image.path").'/'.getArrayValue(@$fixedWhatsappContent->section_value, 'service_breadcrumb_image'),config("setting.file_path.frontend.service_breadcrumb_image.size"))}}" alt="{{ getArrayValue(@$fixedWhatsappContent->section_value, 'service_breadcrumb_image') }}" />
                  </div>
                  <a href="{{ route('service', ['type' => 'whatsapp']) }}" class="service-btn">
                    <p>{{ translate("Read More") }}</p>
                    <span class="service-btn-icon">
                      <i class="bi bi-arrow-up-right"></i>
                    </span>
                  </a>
                </div>
              </div>
            </div>
        </div>
      @endif
     
      @if($fixedEmailContent)
        <div class="service-list">
          <div class="service-item">
            <div class="row align-items-center g-4 mt-3">
              <div class="col-xxl-3">
                <div class="service-title">
                  <span>{{ translate("03") }}</span>
                  <h4>{{ translate($fixedEmailContent->section_value['heading']) }}</h4>
                </div>
              </div>
              <div class="col-xxl-6 col-lg-7">
                <p class="service-description"> {{ translate($fixedEmailContent->section_value['sub_heading']) }} </p>
              </div>
              <div class="col-xxl-3 col-lg-5">
                <div class="service-action">
                  <div class="service-img">
                    <img src="{{showImage(config("setting.file_path.frontend.service_breadcrumb_image.path").'/'.getArrayValue(@$fixedEmailContent->section_value, 'service_breadcrumb_image'),config("setting.file_path.frontend.service_breadcrumb_image.size"))}}" alt="{{ getArrayValue(@$fixedEmailContent->section_value, 'service_breadcrumb_image') }}" />
                  </div>
                  <a href="{{ route('service', ['type' => 'email']) }}" class="service-btn">
                    <p>{{ translate("Read More") }}</p>
                    <span class="service-btn-icon">
                      <i class="bi bi-arrow-up-right"></i>
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>