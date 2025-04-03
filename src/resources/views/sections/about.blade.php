<section class="grow pt-100 pb-100" id="about">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <span class="sub-title">{{getTranslatedArrayValue(@$about_content->section_value, 'sub_heading')}}</span>
                <h3 class="section-title">{{getTranslatedArrayValue(@$about_content->section_value, 'heading')}}</h3>
            </div>

            <div class="section-header-right">
                <p class="title-description">{{getTranslatedArrayValue(@$about_content->section_value, 'description')}}</p>
            </div>
        </div>
    </div>

    <div class="grow-container">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-6 col-lg-8">
                    <div class="grow-left mt-5">
                        <div class="row g-4">
                            @foreach($about_element as $element)
                                <div class="col-md-6 mb-5">
                                    <div class="grow-card">
                                        <span class="icon-avaters">
                                           @php echo getArrayValue(@$element->section_value, 'icon') @endphp
                                        </span>

                                        <div class="grow-card-content">
                                            <h5>{{getTranslatedArrayValue(@$element->section_value, 'title')}}</h5>
                                            <p>{{getTranslatedArrayValue(@$element->section_value, 'sub_title')}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                         <a target="_blank" href="{{getArrayValue(@$about_content->section_value, 'btn_url')}}" class="ig-btn btn--lg btn--primary">{{getTranslatedArrayValue(@$about_content->section_value, 'btn_name')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grow-img">
            <img src="{{showImage(filePath()['frontend']['path'].'/'. @getArrayValue(@$about_content->section_value, 'about_image'),'895x500')}}" alt="{{@getArrayValue(@$about_content->section_value,'about_image')}}">
        </div>
    </div>

    <div class="grow-shape-1">
        <img src="https://i.ibb.co/7WB6kXy/v1069-elements-004.png" alt="v1069-elements-004">
    </div>

    <div class="grow-shape-2">
       <img src="https://i.ibb.co/PmCjn7P/v1069-elements-007.png" alt="v1069-elements-007">
    </div>
</section>
