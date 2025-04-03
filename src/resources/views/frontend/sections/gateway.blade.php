<section class="gateways">
    <div class="container-fluid container-wrapper">
      <div class="gateway-wrapper">
        <div class="row justify-content-center">
          <div class="col-xl-10 col-lg-11 text-center">
            <h4 class="gateway-title">{{getTranslatedArrayValue(@$gateway_content->section_value, 'heading') }}</h4>
            <div class="swiper gateway-slider">
              <div class="swiper-wrapper">
                @foreach($gateway_element as $element)
                <div class="swiper-slide" title="{{ $element->section_value['name'] }}">
                  <div class="gateway-img">
                    <img src="{{showImage(config("setting.file_path.frontend.element_content.gateway.gateway_image.path").'/'.@$element->section_value['gateway_image'],config("setting.file_path.frontend.element_content.gateway.gateway_image.size"))}}" alt="gateway" />
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="gateway-bottom">
              <p> {{getTranslatedArrayValue(@$gateway_content->section_value, 'sub_heading') }} </p>
              <a href="{{getArrayValue(@$gateway_content->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill"> {{getTranslatedArrayValue(@$gateway_content->section_value, 'btn_name') }} </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>