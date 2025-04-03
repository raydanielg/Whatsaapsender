<div class="banner" id="home">
    <div class="container-fluid fluid-wrapper">
        <div class="row gx-5 gy-xl-0 gy-5 align-items-center">
            <div class="col-xl-7">
                <div class="banner-left">
                    <h3>{{getArrayValue(@$banner_content->section_value, 'sub_heading')}}</h3>
                    <h1>
                        {{getArrayValue(@$banner_content->section_value, 'heading')}}
                    </h1>
                    <p>{{getArrayValue(@$banner_content->section_value, 'description')}}</p>
                    <div class="d-flex align-items-center flex-wrap gap-xl-5  gap-4 banner-action">
                        <a target="_blank" href="{{getArrayValue(@$banner_content->section_value, 'btn_url')}}" class="ig-btn btn--lg btn--primary btn--capsule banner-btn">
                            {{getArrayValue(@$banner_content->section_value, 'btn_name')}}
                        </a>
                        <a href="{{getArrayValue(@$banner_content->section_value, 'video_url')}}" data-dimbox="youtube"
                           data-dimbox-ratio="16x9" class="d-flex align-items-center gap-3 banner-action-item banner-btn">  <span class="video-pay"> <i class="fa-solid fa-play"></i> </span>  {{getArrayValue(@$banner_content->section_value, 'video_btn_name')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="banner-right">
                    <div class="banner_img">
                        <img src="{{showImage(filePath()['frontend']['path'].'/'. @getArrayValue(@$banner_content->section_value, 'banner_image'),'1500x2180')}}" alt="">
                        <div class="banner-card banner-right-card">
                            <div class="avatar-group">
                                @foreach($users->take(2) as $user)
                                    <div class="avatar-group-item">
                                        <img src="{{showImage('assets/file/images/user/profile/'.$user->image)}}" alt="{{$user->name}}" class="w-100 h-100"/>
                                    </div>
                                @endforeach

                                <div class="avatar-group-item">
                                   <span>{{count($users)}}+</span>
                                </div>
                            </div>

                            <p>{{translate("Real User Connected.")}}</p>
                        </div>

                        <div class="banner-right-card-two">
                            <img src="https://i.ibb.co/MGmsssB/Screenshot-1.png" alt="Screenshot-1" >
                        </div>

                        <div class="banner-icon sms-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"  x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><linearGradient id="a" x1="2.01" x2="17.99" y1="14" y2="14" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#16b0e2"></stop><stop offset="1" stop-color="#6e5af0"></stop></linearGradient><linearGradient xlink:href="#a" id="b" x1="8.999" x2="11.001" y1="12.984" y2="12.984"></linearGradient><linearGradient xlink:href="#a" id="c" x1="12.496" x2="14.499" y1="12.984" y2="12.984"></linearGradient><linearGradient xlink:href="#a" id="d" x1="5.501" x2="7.503" y1="12.984" y2="12.984"></linearGradient><linearGradient xlink:href="#a" id="e" x1="6.05" x2="21.99" y1="8.754" y2="8.754"></linearGradient><g data-name="1"><path fill="url(#a)40" d="M17.99 10.774v4a6.106 6.106 0 0 1-.04.75c-.23 2.7-1.82 4.04-4.75 4.04h-.4a.805.805 0 0 0-.64.32l-1.2 1.6a1.132 1.132 0 0 1-1.92 0l-1.2-1.6a.923.923 0 0 0-.64-.32h-.4c-3.19 0-4.79-.79-4.79-4.79v-4c0-2.93 1.35-4.52 4.04-4.75a6.076 6.076 0 0 1 .75-.04h6.4q4.785 0 4.79 4.79z" opacity="1" data-original="url(#a)40" class=""></path><path fill="url(#b)" d="M10.004 13.984H10a1 1 0 1 1 .005 0z" opacity="1" data-original="url(#b)"></path><path fill="url(#c)" d="M13.501 13.984h-.005a1 1 0 1 1 .005 0z" opacity="1" data-original="url(#c)" class=""></path><path fill="url(#d)" d="M6.506 13.984h-.005a1 1 0 1 1 .005 0z" opacity="1" data-original="url(#d)"></path><path fill="url(#e)" d="M21.99 6.774v4q0 4.41-4.04 4.75a6.106 6.106 0 0 0 .04-.75v-4q0-4.785-4.79-4.79H6.8a6.076 6.076 0 0 0-.75.04c.23-2.69 1.82-4.04 4.75-4.04h6.4q4.785 0 4.79 4.79z" opacity="1" data-original="url(#e)"></path></g></g></svg>
                        </div>

                        <div class="banner-icon whatsapp-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"  x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#4caf50" d="M256 84.11c-94.93 0-171.89 76.96-171.89 171.89 0 37.95 12.31 73.03 33.14 101.47L95.32 422.3l68.31-21.33C190.31 418 222 427.88 256 427.88c94.93 0 171.89-76.96 171.89-171.89S350.93 84.11 256 84.11z" opacity="1" data-original="#4caf50" class=""></path><path fill="#ffffff" d="M241.69 284.66c-10.44-7.94-35.25-37.57-34.57-43.56s18.33-18.65 18.16-25.73-15.91-52.69-30.21-52.83-40.57 16.16-38.76 48.47 28.68 75.88 69.34 106.74 77.87 42.96 109.1 30.5c27.64-11.03 28.05-39.48 24.23-44.38-3.81-4.9-42.02-23.87-49.01-22.51s-14.3 23.46-25.32 24.96c-11.02 1.48-32.52-13.72-42.96-21.66z" opacity="1" data-original="#ffffff" class=""></path></g></svg>
                        </div>

                        <div class="banner-icon email-icon">
                            <img src="https://i.ibb.co/8XJJRcx/message.png" alt="message">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gradient-bg">
        <img src="{{showImage(filePath()['frontend']['path'].'/'. getArrayValue(@$banner_content->section_value, 'background_image'),'3000x2000')}}" alt="">
    </div>

    <div class="social-media">
        @foreach($social_icons as $icons)
            <a target="_blank" href="{{getArrayValue(@$icons->section_value, 'url')}}" class="social-media-item">
                {!!getArrayValue(@$icons->section_value, 'social_icon')!!}
            </a>
        @endforeach
    </div>
</div>




