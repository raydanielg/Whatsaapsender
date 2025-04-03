@php
    $isMembershipActive = ['user.plan.create', 'user.payment.*', 'user.manual.payment.*'];
    $isCreditLogsActive = ['user.report.credit.*'];
    $isReportsActive = ['user.report.record.*', 'user.report.payment.detail', 'user.plan.subscription'];

    $isContactAttributesActive  = ['user.contact.settings.*'];
    $isContactGroupActive       = ['user.contact.groups.*'];
    $isContactActive            = ['user.contact.*'];
    
    $isSupportTicketActive = [
        'user.support.ticket.index',
        'user.support.ticket.closed',
        'user.support.ticket.running',
        'user.support.ticket.replied',
        'user.support.ticket.answered',
        'user.support.ticket.priority.high',
        'user.support.ticket.priority.medium',
        'user.support.ticket.priority.low',
        'user.support.ticket.details',
    ];
    $isTemplateActive = ['user.template.*'];
    $isSmsActive = ['user.communication.sms.*'];
    $isSmsCampaignActive = ['user.communication.sms.campaign.*'];
    $isWhatsappActive = ['user.communication.whatsapp.*'];
    $isWhatsappCampaignActive = ['user.communication.whatsapp.campaign.*'];
    $isEmailActive = ['user.communication.email.*'];
    $isEmailCampaignActive = ['user.communication.email.campaign.*'];
    $plan_access = (object) planAccess(auth()->user());
@endphp

<aside class="sidebar">
    <div class="sidebar-wrapper">
        <div class="sidebar-logo">
            <a href="{{ route('user.dashboard') }}" class="logo">
                <img src="{{ showImage(config('setting.file_path.panel_logo.path') . '/' . site_settings('panel_logo'), config('setting.file_path.panel_logo.size')) }}"
                    class="logo-lg" alt="">
                <img src="{{ showImage(config('setting.file_path.panel_square_logo.path') . '/' . site_settings('panel_square_logo'), config('setting.file_path.panel_square_logo.size')) }}"
                    class="logo-sm" alt="">
            </a>
            <button class="icon-btn btn-sm dark-soft hover circle d-lg-none" id="sideBar-closer">
                <i class="ri-arrow-left-line"></i>
            </button>
        </div>
        <div class="menu-wrapper">
            <ul class="menus">
                <li class="menu">
                    <a class="menu-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                        href="{{ route('user.dashboard') }}">
                        <span class="menu-symbol">
                            <i class="ri-layout-grid-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="menu">
                    <a class="menu-link {{ menuActive($isMembershipActive) }}" href="{{ route('user.plan.create') }}">
                        <span class="menu-symbol">
                            <i class="ri-news-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Plans') }}</span>
                    </a>
                </li>
                <li class="menu">
                    <a class="menu-link {{ menuActive($isContactActive) }}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-contacts-book-3-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Contacts') }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{ menuShow($isContactActive) }}"
                        {{ menuShow($isContactActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate('All Contacts') }}</h6>
                            </div>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link  {{ request()->routeis('user.contact.settings.index') ? 'active' : '' }}"
                                        href="{{ route('user.contact.settings.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ri-user-settings-line"></i>
                                        </span>
                                        <p>{{ translate('Attributes') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeis('user.contact.group.index') ? 'active' : '' }}"
                                        href="{{ route('user.contact.group.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ri-group-line"></i>
                                        </span>
                                        <p>{{ translate('Groups') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeis('user.contact.index') ? 'active' : '' }}"
                                        href="{{ route('user.contact.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ri-list-indefinite"></i>
                                        </span>
                                        <p>{{ translate('List') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @php
                    $route =
                        @$plan_access->type == \App\Enums\StatusEnum::TRUE->status()
                            ? route('user.gateway.whatsapp.cloud.api')
                            : route('user.gateway.email.index');

                    if (
                        @$plan_access->type == \App\Enums\StatusEnum::FALSE->status() &&
                        isset($plan_access) &&
                        !empty($plan_access)
                    ) {
                        $emailIsAllowed = $plan_access->email['is_allowed'] ?? false;
                        $smsIsAllowed = $plan_access->sms['is_allowed'] ?? false;
                        $androidIsAllowed = $plan_access->android['is_allowed'] ?? false;
                        if (!$emailIsAllowed) {
                            if ($smsIsAllowed) {
                                $route = route('user.gateway.sms.api.index');
                            } elseif ($androidIsAllowed) {
                                $route = route('user.gateway.sms.android.index');
                            } else {
                                $route = route('user.gateway.whatsapp.cloud.api');
                            }
                        }
                    }
                @endphp

                <li class="menu">
                    <a class="menu-link {{ menuActive(['user.gateway.email.index']) }}" href="{{ $route }}">
                        <span class="menu-symbol">
                            <i class="ri-instance-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Gateway') }}</span>
                    </a>
                </li>
                <li class="menu">
                    <a class="menu-link {{ menuActive(array_merge($isSmsActive, $isSmsCampaignActive, $isWhatsappCampaignActive, $isEmailActive, $isWhatsappActive)) }}"
                        href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-mail-send-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Messages') }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{ menuShow(array_merge($isSmsActive, $isSmsCampaignActive, $isWhatsappCampaignActive, $isEmailActive, $isWhatsappActive)) }}"
                        {{ menuShow(array_merge($isSmsActive, $isWhatsappCampaignActive, $isSmsCampaignActive, $isEmailActive, $isWhatsappActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate('Communication options') }}</h6>
                            </div>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ menuActive($isEmailActive) == 'active' ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse" href="#communicationEmail" role="button"
                                        aria-expanded="false" aria-controls="communicationEmail">
                                        <span>
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <p> {{ translate('Email') }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                    </a>
                                    <div class="side-menu-dropdown collapse {{ menuShow(array_merge(['user.communication.email.create', 'user.communication.email.index'], $isEmailCampaignActive)) }}"
                                        id="communicationEmail">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.email.create') ? 'active' : '' }}"
                                                    href="{{ route('user.communication.email.create') }}">
                                                    <p>{{ translate('Send Email') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.email.index')  ? 'active' :''}}"
                                                    href="{{ route('user.communication.email.index') }}">
                                                    <p>{{ translate('History') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive($isEmailCampaignActive) == 'active' ? 'collapsed' : '' }}"
                                                    data-bs-toggle="collapse" href="#whatsappCampaign" role="button"
                                                    aria-expanded="false" aria-controls="whatsappCampaign">

                                                    <p> {{ translate('Campaign') }}
                                                        <small>
                                                            <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                    </p>
                                                </a>
                                                <div class="side-menu-dropdown collapse {{ menuShow($isEmailCampaignActive) }}"
                                                    id="whatsappCampaign" style="">
                                                    <ul class="sub-menu">
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.email.campaign.create') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.email.campaign.create') }}">
                                                                <p>{{ translate('Create') }}</p>
                                                            </a>
                                                        </li>
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.email.campaign.index') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.email.campaign.index') }}">
                                                                <p>{{ translate('List') }}</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ menuActive(array_merge($isSmsActive, $isSmsCampaignActive)) == 'active' ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse" href="#smsCommunication" role="button"
                                        aria-expanded="false" aria-controls="smsCommunication">
                                        <span>
                                            <i class="ri-message-2-line"></i>
                                        </span>
                                        <p> {{ translate('SMS') }} <small> <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                    </a>
                                    <div class="side-menu-dropdown collapse {{ menuShow(array_merge(['user.communication.sms.create', 'user.communication.sms.index'], $isSmsCampaignActive)) }}"
                                        id="smsCommunication">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.sms.create') ? 'active' : '' }}"
                                                    href="{{ route('user.communication.sms.create') }}">
                                                    <p>{{ translate('Send SMS') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.sms.index') ? 'active' : '' }}"
                                                    href="{{ route('user.communication.sms.index') }}">
                                                    <p>{{ translate('History') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive($isSmsCampaignActive) == 'active' ? 'collapsed' : '' }}"
                                                    data-bs-toggle="collapse" href="#smsCampaign" role="button"
                                                    aria-expanded="false" aria-controls="smsCampaign">

                                                    <p> {{ translate('Campaign') }}
                                                        <small>
                                                            <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                    </p>
                                                </a>
                                                <div class="side-menu-dropdown collapse {{ menuShow($isSmsCampaignActive) }}"
                                                    id="smsCampaign" style="">
                                                    <ul class="sub-menu">
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.sms.campaign.create') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.sms.campaign.create') }}">
                                                                <p>{{ translate('Create') }}</p>
                                                            </a>
                                                        </li>
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.sms.campaign.index') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.sms.campaign.index') }}">
                                                                <p>{{ translate('List') }}</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ menuActive($isWhatsappActive) == 'active' ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse" href="#whatsappCommunication" role="button"
                                        aria-expanded="false" aria-controls="whatsappCommunication">
                                        <span>
                                            <i class="ri-whatsapp-line"></i>
                                        </span>
                                        <p> {{ translate('Whatsapp') }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                    </a>
                                    <div class="side-menu-dropdown collapse {{ menuShow(array_merge(['user.communication.whatsapp.create', 'user.communication.whatsapp.index'], $isWhatsappCampaignActive)) }}"
                                        id="whatsappCommunication">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.whatsapp.create') ? 'active' : '' }}"
                                                    href="{{ route('user.communication.whatsapp.create') }}">
                                                    <p>{{ translate('Send Message') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeIs('user.communication.whatsapp.index') ? 'active' : '' }}"
                                                    href="{{ route('user.communication.whatsapp.index') }}">
                                                    <p>{{ translate('History') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive($isWhatsappCampaignActive) == 'active' ? 'collapsed' : '' }}"
                                                    data-bs-toggle="collapse" href="#whatsappCampaign" role="button"
                                                    aria-expanded="false" aria-controls="whatsappCampaign">

                                                    <p> {{ translate('Campaign') }}
                                                        <small>
                                                            <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                    </p>
                                                </a>
                                                <div class="side-menu-dropdown collapse {{ menuShow($isWhatsappCampaignActive) }}"
                                                    id="whatsappCampaign" style="">
                                                    <ul class="sub-menu">
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.whatsapp.campaign.create') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.whatsapp.campaign.create') }}">
                                                                <p>{{ translate('Create') }}</p>
                                                            </a>
                                                        </li>
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{ request()->routeIs('user.communication.whatsapp.campaign.index') ? 'active' : '' }}"
                                                                href="{{ route('user.communication.whatsapp.campaign.index') }}">
                                                                <p>{{ translate('List') }}</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                {{-- <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{request()->routeIs('user.communication.api') ? 'active' : ''}}" href="{{ route('user.communication.api') }}" aria-expanded="false">
                                    <span>
                                        <i class="ri-code-s-slash-line"></i>
                                    </span>
                                    <p>{{ translate("API") }}</p>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu">
                    <a class="menu-link {{ menuActive($isTemplateActive) }}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-stack-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Templates') }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{ menuShow($isTemplateActive) }}"
                        {{ menuShow($isTemplateActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate('All Templates') }}</h6>
                            </div>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeis('user.template.email') ? 'active' : '' }}"
                                        href="{{ route('user.template.email') }}">
                                        <span>
                                            <i class="ri-mail-send-fill"></i>
                                        </span>
                                        <p>{{ translate('Email') }}</p>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeis('user.template.sms') ? 'active' : '' }}"
                                        href="{{ route('user.template.sms') }}">
                                        <span>
                                            <i class="ri-discuss-line"></i>
                                        </span>
                                        <p>{{ translate('SMS') }}</p>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeis('user.template.whatsapp.index') ? 'active' : '' }}"
                                        href="{{ route('user.template.whatsapp.index') }}">
                                        <span>
                                            <i class="ri-whatsapp-line"></i>
                                        </span>
                                        <p>{{ translate('WhastaApp') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu">
                    <a class="menu-link {{ menuActive(array_merge($isCreditLogsActive, $isReportsActive)) }}"
                        href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-bar-chart-2-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Report') }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{ menuShow(array_merge($isCreditLogsActive, $isReportsActive)) }}"
                        {{ menuShow(array_merge($isCreditLogsActive, $isReportsActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate('Report Logs') }}</h6>
                            </div>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ menuShow($isReportsActive) != 'show' ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse" href="#activityRecords" role="button"
                                        aria-expanded="false" aria-controls="activityRecords">
                                        <span>
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <p> {{ translate('Activity Records') }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                    </a>
                                    <div class="side-menu-dropdown collapse {{ menuShow($isReportsActive) }}"
                                        id="activityRecords">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive(['user.plan.subscription']) }}"
                                                    href="{{ route('user.plan.subscription') }}">

                                                    <p>{{ translate('Subscription Logs') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('user.report.record.transaction') ? 'active' : '' }}"
                                                    href="{{ route('user.report.record.transaction') }}">
                                                    <p>{{ translate('Transaction History') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('user.report.record.payment') ? 'active' : '' }}"
                                                    href="{{ route('user.report.record.payment') }}">
                                                    <p>{{ translate('Payment History') }}</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link collapsed {{ menuShow($isCreditLogsActive) }}"
                                        data-bs-toggle="collapse" href="#creditLogs" role="button"
                                        aria-expanded="false" aria-controls="creditLogs">
                                        <span>
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <p> {{ translate('Credit Logs') }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                    </a>
                                    <div class="side-menu-dropdown collapse {{ menuShow($isCreditLogsActive) }}"
                                        id="creditLogs">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('user.report.credit.sms') ? 'active' : '' }}"
                                                    href="{{ route('user.report.credit.sms') }}">
                                                    <p>{{ translate('SMS') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('user.report.credit.email') ? 'active' : '' }}"
                                                    href="{{ route('user.report.credit.email') }}">
                                                    <p>{{ translate('Email') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('user.report.credit.whatsapp') ? 'active' : '' }}"
                                                    href="{{ route('user.report.credit.whatsapp') }}">
                                                    <p>{{ translate('WhatsApp') }}</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="menus">
                <li class="menu">
                    <a class="menu-link {{ request()->routeIs('user.support.*') ? 'active' : '' }}"
                        href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-question-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Support') }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{ menuShow(array_merge($isSupportTicketActive, ['user.support.ticket.create'])) }}"
                        {{ menuShow(array_merge($isSupportTicketActive, ['user.support.ticket.create'])) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate('Support Ticket') }}</h6>
                            </div>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeIs('user.support.ticket.create') ? 'active' : '' }}"
                                        href="{{ route('user.support.ticket.create') }}">
                                        <span>
                                            <i class="ri-coupon-2-line"></i>
                                        </span>
                                        <p>{{ translate('Create ticket') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ menuShow($isSupportTicketActive) != 'show' ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse" href="#currency" role="button"
                                        aria-expanded="false" aria-controls="currencyList">
                                        <span>
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                        <p> {{ translate('All Tickets') }} <small> <i
                                                    class="ri-arrow-down-s-line"></i> </small>
                                        </p>
                                    </a>

                                    <div class="side-menu-dropdown collapse {{ menuShow($isSupportTicketActive) }}"
                                        id="currency">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive(['user.support.ticket.index']) }}"
                                                    href="{{ route('user.support.ticket.index') }}">
                                                    <p>{{ translate('Ticket List') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive(['user.support.ticket.priority.high']) }}"
                                                    href="{{ route('user.support.ticket.priority.high') }}">
                                                    <p>{{ translate('High Priority') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive(['user.support.ticket.priority.medium']) }}"
                                                    href="{{ route('user.support.ticket.priority.medium') }}">
                                                    <p>{{ translate('Medium Priority') }}</p>
                                                </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ menuActive(['user.support.ticket.priority.low']) }}"
                                                    href="{{ route('user.support.ticket.priority.low') }}">
                                                    <p>{{ translate('Low Priority') }}</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeIs('user.support.ticket.running') ? 'active' : '' }}"
                                        href="{{ route('user.support.ticket.running') }}">
                                        <span>
                                            <i class="ri-hourglass-line"></i>
                                        </span>
                                        <p>{{ translate('Running tickets') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeIs('user.support.ticket.answered') ? 'active' : '' }}"
                                        href="{{ route('user.support.ticket.answered') }}">
                                        <span>
                                            <i class="ri-question-answer-line"></i>
                                        </span>
                                        <p>{{ translate('Answered tickets') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeIs('user.support.ticket.replied') ? 'active' : '' }}"
                                        href="{{ route('user.support.ticket.replied') }}">
                                        <span>
                                            <i class="ri-reply-line"></i>
                                        </span>
                                        <p>{{ translate('Replied tickets') }}</p>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-link {{ request()->routeIs('user.support.ticket.closed') ? 'active' : '' }}"
                                        href="{{ route('user.support.ticket.closed') }}">
                                        <span>
                                            <i class="ri-door-closed-line"></i>
                                        </span>
                                        <p>{{ translate('Closed tickets') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</aside>
