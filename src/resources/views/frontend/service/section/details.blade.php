@php
$fixedContent = array_values(array_filter($service_details_content, function($item) use($type) {
    return $item->section_key === "service_details.$type.fixed_content";
}))[0] ?? null;
$elementContent = array_values(array_filter($service_details_element, function($item) use($type) {
    return $item->section_key === "service_details.$type.element_content";
})) ?? null;
@endphp

@if($type == 'sms')
<section class="pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row align-items-xxl-end align-items-center g-xl-5 gy-5">
        <div class="col-lg-6">
          <div class="campaign-content">
            <div class="section-title">
              <h3>{{getTranslatedArrayValue(@$fixedContent->section_value, 'heading') }}</h3>
            </div>
            <div class="mb-5">
              <p class="fs-20"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'description') }}</p>
            </div>
            <a href="{{getArrayValue(@$fixedContent->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'btn_name') }} </a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="ms-xxl-5">
            <img src="{{showImage(config("setting.file_path.frontend.service_details_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_details_image'),config("setting.file_path.frontend.service_details_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'service_details_image') }}" />
          </div>
        </div>
      </div>
    </div>
  </section>
  @else
  <section class="pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row">
        <div class="col-xl-12 col-lg-11">
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
      <div class="row align-items-xxl-start align-items-center g-xl-5 gy-5">
        <div class="col-lg-6">
          <div class="me-xxl-5">
            <div class="accordion-wrapper template-accordion mb-60">
              <div class="accordion" id="template-accordion">
                @foreach($elementContent as $element)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading_{{ $element->id }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $element->id }}" aria-expanded="true" aria-controls="collapse_{{ $element->id }}"> {{ translate($element->section_value['heading']) }} </button>
                  </h2>
                  <div id="collapse_{{ $element->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading_{{ $element->id }}">
                    <div class="accordion-body">
                      <p> {{ translate($element->section_value['description']) }} </p>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <a href="{{getArrayValue(@$fixedContent->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'btn_name') }} </a>
          </div>
        </div>
        <div class="col-lg-6">
          <div>
            <img src="{{showImage(config("setting.file_path.frontend.service_details_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_details_image'),config("setting.file_path.frontend.service_details_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'service_details_image') }}" />
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif