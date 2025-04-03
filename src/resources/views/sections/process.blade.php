<section class="pt-100 pb-100">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <span class="sub-title">
                    {{getArrayValue(@$process_content->section_value, 'sub_heading')}}
                </span>

                <h3 class="section-title">
                    {{getArrayValue(@$process_content->section_value, 'heading')}}
                </h3>
            </div>

            <div class="section-header-right">
                <p class="title-description">{{getArrayValue(@$process_content->section_value, 'description')}}</p>
            </div>
        </div>

        <div class="process-step">
            @foreach($process_element as $element)
                <div class="process-step-item">
                    <div class="step-left">
                        <div class="step-img">
                            <img src="{{showImage(filePath()['frontend']['path'].'/'. @getArrayValue(@$element->section_value, 'card_image'),'545x260')}}" alt="">
                        </div>
                    </div>

                    <div class="step-middle">
                        <div class="step-indicator">
                            <span></span>
                        </div>
                    </div>

                    <div class="step-right">
                        <div class="step-content">
                            <h4>{{getArrayValue(@$element->section_value, 'heading')}}</h4>
                            <p>{{getArrayValue(@$element->section_value, 'sub_heading')}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
