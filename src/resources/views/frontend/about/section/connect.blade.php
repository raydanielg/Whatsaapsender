<section class="connect">
    <div class="container-fluid container-wrapper">
      <div class="connect-wrapper">
        <div class="row justify-content-center">
          <div class="col-xxl-8 col-lg-10">
            <div class="section-title text-center">
              <h3 class="text-light mx-auto">{{getTranslatedArrayValue(@$connect_content->section_value, 'heading') }}</h3>
            </div>
          </div>
        </div>
        <div class="row justify-content-center mt-xl-5 mb-30">
          <div class="col-xxl-11">
            <div class="row justify-content-center gx-xxl-5 gy-5">
                @foreach($connect_element as $element)
                <div class="col-lg-4 col-sm-6">
                    <div class="connect-card">
                      <div class="connect-icon">
                        <img src="{{showImage(config("setting.file_path.frontend.element_content.connect_section.conenct_image.path").'/'.@$element->section_value['conenct_image'],config("setting.file_path.frontend.element_content.connect_section.conenct_image.size"))}}" alt="feature" class="rounded w-100" />
                      </div>
                      <h5>{{ getTranslatedArrayValue(@$element->section_value, 'heading') }}</h5>
                      <p>{{ getTranslatedArrayValue(@$element->section_value, 'sub_heading') }}</p>
                      <a href="{{ getArrayValue(@$element->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill">{{ getTranslatedArrayValue(@$element->section_value, 'btn_name') }}</a>
                    </div>
                  </div>
                @endforeach
             
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>