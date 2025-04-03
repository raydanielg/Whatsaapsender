<section class="contact pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="contact-wrapper">
        <div class="row gy-5 gx-lg-5">
          <div class="col-xl-6 col-lg-6">
            <div class="section-title">
              <h3>{{getTranslatedArrayValue(@$contact_content->section_value, 'heading') }}</h3>
              <p class="fs-24">{{getTranslatedArrayValue(@$contact_content->section_value, 'sub_heading') }}</p>
            </div>

            <form action="{{ route("contact.get_in_touch") }}" class="contact-form" method="POST">
                @csrf
                <input type="text" name="subject" value="{{ site_settings("site_name"). " get in touch contact" }}" hidden>
                <div class="form-element">
                  <input type="text" name="email_from_name" placeholder="{{ translate("Enter your name") }}" class="form-control" />
                </div>
                <div class="form-element">
                  <input type="email" name="email_to_address" class="form-control" placeholder="{{ translate("Enter your email address") }}" />
                </div>
                <div class="form-element">
                  <textarea name="message" class="form-control" rows="4" placeholder="{{ translate("Go ahead, We are listening...") }}"></textarea>
                </div>
                <button type="submit" class="i-btn btn--primary bg--gradient btn--xl w-100 submit-btn mt-3"> {{ translate("Submit") }} </button>
              </form>
          </div>

          <div class="col-xl-5 offset-xl-1 col-lg-6 pt-lg-0 pt-4">
            <ul class="contact-list">
              <li>
                <span>
                  <i class="bi bi-geo-alt-fill"></i>
                </span> {{ site_settings("address") }}
              </li>
              <li class="text-break">
                <span>
                  <i class="bi bi-telephone-fill"></i>
                </span>{{ site_settings("phone") }}
              </li>
              <li class="text-break">
                <span>
                  <i class="bi bi-envelope-fill"></i>
                </span> {{ site_settings("email") }}
              </li>
            </ul>
            <div class="map-wrapper">
              @php echo site_settings("google_map_iframe"); @endphp
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>