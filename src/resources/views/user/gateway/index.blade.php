@extends('user.layouts.app')
@section('panel')
    <main class="main-body">
        <div class="container-fluid px-0 main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <h2>{{ $title }}</h2>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.dashboard') }}">{{ translate('Dashboard') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            @php
                $type = array_key_exists('type', planAccess($user)) ? planAccess($user)['type'] : \App\Enums\StatusEnum::FALSE->status();
                $admin_gateway_access = false;
                if($type == \App\Enums\StatusEnum::TRUE->status()) {
                    $admin_gateway_access = true;
                }
            @endphp
            <div class="pill-tab mb-4">
                <ul class="nav" role="tablist">
                    @if(!$admin_gateway_access)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->routeis('user.gateway.email.index') ? 'active' : '' }}"
                                href="{{ route('user.gateway.email.index') }}" role="tab"
                                {{ request()->routeis('user.gateway.email.index') ? "aria-selected='true'" : "aria-selected='false' tabindex='-1'" }}>
                                <i class="ri-mail-line"></i> {{ translate('Email Gateways') }} </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->routeis('user.gateway.sms.api.index') ? 'active' : '' }}"
                                href="{{ route('user.gateway.sms.api.index') }}" role="tab"
                                {{ request()->routeis('user.gateway.sms.api.index') ? "aria-selected='true'" : "aria-selected='false' tabindex='-1'" }}>
                                <i class="ri-message-2-line"></i> {{ translate('SMS API Gateway') }} </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request()->routeis('user.gateway.sms.android.index') ? 'active' : '' }}"
                                href="{{ route('user.gateway.sms.android.index') }}" role="tab"
                                {{ request()->routeis('user.gateway.sms.android.index') ? "aria-selected='true'" : "aria-selected='false' tabindex='-1'" }}>
                                <i class="ri-android-line"></i> {{ translate('Android Gateway') }} </a>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->routeis('user.gateway.whatsapp.cloud.api') ? 'active' : '' }}"
                            href="{{ route('user.gateway.whatsapp.cloud.api') }}" role="tab"
                            {{ request()->routeis('user.gateway.whatsapp.cloud.api') ? "aria-selected='true'" : "aria-selected='false' tabindex='-1'" }}>
                            <i class="ri-whatsapp-line"></i> {{ translate('Whatsapp Cloud API') }} </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->routeis('user.gateway.whatsapp.device') ? 'active' : '' }}"
                            href="{{ route('user.gateway.whatsapp.device') }}" role="tab"
                            {{ request()->routeis('user.gateway.whatsapp.device') ? "aria-selected='true'" : "aria-selected='false' tabindex='-1'" }}>
                            <i class="ri-whatsapp-line"></i> {{ translate('Whatsapp Node Device') }} </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                @yield('tab-content')
            </div>
        </div>
    </main>
@endsection
@section('modal')
    @yield('modal')
@endsection
