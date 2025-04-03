@php
$fixedContent = array_values(array_filter($service_overview_content, function($item) use($type) {
    return $item->section_key === "service_overview.$type.fixed_content";
}))[0] ?? null;
$multipleContent = array_values(array_filter($service_overview_multi_content, function($item) use($type) {
    return $item->section_key === "service_overview.$type.multiple_static_content";
}))[0] ?? null;
@endphp

@if($type == 'whatsapp') 
<section class="message-service pb-130">
  <div class="container-fluid container-wrapper">
    <div class="row align-items-center g-xl-5 gy-4">
      <div class="col-xl-6">
        <div class="section-title">
          <h3> {{getTranslatedArrayValue(@$fixedContent->section_value, 'heading') }} <span>
              <img src="./assets/images/star.svg" alt="" />
            </span>
          </h3>
        </div>
        <div class="service-wrapper">
          <div class="message-service-content">
            <p> {{getTranslatedArrayValue(@$fixedContent->section_value, 'sub_heading') }} </p>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="ms-xxl-5">
          <img src="{{showImage(config("setting.file_path.frontend.service_overview_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_overview_image'),config("setting.file_path.frontend.service_overview_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'service_overview_image') }}" />
        </div>
      </div>
    </div>
  </div>
</section>
@else
<section class="message-service pb-130">
  <div class="container-fluid container-wrapper">
    <div class="row">
      <div class="col-xl-12 col-lg-10">
        <div class="section-title">
          <div class="row">
            <div class="col-xl-7">
              <h3> {{getTranslatedArrayValue(@$fixedContent->section_value, 'heading') }} <span>
                  <img src="./assets/images/star.svg" alt="" />
                </span>
              </h3>
            </div>
            <div class="col-xl-5">
              <p> {{getTranslatedArrayValue(@$fixedContent->section_value, 'sub_heading') }} </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row align-items-center g-xl-5 gy-5">
      <div class="col-xxl-7 col-xl-6">
        <div class="row g-0 service-wrapper me-xxl-5">
          <div class="col-md-6 service-list">
            <div class="service-card">
              <span class="service-icon">
                <i class="bi bi-activity"></i>
              </span>
              <div class="service-content">
                <h4>{{getTranslatedArrayValue(@$multipleContent->section_value['item_one'], 'heading') }}</h4>
                <p>{{getTranslatedArrayValue(@$multipleContent->section_value['item_one'], 'sub_heading') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 service-list">
            <div class="service-card">
              <span class="service-icon">
                <i class="bi bi-activity"></i>
              </span>
              <div class="service-content">
                <h4>{{getTranslatedArrayValue(@$multipleContent->section_value['item_two'], 'heading') }}</h4>
                <p>{{getTranslatedArrayValue(@$multipleContent->section_value['item_two'], 'sub_heading') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 service-list">
            <div class="service-card">
              <span class="service-icon">
                <i class="bi bi-activity"></i>
              </span>
              <div class="service-content">
                <h4>{{getTranslatedArrayValue(@$multipleContent->section_value['item_three'], 'heading') }}</h4>
                <p>{{getTranslatedArrayValue(@$multipleContent->section_value['item_three'], 'sub_heading') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 service-list">
            <div class="service-card">
              <span class="service-icon">
                <i class="bi bi-activity"></i>
              </span>
              <div class="service-content">
                <h4>{{getTranslatedArrayValue(@$multipleContent->section_value['item_four'], 'heading') }}</h4>
                <p>{{getTranslatedArrayValue(@$multipleContent->section_value['item_four'], 'sub_heading') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xxl-5 col-xl-6">
        <div>
          <img src="{{showImage(config("setting.file_path.frontend.service_overview_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_overview_image'),config("setting.file_path.frontend.service_overview_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'service_overview_image') }}" />
        </div>
      </div>
    </div>
  </div>
</section>
@endif
