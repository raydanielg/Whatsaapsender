<section class="about pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row align-items-xxl-end align-items-center gx-lg-5 gy-4">
        <div class="col-xxl-7 col-lg-6">
          <div class="section-title">
            <h3>{{getTranslatedArrayValue(@$about_overview->section_value, 'heading') }}</h3>
          </div>
          <div class="about-content">
            <p> {{getTranslatedArrayValue(@$about_overview->section_value, 'description') }} </p>
            
          </div>
        </div>
        <div class="col-xxl-5 col-lg-6">
          <div>
            <img src="{{showImage(config("setting.file_path.frontend.about_overview_image.path").'/'.getArrayValue(@$about_overview->section_value, 'about_overview_image'),config("setting.file_path.frontend.about_overview_image.size"))}}" alt="{{ getArrayValue(@$about_overview->section_value, 'about_overview_image') }}" />
          </div>
        </div>
      </div>
    </div>
  </section>