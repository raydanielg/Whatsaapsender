<div class="col-lg-6 order-lg-0 order-1">
    <div class="auth-left">
      <div>
        <div class="section-title mb-5">
          <h3 class="text-gradient"> {{getTranslatedArrayValue(@$user_auth_content->section_value, 'heading') }} </h3>
        </div>
        <div class="auth-features">
          <div class="auth-feature-item">
            <span class="auth-feature-icon text-gradient">
              <i class="bi bi-people"></i>
            </span>
            <div class="auth-feature-info">
              <h6>{{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_one'], 'heading') }}</h6>
              <p> {{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_one'], 'sub_heading') }} </p>
            </div>
          </div>
          <div class="auth-feature-item">
            <span class="auth-feature-icon text-gradient">
              <i class="bi bi-patch-check"></i>
            </span>
            <div class="auth-feature-info">
              <h6>{{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_two'], 'heading') }}</h6>
              <p> {{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_two'], 'sub_heading') }} </p>
            </div>
          </div>
          <div class="auth-feature-item">
            <span class="auth-feature-icon text-gradient">
              <i class="bi bi-shield-check"></i>
            </span>
            <div class="auth-feature-info">
              <h6>{{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_three'], 'heading') }}</h6>
              <p> {{getTranslatedArrayValue(@$user_auth_multi_content->section_value['item_three'], 'sub_heading') }} </p>
            </div>
          </div>
        </div>
      </div>
      <div class="auth-footer">
        <nav av class="auth-nav">
          @foreach($user_auth_element as $element)
            <a href="{{ $element->section_value['btn_url'] }}" class="auth-nav-link">{{ translate($element->section_value['btn_name']) }}</a>
          @endforeach
        </nav>
      </div>
    </div>
  </div>