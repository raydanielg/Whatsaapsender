<header class="header">
    <div class="container-fluid fluid-wrapper">
        <div class="header-wrap">
            <a href="{{route('home')}}" class="site-logo">
                <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="">
            </a>

                <div class="nav-menu-wrapper">
                    <div class="d-flex align-items-center justify-content-between w-100 d-lg-none">
                        <div class="mobile-site-logo">
                            <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="">
                        </div>

                        <div class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>

                    <nav class="nav-menu">
                        <ul>
                            <li class="nav-menu-item"><a href="{{route('home')}}">{{translate('Home')}}</a></li>
                            <li class="nav-menu-item"><a href="{{route('about')}}">{{translate('About')}}</a></li>
                            <li class="nav-menu-item"><a href="{{route('features')}}">{{translate('Features')}}</a></li>
                            <li class="nav-menu-item"><a href="{{route('pricing')}}">{{translate('Pricing')}}</a></li>
                            <li class="nav-menu-item"><a href="{{route('faq')}}">{{translate("Faq's")}}</a></li>
                        </ul>
                    </nav>
                    <div class="d-lg-none w-100"> <a href="{{route('login')}}" class="ig-btn btn--primary btn--md btn--capsule btn-nowrap w-100">{{translate('Sign In')}}</a></div>
                </div>

            <div class="d-lg-flex d-none align-items-center gap-3 head-action">
                <a href="{{route('login')}}" class="ig-btn btn--primary btn--md btn--capsule btn-nowrap">{{translate('Sign In')}}</a>
            </div>

            <div class="d-lg-none">
                <span class="bars"><i class="fa-solid fa-bars"></i></span>
            </div>
        </div>
    </div>
</header>

<div class="main-nav-overlay d-lg-none">
</div>
