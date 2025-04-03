<header class="header">
    <div class="header-left">
    <div class="header-action d-lg-none">
        <button class="btn-icon" type="button" id="sidebar-handler">
        <i class="ri-menu-2-fill"></i>
        </button>
    </div>
    <div class="header-action d-sm-flex d-none">
        <a href="{{route('admin.system.cache.clear')}}" class="btn-icon">
        <i class="ri-refresh-line"></i>
        </a>
    </div>
    <div class="header-action d-sm-flex d-none">
        <a href="{{url('/')}}" target="_blank" class="btn-icon">
        <i class="ri-earth-line"></i>
        </a>
    </div>
    </div>
    <div class="header-right">
        <div class="header-action">
            <div class="header-action">
                <form class="settingsForm themeForm" method="post">
                    @csrf
                    <input type="hidden" name="site_settings[theme_mode]" id="theme_mode" value="{{ site_settings('theme_mode') == \App\Enums\StatusEnum::FALSE->status() ? \App\Enums\StatusEnum::TRUE->status() : \App\Enums\StatusEnum::FALSE->status() }}">
                    <button class="btn-icon theme-toggler" type="button">
                        @if(site_settings('theme_mode') == \App\Enums\StatusEnum::FALSE->status())
                            <i class="ri-sun-line"></i>
                        @else
                            <i class="ri-moon-line"></i>
                        @endif
                    </button>
                </form>
            </div>
        </div>
        <div class="header-action">
            <div class="lang-dropdown">
                <div class="btn-icon dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="flag-img">
                        
                        <img class="lang-image" src="{{ asset('assets/theme/global/images/flags/' . App::getLocale() . '.svg') }}" alt="{{ App::getLocale() }}" />
                    </span>
                </div>
                <div class="dropdown-menu dropdown-menu-end">
                    <ul>
                        @foreach($languages as $language)
                            <li>
                                <a class="pointer" onclick="changeLang('{{$language->id}}', '{{ $language->code }}')">
                                    <i class="flag-icon-{{$language->code}} flag-icon flag-icon-squared rounded-circle"></i>
                                    {{$language->name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="header-action">
            <div class="profile-dropdown">
            <div class="topbar-profile dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="profile-avatar">
                    <img src="{{showImage(config('setting.file_path.admin_profile.path').'/'.auth()->guard('admin')->user()->image, config('setting.file_path.admin_profile.size'))}}" alt="{{ auth()->guard('admin')->user()->username }}">
                </span>
                <div class="topbar-profile-info d-sm-block d-none">
                <p>{{ ucfirst(auth()->guard('admin')->user()->username) }}</p>
                <span>{{ auth()->guard('admin')->user()->name }}</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-end">
                <ul>
                <li>
                    <a class="dropdown-item" href="{{ route("admin.profile") }}">
                    <i class="ri-user-line"></i> {{ translate("My Account") }} </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.communication.api') }}">
                    <i class="ri-code-s-slash-line"></i> {{ translate("API") }} </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{route('admin.logout')}}">
                    <i class="ri-logout-box-line"></i> {{ translate("Logout") }} </a>
                </li>
                </ul>
            </div>
            </div>
        </div>
    </div>
</header>
