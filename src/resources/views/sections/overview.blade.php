<section class="get-start pt-100 pb-100">
    <div class="get-start-bg">
        <img src="{{showImage(filePath()['frontend']['path'].'/'. getArrayValue(@$overview_content->section_value, 'background_image'),'1900x550')}}" alt="">
    </div>

    <div class="container">
        <div class="get-start-container">
            <div class="get-start-content">
                <div class="section-header dark">
                    <div class="section-header-left">
                        <span class="sub-title">
                            {{getArrayValue(@$overview_content->section_value, 'sub_heading')}}
                        </span>

                        <h3 class="section-title">{{getArrayValue(@$overview_content->section_value, 'heading')}}
                        </h3>
                    </div>

                    <div class="section-header-right">
                        <p class="title-description">{{getArrayValue(@$overview_content->section_value, 'description')}}</p>
                    </div>
                </div>

                <div class="mt-4 d-flex align-items-center justify-content-center flex-wrap gap-4">
                    <a target="_blank" href="{{getArrayValue(@$overview_content->section_value, 'btn_left_url')}}" class="ig-btn btn--lg btn--primary">{{getArrayValue(@$overview_content->section_value, 'btn_left_name')}}</a>
                    <a target="_blank" href="{{getArrayValue(@$overview_content->section_value, 'btn_right_url')}}" class="ig-btn btn--lg btn--white-outline">{{getArrayValue(@$overview_content->section_value, 'btn_right_name')}}</a>
                </div>
            </div>
            <div class="get-start-dash">
                <div>
                    <img src="{{showImage(filePath()['frontend']['path'].'/'. getArrayValue(@$overview_content->section_value, 'overview_image'),'952x450')}}" alt="">
                </div>
                <div class="get-start-dash-sm">
                    <img src="{{showImage(filePath()['frontend']['path'].'/'. getArrayValue(@$overview_content->section_value, 'overview_min_image'),'300x192')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
