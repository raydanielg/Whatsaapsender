<!-- ========Footer Section Start======== -->
<footer class="footer">
    <div class="footer-top ">
        <div class="container">
            <div class="row g-0 justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="footer-item">
                        <div class="footer-info">
                            <a href="#" class="footer-log">
                                <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="">
                            </a>
                            <p>{{getTranslatedArrayValue(@$footer_content->section_value, 'text')}}</p>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-2 mt-5">
                            @foreach($social_icons as $icons)
                                <a target="_blank" href="{{getArrayValue(@$icons->section_value, 'url')}}" class="footer-social">
                                    {!!getArrayValue(@$icons->section_value, 'social_icon')!!}
                                </a>
                            @endforeach
                        </div>

                        <ul class="mt-5 footer-menus">
                            <li><a href="{{route('home')}}" class="footer-menu">{{translate('Home')}}</a></li>
                            <li><a href="{{route('about')}}" class="footer-menu">{{translate('About')}}</a></li>
                            <li><a href="{{route('features')}}" class="footer-menu">{{translate('Features')}}</a></li>
                            <li><a href="{{route('pricing')}}" class="footer-menu">{{translate('Pricing')}}</a></li>
                            <li><a href="{{route('faq')}}" class="footer-menu">{{translate("Faq's")}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="d-flex align-items-center flex-column-reverse flex-md-row justify-content-between text-center gap-4">
                <div class="copyright">
                    <p>&copy;{{getArrayValue(@$footer_content->section_value, 'copyright_text')}}</p>
                </div>

                <ul class="footer-menus">
                    @foreach($pages as $page)
                        <li>
                            <a target="_blank" href="{{route('page',[Str::slug(getArrayValue(@$page->section_value, 'title')),$page->id])}}" class="footer-menu">{{getTranslatedArrayValue(@$page->section_value, 'title')}}</a>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>


    <div class="footer-bg">
        <img src="{{showImage(filePath()['frontend']['path'].'/'. getArrayValue(@$footer_content->section_value, 'background_image'),'19020x1060')}}" alt="">
    </div>
</footer>
