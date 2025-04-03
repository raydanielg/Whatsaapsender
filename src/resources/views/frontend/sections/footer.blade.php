<footer class="footer">
    <div class="container-fluid container-wrapper">
      <div class="footer-content">
        <div class="footer-top">
          <div class="row gy-4 align-items-end">
            <div class="col-xl-9 col-lg-9">
              <div class="section-title mb-0">
                <h3 class="text-light"> {{getTranslatedArrayValue(@$footer_content->section_value, 'heading') }} </h3>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3">
              <div class="d-flex flex-column align-items-lg-end">
                <a href="{{getArrayValue(@$footer_content->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill w-max-content"> {{getTranslatedArrayValue(@$footer_content->section_value, 'btn_name') }} </a>
              </div>
            </div>
          </div>
        </div>
        <div class="footer-middle">
          <div class="row gx-lg-5 gy-5">
            <div class="col-xxl-4 col-lg-4">
              <div class="me-xl-5">
                <a href="#" class="logo-wrapper">
                  <img src="{{showImage(config('setting.file_path.panel_logo.path').'/'.site_settings('panel_logo'),config('setting.file_path.panel_logo.size'))}}" alt="logo" />
                </a>
                <p> {{getTranslatedArrayValue(@$footer_content->section_value, 'sub_heading') }} </p>
                <ul class="footer-social">
                  @foreach($social_element as $element)
                  
                  <li title="{{ $element->section_value['title']  }}">
                    <a href="{{$element->section_value['url'] }}">
                      @php echo $element->section_value['icon'] @endphp
                    </a>
                  </li>
                  @endforeach
                </ul>
                <div class="payment-logos mt-30">
                  <img src="{{showImage(config("setting.file_path.frontend.payment_gateway_image.path").'/'.getArrayValue(@$footer_content->section_value, 'payment_gateway_image'),config("setting.file_path.frontend.payment_gateway_image.size"))}}" alt="banner" class="w-100" />
                </div>
              </div>
            </div>
            <div class="col-xxl-7 offset-xxl-1 col-lg-8">
              <div class="row gy-5 gx-4">
                <div class="col-lg-4 col-sm-6">
                  <div class="footer-nav">
                    <h6>{{ translate("Quick Navigation") }}</h6>
                    <ul>
                      <li>
                        <a href="{{ route("blog") }}">{{ translate("Blogs") }}</a>
                      </li>
                      <li>
                        <a href="{{ route("about") }}">{{ translate("About Us") }}</a>
                      </li>
                      <li>
                        <a href="{{ route("contact") }}">{{ translate("Contact") }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                  <div class="footer-nav">
                    <h6>{{ translate("Licence") }}</h6>
                    <ul>
                      @foreach($pages as $page)
                          <li>
                              <a target="_blank" href="{{route('page',[Str::slug(getArrayValue(@$page->section_value, 'title')),$page->id])}}" class="footer-menu">{{getTranslatedArrayValue(@$page->section_value, 'title')}}</a>
                          </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="footer-nav">
                    <h6>{{ translate("Information") }}</h6>
                    <div class="contact-info">
                      <a href="mailto:info@example.com" class="contact-info-item text-break">
                        <i class="bi bi-envelope"></i> {{ site_settings('email') }} </a>
                      <a href="tel:012-345-67891" class="contact-info-item">
                        <i class="bi bi-telephone"></i> {{ site_settings('phone') }} </a>
                      <span class="contact-info-item">
                        <i class="bi bi-geo-alt"></i> {{ site_settings('address') }} </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="copy-right">
          <p>{{ site_settings('copyright') }}</p>
        </div>
      </div>
    </div>
</footer>