@php
$fixedContent = array_values(array_filter($service_feature_content, function($item) use($type) {
    return $item->section_key === "service_feature.$type.fixed_content";
}))[0] ?? null;
$elementContent = array_values(array_filter($service_feature_element, function($item) use($type) {
    return $item->section_key === "service_feature.$type.element_content";
})) ?? null;
@endphp
<section class="service-feature pb-130">
    <div class="container-fluid container-wrapper">
      <div class="section-title">
        <div class="row gy-4">
          <div class="col-md-7">
            <h3>{{getTranslatedArrayValue(@$fixedContent->section_value, 'title') }}<span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
          <div class="col-md-5">
            <div class="d-flex align-items-center justify-content-md-end gap-3">
              <button class="i-btn btn--dark outline btn--md pill review-prev">
                <i class="bi bi-arrow-left fs-20"></i> {{ translate("Previous") }} </button>
              <button class="i-btn btn--dark btn--md pill review-next"> {{ translate("Next") }} <i class="bi bi-arrow-right fs-20"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper feature-slider">
        <div class="swiper-wrapper">
          @foreach($elementContent as $element)
          <div class="swiper-slide">
            <div class="feature-card">
              <span class="feature-icon">
                <img src="{{showImage(config("setting.file_path.frontend.element_content.service_feature.service_feature_image.path").'/'.@$element->section_value['service_feature_image'],config("setting.file_path.frontend.element_content.service_feature.service_feature_image.size"))}}" alt="service_feature" />
              </span>
              <h4>{{ $element->section_value['heading'] }}</h4>
              <p> {{ $element->section_value['description'] }} </p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>