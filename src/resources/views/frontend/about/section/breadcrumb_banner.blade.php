<section class="breadcrumb-banner pb-130">
    <div class="container-fluid container-wrapper">
      <div class="banner-wrapper">
        <div class="breadcrumb-img">
          <img src="{{showImage(config("setting.file_path.frontend.about_breadcrumb_image.path").'/'.getArrayValue(@$about_content->section_value, 'about_breadcrumb_image'),config("setting.file_path.frontend.about_breadcrumb_image.size"))}}" alt="{{ getArrayValue(@$about_content->section_value, 'about_breadcrumb_image') }}" />
        </div>
        <div class="breadcrumb-content">
          <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-9">
              <h2 class="breadcrumb-title"> {{getTranslatedArrayValue(@$about_content->section_value, 'heading') }} </h2>
            </div>
          </div>
          <div class="breadcrumb-bottom">
            <div class="row gy-5 align-items-start">
              <div class="col-xxl-5 col-xl-6">
                <div class="breadcrumb-actions">
                  <a href="{{getArrayValue(@$about_content->section_value, 'transparent_btn_url') }}" class="i-btn btn--light outline btn--xl pill"> {{getTranslatedArrayValue(@$about_content->section_value, 'transparent_btn_name') }} </a>
                  <a href="{{getArrayValue(@$about_content->section_value, 'solid_btn_url') }}" class="i-btn btn--light btn--xl pill"> {{getTranslatedArrayValue(@$about_content->section_value, 'solid_btn_name') }} </a>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ translate("Home") }}</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">{{getTranslatedArrayValue(@$about_content->section_value, 'breadcrumb_title') }} </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <div class="col-xxl-6 col-xl-6 offset-xxl-1">
                <p class="breadcrumb-description"> {{getTranslatedArrayValue(@$about_content->section_value, 'sub_heading') }}  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>