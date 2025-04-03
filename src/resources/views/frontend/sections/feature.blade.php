<section class="feature pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row">
        <div class="col-xxl-6 col-lg-10">
          <div class="section-title">
            <h3> {{getTranslatedArrayValue(@$feature_content->section_value, 'heading') }} <span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
        </div>
      </div>
      <div class="row g-xxl-5 gy-5 align-items-center">
        <div class="col-xxl-6">
          <div class="feature-tab">
            <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              @foreach($feature_element as $element)
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ 'feature-'.$element->id }}-tab" data-bs-toggle="pill" href="#{{ 'feature-'.$element->id }}" role="tab" aria-controls="{{ 'feature-'.$element->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" {{ !$loop->first ? "tabindex=-1" : ''}}> {{ $element->section_value['heading'] }} <span>
                  <i class="bi bi-arrow-up-right"></i>
                  </span>
                </a>
              @endforeach
            </div>
          </div>
        </div>
        <div class="col-xxl-6">
          <div class="tab-content" id="v-pills-tabContent">
            @foreach($feature_element as $element)
            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="{{ 'feature-'.$element->id }}" role="tabpanel" aria-labelledby="{{ 'feature-'.$element->id }}-tab" tabindex="0">
              <div class="row g-4 align-items-center">
                <div class="col-xxl-7 col-md-6">
                  <div class="feature-tab-img">
                    <img src="{{showImage(config("setting.file_path.frontend.element_content.feature.feature_image.path").'/'.@$element->section_value['feature_image'],config("setting.file_path.frontend.element_content.feature.feature_image.size"))}}" alt="feature" class="rounded w-100" />
                  </div>
                </div>
                <div class="col-xxl-5 col-md-6">
                  <div class="row g-4">
                    <div class="col-md-12 col-sm-6">
                      <div class="feature-card">
                        <p> {{ $element->section_value['item_one']['sub_heading'] ?? 'N\A'}} </p>
                        <a href="{{ $element->section_value['item_one']['btn_url'] ?? 'N\A'}}">{{ translate($element->section_value['item_one']['heading']) ?? translate('N\A')}}<span>
                            <i class="bi bi-arrow-up-right"></i>
                          </span>
                        </a>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-6">
                      <div class="feature-card bg--gradient">
                        <p> {{ $element->section_value['item_two']['sub_heading'] }} </p>
                        <a href="{{ $element->section_value['item_two']['btn_url'] ?? 'N\A'}}">{{ translate($element->section_value['item_two']['heading']) ?? translate('N\A')}}<span>
                            <i class="bi bi-arrow-up-right"></i>
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      
    </div>
  </section>