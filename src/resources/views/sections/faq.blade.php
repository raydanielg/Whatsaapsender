<section class="faq pt-100 pb-100" id="faq">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="section-header section-header-two align-items-start text-start">
                    <span class="sub-title">FAQ</span>
                    <h3 class="section-title">
                        {{translate(getArrayValue(@$faq_content->section_value, 'heading'))}}
                    </h3>
                    <p class="title-description">{{translate(getArrayValue(@$faq_content->section_value, 'sub_heading'))}}</p>
                </div>

                <div class="faq-left-content">
                    <div class="faq-card mb-4">
                        <div class="faq-card-title">
                            @php echo translate(getArrayValue(@$faq_content->section_value, 'message_icon')); @endphp
                            <h4> {{translate(getArrayValue(@$faq_content->section_value, 'message_title'))}}</h4>
                        </div>
                        <p> {{translate(getArrayValue(@$faq_content->section_value, 'message_description'))}}</p>
                    </div>
                    <div class="mt-5 d-flex align-items-center flex-wrap gap-4">
                        <a target="_blank" href="{{getArrayValue(@$faq_content->section_value, 'btn_left_url')}}" class="ig-btn btn--lg btn--primary">{{getArrayValue(@$faq_content->section_value, 'btn_left_name')}}</a>
                        <a target="_blank" href="{{getArrayValue(@$faq_content->section_value, 'btn_right_url')}}" class="ig-btn btn--lg btn--primary-outline">{{getArrayValue(@$faq_content->section_value, 'btn_right_name')}}</a>
                       
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="custom-accordion">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        @foreach($faq_element as $key => $element)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne-{{$key}}">
                                    <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-{{$key}}"
                                            aria-expanded="false" aria-controls="flush-collapseOne">
                                        {{translate(getArrayValue(@$element->section_value, 'question'))}}
                                    </button>
                                </h2>
                                <div id="flush-collapseOne-{{$key}}" class="accordion-collapse collapse @if($loop->first) show @endif"
                                     aria-labelledby="flush-headingOne-{{$key}}"
                                     data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">{{translate(getArrayValue(@$element->section_value, 'answer'))}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
