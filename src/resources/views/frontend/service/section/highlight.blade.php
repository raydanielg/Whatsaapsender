@php
$fixedContent = array_values(array_filter($service_highlight_content, function($item) use($type) {
    return $item->section_key === "service_highlight.$type.fixed_content";
}))[0] ?? null;
$elementContent = array_values(array_filter($service_highlight_element, function($item) use($type) {
    return $item->section_key === "service_highlight.$type.element_content";
})) ?? null;
@endphp
<section class="message-feature pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row align-items-center g-lg-5 gy-5">
        <div class="col-xxl-5 col-lg-6 order-lg-0 order-1">
          <div>
            <img src="{{showImage(config("setting.file_path.frontend.service_highlight_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_highlight_image'),config("setting.file_path.frontend.service_highlight_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'service_highlight_image') }}" />
          </div>
        </div>
        <div class="col-xxl-6 col-lg-6 order-lg-1 order-0 offset-xxl-1">
          <div class="section-title">
            <h3> {{getTranslatedArrayValue(@$fixedContent->section_value, 'heading') }} <span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
          <div class="message-feature-content">
            <p> {{getTranslatedArrayValue(@$fixedContent->section_value, 'description') }} </p>
            <ul>
              @foreach($elementContent as $element)
              <li>
                <i class="bi bi-arrow-up-right-circle-fill text-gradient"></i>  {{ translate($element->section_value['heading']) }} 
              </li>
              @endforeach
            </ul>
            <div class="mt-5">
              <a href="{{getArrayValue(@$fixedContent->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'btn_name') }} </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>