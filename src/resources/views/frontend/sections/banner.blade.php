<section class="banner">
    <div class="container-fluid container-wrapper">
      <div class="banner-wrapper">
        <div class="row gy-5 align-items-center">
          <div class="col-xxl-5 col-xl-6">
            
            <div class="banner-content">
              <h1 class="banner-title text-gradient">{{getTranslatedArrayValue(@$banner_content->section_value, 'heading') }}<span class="banner-title-shape">
                  <span>
                    <img src="{{asset('assets/file/default/frontend/long-arrow.svg')}}" alt="long-arrow"/>
                  </span>
                  <span>
                    <img src="{{asset('assets/file/default/frontend/globe.svg')}}" alt="long-arrow"/>
                  </span>
                </span>
              </h1>
              <p class="banner-description"> {{ getTranslatedArrayValue(@$banner_content->section_value, 'sub_heading') }} </p>
              <div class="banner-actions">
                <a class="combine-btn" href="{{ getArrayValue(@$banner_content->section_value, 'btn_url') }}">
                  <span class="combine-text">
                    <span class="text-gradient">{{ getTranslatedArrayValue(@$banner_content->section_value, 'btn_name') }}</span>
                  </span>
                  <span class="combine-icon">
                    <i class="bi bi-arrow-up-right"></i>
                  </span>
                  <span class="combine-shape">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 45" fill="none">
                      <path d="M0 17.844C0 3.82463 15.9519 -4.22921 27.2326 4.09472L28.4013 4.95705C34.0131 9.09802 41.7212 8.89587 47.1084 4.46645C57.0417 -3.70095 72 3.36537 72 16.2253V28.1999C72 41.2304 57.3748 48.9074 46.65 41.5065C41.3626 37.8578 34.4167 37.6857 28.9551 41.0681L27.8135 41.7751C15.6754 49.2922 0 40.562 0 26.2848V17.844Z" fill="white" />
                    </svg>
                  </span>
                </a>
                {{-- <a href="#" class="work-btn">{{ translate("HOW IT WORKS") }}</a> --}}
              </div>
              <div class="overall-user">
                <div class="avatar-group">
                  @foreach($users->take(3) as $user)
                  <span class="avatar-group-item">
                    <img class="avatar avatar-lg circle img-fluid" src="{{showImage(filePath()['profile']['user']['path'].'/'.$user->image, filePath()['profile']['user']['size'])}}" alt="{{ $user->name }}" />
                  </span>
                  @endforeach
                 
                </div>
                <div class="user-content">
                  <h6>{{ $users->count().' '.translate("Business People")}}</h6>
                  <p>{{ translate("Already registered") }}</p>
                </div>
              </div>
              <div class="providers">
                <div class="row g-xl-4 align-items-center">
                  <div class="col-lg-4">
                    <p>{{ getTranslatedArrayValue(@$banner_content->section_value, 'element_heading') }}</p>
                  </div>
                  <div class="col-lg-8">
                    <div class="row row-cols-sm-3 row-cols-2 justify-content-lg-start justify-content-center align-items-center gy-3 gx-4">
                      @foreach($banner_element as $element)
                        <div class="col">
                          <img src="{{showImage(config("setting.file_path.frontend.element_content.banner.company_logo.path").'/'.@$element->section_value['company_logo'],config("setting.file_path.frontend.element_content.banner.company_logo.size"))}}" alt="banner" class="w-100" />
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xxl-6 offset-xxl-1 col-xl-6">
            <div class="banner-right">
              <div class="banner-img-lg">
                <img src="{{showImage(config("setting.file_path.frontend.banner_image.path").'/'. getArrayValue(@$banner_content->section_value, 'banner_image'),config("setting.file_path.frontend.banner_image.size"))}}" alt="{{ getArrayValue(@$banner_content->section_value, 'banner_image') }}" />
                <span class="video-wrapper">
                  <img src="{{showImage(config("setting.file_path.frontend.video_button_image.path").'/'. getArrayValue(@$banner_content->section_value, 'video_button_image'),config("setting.file_path.frontend.video_button_image.size"))}}" alt="{{ getArrayValue(@$banner_content->section_value, 'video_button_image') }}" />
                  <a href="{{ getArrayValue(@$banner_content->section_value, 'video_url') }}" data-dimbox="youtube" data-dimbox-ratio="16x9" class="video-play-btn">
                    <i class="bi bi-play-fill text-gradient"></i>
                  </a>
                </span>
                <div class="banner-countdown">
                  <span class="countdown-text">{{ getTranslatedArrayValue(@$banner_content->section_value, 'information_card_heading') }}</span>
                  <p> {{ getTranslatedArrayValue(@$banner_content->section_value, 'information_card_sub_heading') }} </p>
                  <div class="countdown-progress"></div>
                  <div class="countdown-star">
                    <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
                  </div>
                </div>
              </div>
              <div class="banner-img-sm">
                <img src="{{showImage(config("setting.file_path.frontend.banner_second_image.path").'/'. getArrayValue(@$banner_content->section_value, 'banner_second_image'),config("setting.file_path.frontend.banner_second_image.size"))}}" alt="{{ getArrayValue(@$banner_content->section_value, 'banner_second_image') }}" />
              </div>
            </div>
          </div>
        </div>
        <button href="#service-section" class="cursor-down">
          <i class="bi bi-arrow-down text-gradient"></i>
        </button>
      </div>
    </div>
  </section>