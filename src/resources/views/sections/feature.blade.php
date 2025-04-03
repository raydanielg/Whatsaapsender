<section id="features" class="pt-100 pb-100">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <span class="sub-title">{{getArrayValue(@$feature_content->section_value, 'sub_heading')}}</span>
                <h3 class="section-title">{{getArrayValue(@$feature_content->section_value, 'heading')}}</h3>
            </div>

            <div class="section-header-right">
                <p class="title-description">{{getArrayValue(@$feature_content->section_value, 'description')}}</p>
            </div>
        </div>

        <div class="feature-items">
            @foreach($feature_element as $element)
                    <div class="feature-item">
                        <div class="feature-item-left">
                            <span class="feature-icon">
                                {!!getArrayValue(@$element->section_value, 'feature_icon')!!}
                            </span>
                            <h4 class="feature-title">{{getArrayValue(@$element->section_value, 'title')}}</h4>
                        </div>

                        <div class="feature-item-desc">
                            <p>{{getArrayValue(@$element->section_value, 'description')}}</p>
                        </div>

                        <div class="feature-item-right">
                            <a href="javascript:void(0)" type="button" data-bs-toggle="modal" data-bs-target="#section-{{$loop->index}}" class="ig-btn btn--primary-outline btn--sm btn--capsule align-items-center">{{getArrayValue($element->section_value, 'btn_name')}} <i class="fa-solid fa-arrow-right-long ps-3"></i></a>
                        </div>
                    </div>

                <div class="modal fade" id="section-{{$loop->index}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog nafiz modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">
                            {{translate("Details")}}
                          </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{getArrayValue(@$element->section_value, 'description')}}
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            {{translate('Close')}}
                          </button>

                        </div>
                      </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>




