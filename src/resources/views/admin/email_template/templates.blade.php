<div class="col-lg-auto">
    <div class="vertical-tab card sticky-item">
        <div class="nav flex-column nav-pills gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link {{ request()->routeIs('admin.template.email.list.own') ? 'active' : ''}}" href="{{ route('admin.template.email.list.own') }}">{{ translate('Admin')}}
                <span><i class="las la-angle-right"></i></span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.template.email.list.user') ? 'active' : ''}}" href="{{ route('admin.template.email.list.user') }}">{{ translate('User')}}
                <span><i class="las la-angle-right"></i></span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.template.email.list.default') ? 'active' : ''}}" href="{{ route('admin.template.email.list.default') }}">{{ translate('Default')}}
                <span><i class="las la-angle-right"></i></span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.template.email.list.global') ? 'active' : ''}}" href="{{ route('admin.template.email.list.global') }}">{{ translate('Global Template')}}
                <span><i class="las la-angle-right"></i></span>
            </a>
        </div>
    </div>
</div>