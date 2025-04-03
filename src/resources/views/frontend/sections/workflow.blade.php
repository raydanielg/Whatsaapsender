<section class="work-process">
    <div class="container-fluid container-wrapper">
      <div class="work-process-wrapper">
        <div class="row justify-content-center">
          <div class="col-xxl-8 col-lg-10">
            <div class="section-title text-center">
              <h3 class="text-light mx-auto">{{getTranslatedArrayValue(@$workflow_content->section_value, 'heading') }}</h3>
            </div>
          </div>
        </div>
        <div class="work-tab-wrapper">
          <!-- Tab panes -->
          <div class="tab-content text-light">
            @foreach($workflow_element as $element)
            <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="{{ 'feature-'.$element->id }}" role="tabpanel" aria-labelledby="{{ 'feature-'.$element->id }}-tab" tabindex="0">
                <div class="row g-5">
                  <div class="col-lg-6">
                    <div class="process-card flex-lg-column flex-column-reverse pt-100">
                      <p> {{ $element->section_value['item_one']['process'] ?? 'N\A'}} </p>
                      <div class="process-img">
                        <img src="{{showImage(config("setting.file_path.frontend.element_content.workflow.process_image.path").'/'.@$element->section_value['item_one']['process_image'],config("setting.file_path.frontend.element_content.workflow.process_image.size"))}}" alt="feature" class="rounded w-100" />
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="process-card">
                      <div class="process-img">
                        <img src="{{showImage(config("setting.file_path.frontend.element_content.workflow.process_image.path").'/'.@$element->section_value['item_two']['process_image'],config("setting.file_path.frontend.element_content.workflow.process_image.size"))}}" alt="feature" class="rounded w-100" />
                      </div>
                      <p> {{ translate($element->section_value['item_two']['process'] ?? 'N\A')}} </p>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <!-- Nav tabs -->
          <div class="process-tab">
            <ul class="nav nav-tabs" role="tablist">
              @foreach($workflow_element as $element)
              <li class="nav-item" role="presentation">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ 'feature-'.$element->id }}-tab" data-bs-toggle="pill" href="#{{ 'feature-'.$element->id }}" role="tab" aria-controls="{{ 'feature-'.$element->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" {{ !$loop->first ? "tabindex=-1" : ''}}> {{ translate($element->section_value['heading']) }} <span>
                    <i class="bi bi-arrow-up-right"></i>
                  </span>
                </a>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>