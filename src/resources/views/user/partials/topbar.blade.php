<header class="header">
    <div class="header-left">
    <div class="header-action d-lg-none">
        <button class="btn-icon" type="button" id="sidebar-handler">
        <i class="ri-menu-2-fill"></i>
        </button>
    </div>
    
    </div>
    <div class="header-right">
        <div class="header-action d-none">
            <button class="btn-icon" id="theme-toggle">
                <i class="dark ri-moon-line"></i>
            </button>
        </div>
        <div class="header-action">
            <div class="header-action d-sm-flex d-none">
                <a href="{{url('/')}}" target="_blank" class="btn-icon">
                    <i class="ri-earth-line"></i>
                </a>
            </div>
        </div>
        <div class="header-action">
            <div class="profile-dropdown">
            <div class="topbar-profile dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="profile-avatar">
                    <img src="{{showImage(filePath()['profile']['user']['path'].'/'.auth()->user()->image, filePath()['profile']['user']['size'])}}" alt="{{ auth()->user()->username }}">
                </span>
                <div class="topbar-profile-info d-sm-block d-none">
                <p>{{ translate("Member") }}</p>
                <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-end">
                <ul>
                <li>
                    <a class="dropdown-item" href="{{ route("user.profile") }}">
                    <i class="ri-user-line"></i> {{ translate("My Account") }} </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('user.communication.api') }}">
                    <i class="ri-code-s-slash-line"></i> {{ translate("API") }} </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{route('logout')}}">
                    <i class="ri-logout-box-line"></i> {{ translate("Logout") }} </a>
                </li>
                </ul>
            </div>
            </div>
        </div>
    </div>
</header>
