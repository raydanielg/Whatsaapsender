<section class="our-journey">
    <div class="container-fluid container-wrapper">
      <div class="row gy-5 align-items-center">
        <div class="col-xxl-5 col-lg-6">
          <div class="journey-content">
            <p>{{ getTranslatedArrayValue(@$client_content->section_value, 'sub_heading') }}</p>
            <h5>{{ getTranslatedArrayValue(@$client_content->section_value, 'heading') }}</h5>
          </div>
        </div>
        <div class="col-xxl-7 col-lg-6">
          <div class="journey-counter">
            @foreach(@$client_multi_content->section_value ?? [] as $value)
              @if($loop->last)
                <span class="journey-counter-divider"></span>
              @endif
              <div class="journey-counter-item">
                <span data-value="{{ $value['heading'] }}">{{ translate($value['heading']) }}</span>
                <p>{{ translate($value['sub_heading']) }}</p>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>