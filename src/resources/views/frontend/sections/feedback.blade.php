<section class="testimonial pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row gy-4 align-items-end mb-60">
        <div class="col-md-7">
          <div class="section-title mb-0">
            <h3> {{getTranslatedArrayValue(@$feedback_content->section_value, 'heading') }} <span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
        </div>
        <div class="col-md-5">
          <div class="d-flex align-items-center justify-content-end gap-3">
            <button class="i-btn btn--dark outline btn--md pill review-prev">
              <i class="bi bi-arrow-left fs-20"></i> {{ translate("Previous") }} </button>
            <button class="i-btn btn--dark btn--md pill review-next"> {{ translate("Next") }} <i class="bi bi-arrow-right fs-20"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="swiper review-slider">
        <div class="swiper-wrapper">
          @foreach($feedback_element as $element)
          <div class="swiper-slide">
            <div class="review-card">
              <p>{{ translate($element->section_value['message']) }}</p>
              <div class="reviewer">
                <span class="reviewer-img">
                  <img src="{{showImage(config("setting.file_path.frontend.element_content.feedback.reviewer_image.path").'/'.@$element->section_value['reviewer_image'],config("setting.file_path.frontend.element_content.feedback.reviewer_image.size"))}}" alt="feature" class="rounded w-100" />
                </span>
                <div class="reviewer-info">
                  <h6>{{ translate($element->section_value['name']) }}</h6>
                  <span>{{ translate($element->section_value['designation']) }}</span>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
</section>