<section class="why-us">
    <div class="container-fluid container-wrapper">
      <div class="why-us-wrapper">
        <h3 class="why-us-title">{{getTranslatedArrayValue(@$advantage_content->section_value, 'heading') }}</h3>
        <div class="row g-4 align-items-end">
          
          @foreach(@$advantage_multi_content->section_value ?? [] as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-md-6 {{ array_key_exists($key, array_flip(['item_three', 'item_five'])) ? 'offset-xxl-3' : '' }} ">
              <div class="why-us-card {{ $loop->first ? 'active' : '' }} {{ $loop->iteration > 4 ? 'mt-120' : '' }}">
                <span></span>
                <h5> {{translate($value['heading'])}} </h5>
                <p> {{translate($value['sub_heading'])}} </p>
              </div>
            </div>
          @endforeach
          <div class="col-xxl-3 col-xl-4 col-md-6">
            <div class="d-flex flex-column align-items-end">
              <a class="combine-btn" href="{{getArrayValue(@$advantage_content->section_value, 'btn_url') }}">
                <span class="combine-text">
                  <span class="text-gradient">{{getTranslatedArrayValue(@$advantage_content->section_value, 'btn_name') }}</span>
                </span>
                <span class="combine-icon">
                  <i class="bi bi-arrow-up-right"></i>
                </span>
                <span class="combine-shape">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 45" fill="none">
                    <path d="M0 17.844C0 3.82463 15.9519 -4.22921 27.2326 4.09472L28.4013 4.95705C34.0131 9.09802 41.7212 8.89587 47.1084 4.46645C57.0417 -3.70095 72 3.36537 72 16.2253V28.1999C72 41.2304 57.3748 48.9074 46.65 41.5065C41.3626 37.8578 34.4167 37.6857 28.9551 41.0681L27.8135 41.7751C15.6754 49.2922 0 40.562 0 26.2848V17.844Z" fill="white"></path>
                  </svg>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>