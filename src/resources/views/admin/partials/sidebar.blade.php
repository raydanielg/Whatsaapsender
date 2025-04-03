@php
    $isMembershipPlanActive     = ['admin.membership.plan.*'];
    $isMemberActive             = ['admin.user.*'];
    $isSettingActive            = ['admin.system.*'];
    $isCurrencyActive           = ['admin.system.currency.*'];
    $isLanguageActive           = ['admin.system.language.*'];
    $isSupportTicketActive      = ['admin.support.ticket.*'];
    $isFrontendCustomizeActive  = ['admin.frontend.sections.*'];
    $isCreditLogsActive         = ['admin.report.credit.*'];
    $isReportsActive            = ['admin.report.record.*',
                                  'admin.report.payment.detail'];
    $isPaymentActive            = ['admin.payment.*'];
    $isDisapatchGatewayActive   = ['admin.gateway.*'];
    $isTemplateActive           = ['admin.template.*'];

    $isContactAttributesActive  = ['admin.contact.settings.*'];
    $isContactGroupActive       = ['admin.contact.groups.*'];
    $isContactActive            = ['admin.contact.*'];

    $isSmsActive                = ['admin.communication.sms.*'];
    $isSmsCampaignActive        = ['admin.communication.sms.campaign.*'];
    $isWhatsappActive           = ['admin.communication.whatsapp.*'];
    $isWhatsappCampaignActive   = ['admin.communication.whatsapp.campaign.*'];
    $isEmailActive              = ['admin.communication.email.*'];
    $isEmailCampaignActive      = ['admin.communication.email.campaign.*'];
    $isBlogActive               = ['admin.blog.api'];
@endphp

<aside class="sidebar">
    <div class="sidebar-wrapper">
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{showImage(config('setting.file_path.panel_logo.path').'/'.site_settings('panel_logo'),config('setting.file_path.panel_logo.size'))}}" class="logo-lg" alt="">
                <img src="{{showImage(config('setting.file_path.panel_square_logo.path').'/'.site_settings('panel_square_logo'),config('setting.file_path.panel_square_logo.size'))}}" class="logo-sm" alt="">
            </a>
            <button class="icon-btn btn-sm dark-soft hover circle d-lg-none" id="sideBar-closer">
                <i class="ri-arrow-left-line"></i>
            </button>
        </div>
        <div class="menu-wrapper">
            <ul class="menus scrollable-menu">

                <li class="menu">
                    <a class="menu-link {{request()->routeIs('admin.dashboard') ? 'active' :''}}" href="{{ route("admin.dashboard") }}">
                        <span class="menu-symbol">
                            <i class="ri-layout-grid-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("dashboard") }}</span>
                    </a>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive($isMemberActive)}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-account-box-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Members") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow(array_merge($isMemberActive, $isMembershipPlanActive))}}" {{ menuShow(array_merge($isMemberActive, $isMembershipPlanActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{translate("Member Management")}}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isMemberActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#member" role="button" aria-expanded="false" aria-controls="memberList">
                                            <span>
                                                <i class="ri-team-line"></i>
                                            </span>
                                            <p> {{ translate("Member List") }} <small> <i class="ri-arrow-down-s-line"></i> </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow($isMemberActive)}}" id="member">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.user.index') ? 'active' : '' }}" href="{{route('admin.user.index')}}">
                                                        <p>{{ translate("All Members") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.user.active') ? 'active' : '' }}" href="{{route('admin.user.active')}}">
                                                        <p>{{ translate("Active Members") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.user.banned') ? 'active' : '' }}" href="{{route('admin.user.banned')}}">
                                                        <p>{{ translate("Banned Members") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isMembershipPlanActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#membershipPlan" role="button" aria-expanded="false" aria-controls="membershipPlan">
                                            <span>
                                                <i class="ri-building-3-line"></i>
                                            </span>
                                            <p> {{ translate("Membership Plans") }} <small> <i class="ri-arrow-down-s-line"></i> </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow($isMembershipPlanActive)}}" id="membershipPlan">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.membership.plan.create') ? 'active' : '' }}" href="{{route('admin.membership.plan.create')}}">
                                                        <p>{{ translate("Create Plan") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.membership.plan.index') ? 'active' : '' }}" href="{{route('admin.membership.plan.index')}}">
                                                        <p>{{ translate("Plan List") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-note">
                                        <p class="menu-note">
                                            {{ translate("Access Member specific") }}
                                            <br>
                                            <a href="{{ route("admin.system.setting", ["type" => "member"]) }}">{{ translate("Settings") }}</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive(array_merge($isSmsActive, $isSmsCampaignActive, $isWhatsappCampaignActive, $isEmailActive, $isWhatsappActive))}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-mail-send-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Messages") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow(array_merge($isSmsActive, $isSmsCampaignActive, $isWhatsappCampaignActive, $isEmailActive, $isWhatsappActive))}}" {{ menuShow(array_merge($isSmsActive, $isWhatsappCampaignActive, $isSmsCampaignActive, $isEmailActive, $isWhatsappActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("Communication") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuActive($isEmailActive) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#communicationEmail" role="button" aria-expanded="false" aria-controls="communicationEmail">
                                        <span>
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <p> {{ translate("Email") }} <small>
                                            <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow(array_merge(['admin.communication.email.create', 'admin.communication.email.index'], $isEmailCampaignActive))}}" id="communicationEmail">
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item">
                                            <a class="sidebar-menu-link {{request()->routeIs('admin.communication.email.create')  ? 'active' :''}}" href="{{ route('admin.communication.email.create') }}">
                                                <p>{{ translate("Send Email") }}</p>
                                            </a>
                                            </li>
                                            <li class="sub-menu-item">
                                            <a class="sidebar-menu-link {{request()->routeIs('admin.communication.email.index')}}" href="{{ route('admin.communication.email.index') }}">
                                                <p>{{ translate("History") }}</p>
                                            </a>
                                            </li>
                                            <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{menuActive($isEmailCampaignActive) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#whatsappCampaign" role="button" aria-expanded="false" aria-controls="whatsappCampaign">

                                                <p> {{ translate("Campaign") }}
                                                    <small>
                                                    <i class="ri-arrow-down-s-line"></i>
                                                    </small>
                                                </p>
                                                </a>
                                                <div class="side-menu-dropdown collapse {{menuShow($isEmailCampaignActive)}}" id="whatsappCampaign" style="">
                                                <ul class="sub-menu">
                                                    <li class="sub-menu-item">
                                                        <a class="sidebar-menu-link {{request()->routeIs('admin.communication.email.campaign.create') ? 'active' : ''}}" href="{{ route('admin.communication.email.campaign.create') }}">
                                                        <p>{{ translate("Create") }}</p>
                                                        </a>
                                                    </li>
                                                    <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{request()->routeIs('admin.communication.email.campaign.index') ? 'active' : ''}}" href="{{ route('admin.communication.email.campaign.index') }}">
                                                        <p>{{ translate("List") }}</p>
                                                    </a>
                                                    </li>
                                                </ul>
                                                </div>
                                            </li>
                                        </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuActive(array_merge($isSmsActive, $isSmsCampaignActive)) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#smsCommunication" role="button" aria-expanded="false" aria-controls="smsCommunication">
                                            <span>
                                                <i class="ri-message-2-line"></i>
                                            </span>
                                            <p> {{ translate("SMS") }} <small> <i class="ri-arrow-down-s-line"></i> </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow(array_merge(['admin.communication.sms.create', 'admin.communication.sms.index'], $isSmsCampaignActive))}}" id="smsCommunication">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{request()->routeIs('admin.communication.sms.create') ? 'active' :''}}" href="{{ route('admin.communication.sms.create') }}">
                                                        <p>{{ translate("Send SMS") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{request()->routeIs('admin.communication.sms.index') ? 'active' : ''}}" href="{{ route('admin.communication.sms.index') }}">
                                                        <p>{{ translate("History") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{menuActive($isSmsCampaignActive) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#smsCampaign" role="button" aria-expanded="false" aria-controls="smsCampaign">

                                                      <p> {{ translate("Campaign") }}
                                                        <small>
                                                          <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                      </p>
                                                    </a>
                                                    <div class="side-menu-dropdown collapse {{menuShow($isSmsCampaignActive)}}" id="smsCampaign" style="">
                                                      <ul class="sub-menu">
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{request()->routeIs('admin.communication.sms.campaign.create') ? 'active' : ''}}" href="{{ route('admin.communication.sms.campaign.create') }}">
                                                              <p>{{ translate("Create") }}</p>
                                                            </a>
                                                          </li>
                                                        <li class="sub-menu-item">
                                                          <a class="sidebar-menu-link {{request()->routeIs('admin.communication.sms.campaign.index') ? 'active' : ''}}" href="{{ route('admin.communication.sms.campaign.index') }}">
                                                            <p>{{ translate("List") }}</p>
                                                          </a>
                                                        </li>
                                                      </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuActive($isWhatsappActive) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#whatsappCommunication" role="button" aria-expanded="false" aria-controls="whatsappCommunication">
                                        <span>
                                            <i class="ri-whatsapp-line"></i>
                                        </span>
                                        <p> {{ translate("Whatsapp") }} <small>
                                            <i class="ri-arrow-down-s-line"></i>
                                            </small>
                                        </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow(array_merge(['admin.communication.whatsapp.create', 'admin.communication.whatsapp.index'], $isWhatsappCampaignActive))}}" id="whatsappCommunication">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{request()->routeIs('admin.communication.whatsapp.create') ? 'active' :''}}" href="{{ route('admin.communication.whatsapp.create') }}">
                                                    <p>{{ translate("Send Message") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{request()->routeIs('admin.communication.whatsapp.index') ? 'active' :''}}" href="{{ route('admin.communication.whatsapp.index') }}">
                                                    <p>{{ translate("History") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{menuActive($isWhatsappCampaignActive) == 'active' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#whatsappCampaign" role="button" aria-expanded="false" aria-controls="whatsappCampaign">

                                                    <p> {{ translate("Campaign") }}
                                                        <small>
                                                        <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                    </p>
                                                    </a>
                                                    <div class="side-menu-dropdown collapse {{menuShow($isWhatsappCampaignActive)}}" id="whatsappCampaign" style="">
                                                    <ul class="sub-menu">
                                                        <li class="sub-menu-item">
                                                            <a class="sidebar-menu-link {{request()->routeIs('admin.communication.whatsapp.campaign.create') ? 'active' : ''}}" href="{{ route('admin.communication.whatsapp.campaign.create') }}">
                                                            <p>{{ translate("Create") }}</p>
                                                            </a>
                                                        </li>
                                                        <li class="sub-menu-item">
                                                        <a class="sidebar-menu-link {{request()->routeIs('admin.communication.whatsapp.campaign.index') ? 'active' : ''}}" href="{{ route('admin.communication.whatsapp.campaign.index') }}">
                                                            <p>{{ translate("List") }}</p>
                                                        </a>
                                                        </li>
                                                    </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    {{-- <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{request()->routeIs('admin.communication.api') ? 'active' : ''}}" href="{{ route('admin.communication.api') }}" aria-expanded="false">
                                        <span>
                                            <i class="ri-code-s-slash-line"></i>
                                        </span>
                                        <p>{{ translate("API") }}</p>
                                        </a>
                                    </li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive(array_merge($isPaymentActive, $isDisapatchGatewayActive))}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-instance-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Gateway") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow(array_merge($isPaymentActive, $isDisapatchGatewayActive))}}" {{ menuShow(array_merge($isPaymentActive, $isDisapatchGatewayActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("Gateway Options") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isPaymentActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#automaticPayment" role="button" aria-expanded="false" aria-controls="automaticPayment">
                                            <span>
                                                <i class="ri-bank-card-line"></i>
                                            </span>
                                            <p> {{ translate("Payment Gateway") }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                                </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow($isPaymentActive)}}" id="automaticPayment">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.payment.automatic.index') ? 'active' : '' }}" href="{{route('admin.payment.automatic.index')}}" >
                                                        <p>{{ translate("Automatic Payment") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="side-menu-dropdown collapse {{menuShow($isPaymentActive)}}" id="automaticPayment">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ request()->routeis('admin.payment.manual.index') ? 'active' : '' }}" href="{{route('admin.payment.manual.index')}}" >
                                                        <p>{{ translate("Manual Payment") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ menuActive($isDisapatchGatewayActive) }}" href="{{ route("admin.gateway.email.index") }}">
                                            <span>
                                                <i class="ri-tools-line"></i>
                                            </span>
                                            <p>{{ translate("Messaging Gateways") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive($isContactActive)}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-contacts-book-3-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Contacts") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow($isContactActive)}}" {{ menuShow($isContactActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("All Contacts") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link  {{ request()->routeis('admin.contact.settings.index') ? 'active' : '' }}" href="{{ route('admin.contact.settings.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ri-user-settings-line"></i>
                                        </span>
                                        <p>{{ translate("Attributes") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->routeis('admin.contact.group.index') ? 'active' : '' }}" href="{{ route('admin.contact.group.index') }}" aria-expanded="false">
                                            <span>
                                                <i class="ri-group-line"></i>
                                            </span>
                                            <p>{{ translate("Groups") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->routeis('admin.contact.index') ? 'active' : '' }}" href="{{ route('admin.contact.index') }}" aria-expanded="false">
                                            <span>
                                                <i class="ri-list-indefinite"></i>
                                            </span>
                                            <p>{{ translate("List") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive($isTemplateActive)}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-stack-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Templates") }} @if($sms_template_request > 0 || $email_template_request > 0) <span class="text-danger fs-16">*</span> @endif</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow($isTemplateActive)}}" {{ menuShow($isTemplateActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("All Templates") }}</h6>
                                
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->routeis('admin.template.email') ? 'active' : '' }}" href="{{ route('admin.template.email') }}">
                                            <span>
                                                <i class="ri-mail-send-fill"></i>
                                            </span>
                                            <p>
                                                {{ translate("Email") }}
                                                @if($email_template_request > 0)
                                                    <span class="fs-12 i-badge pill danger-soft">
                                                        {{ $email_template_request }}
                                                    </span>
                                                @endif
                                            </p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->routeis('admin.template.sms') ? 'active' : '' }}" href="{{ route('admin.template.sms') }}">
                                            <span>
                                                <i class="ri-discuss-line"></i>
                                            </span>
                                            <p>
                                                {{ translate("SMS") }}
                                                @if($sms_template_request > 0)
                                                    <span class="fs-12 i-badge pill danger-soft">
                                                        {{ $sms_template_request }}
                                                    </span>
                                                @endif
                                            </p>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->routeis('admin.template.whatsapp.index') ? 'active' : '' }}" href="{{ route('admin.template.whatsapp.index') }}">
                                            <span>
                                                <i class="ri-whatsapp-line"></i>
                                            </span>
                                            <p>{{ translate("WhastaApp") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive(array_merge($isCreditLogsActive, $isReportsActive))}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-bar-chart-2-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Report") }} @if($pending_manual_payment_count > 0) <span class="text-danger fs-16">*</span> @endif</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow(array_merge($isCreditLogsActive, $isReportsActive))}}" {{ menuShow(array_merge($isCreditLogsActive, $isReportsActive)) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("Report Logs") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isReportsActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#activityRecords" role="button" aria-expanded="false" aria-controls="activityRecords">
                                            <span>
                                                <i class="ri-file-chart-line"></i>
                                            </span>
                                            <p> {{ translate("Activity Records") }} 
                                                @if($pending_manual_payment_count > 0)
                                                    <span class="text-danger">
                                                        <i class="ri-error-warning-line"></i>
                                                    </span>
                                                @endif
                                                <small>
                                                    <i class="ri-arrow-down-s-line"></i>
                                                </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow($isReportsActive)}}" id="activityRecords">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.record.transaction') ? 'active' : '' }}" href="{{ route('admin.report.record.transaction') }}">
                                                    <p>{{ translate("Transaction History") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.record.subscription') ? 'active' : '' }}" href="{{ route('admin.report.record.subscription') }}">
                                                    <p>{{ translate("Subscription History") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.record.payment') ? 'active' : '' }}" href="{{ route('admin.report.record.payment') }}">
                                                    <p>{{ translate("Payment History") }}</p>
                                                    @if($pending_manual_payment_count > 0)
                                                        <span class="fs-12 i-badge pill danger-soft">
                                                           {{ $pending_manual_payment_count }}
                                                        </span>
                                                    @endif
                                                </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link collapsed {{menuShow($isCreditLogsActive)}}" data-bs-toggle="collapse" href="#creditLogs" role="button" aria-expanded="false" aria-controls="creditLogs">
                                            <span>
                                                <i class="ri-hand-coin-line"></i>
                                            </span>
                                            <p> {{ translate("Credit Logs") }} <small>
                                                <i class="ri-arrow-down-s-line"></i>
                                                </small>
                                            </p>
                                        </a>
                                        <div class="side-menu-dropdown collapse {{menuShow($isCreditLogsActive)}}" id="creditLogs">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.credit.sms') ? 'active' : '' }}" href="{{ route('admin.report.credit.sms') }}">
                                                    <p>{{ translate("SMS") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.credit.email') ? 'active' : '' }}" href="{{ route('admin.report.credit.email') }}">
                                                    <p>{{ translate("Email") }}</p>
                                                </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                <a class="sidebar-menu-link {{ request()->routeis('admin.report.credit.whatsapp') ? 'active' : '' }}" href="{{ route('admin.report.credit.whatsapp') }}">
                                                    <p>{{ translate("WhatsApp") }}</p>
                                                </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{menuActive($isFrontendCustomizeActive)}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-layout-4-line"></i>
                        </span>
                        <span class="menu-label">{{ translate('Frontend Sections')}}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow($isFrontendCustomizeActive)}}" {{ menuShow($isFrontendCustomizeActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                            <span class="back-to-menu" role="button">
                                <i class="ri-arrow-left-line"></i>
                            </span>
                            <h6>{{ translate('Section List')}}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    @php
                                        $lastElement = collect(request()->segments())->last();
                                    @endphp
                                    @foreach(getFrontendSection() as $key => $section)
                                        @if($key == 'service-highlight' || $key == 'service-overview' || $key == 'service-feature' || $key == 'service-sections' || $key == 'service-menu' || $key == 'service-breadcrumb' || $key == 'service-details')
                                            <?php
                                                $isSectionActive = request()->route()->parameter('section_key') === $key;
                                            ?>
                                            <li class="sidebar-menu-item">
                                                <a class="sidebar-menu-link collapsed {{ $isSectionActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#frontend_groups_{{ $key }}" role="button" aria-expanded="{{ $isSectionActive ? 'true' : 'false' }}" aria-controls="frontend_groups_{{ $key }}">
                                                    <span>
                                                        <i class="{{ __(\Illuminate\Support\Arr::get($section, 'icon', '')) }}"></i>
                                                    </span>
                                                    <p>
                                                        {{ __(\Illuminate\Support\Arr::get($section, 'name', '')) }}
                                                        <small>
                                                            <i class="ri-arrow-down-s-line"></i>
                                                        </small>
                                                    </p>
                                                </a>
                                                <div class="side-menu-dropdown collapse {{ $isSectionActive ? 'show' : '' }}" id="frontend_groups_{{ $key }}">
                                                    <ul class="sub-menu">
                                                        @foreach($section['types'] as $section_key => $section_value)
                                                            @php
                                                                $subMenuName = \Illuminate\Support\Arr::get($section_value, 'name', '');
                                                                if (!$subMenuName) continue;
                                                                $lowerCaseSubMenuName = strtolower($subMenuName);
                                                               
                                                                $isSubMenuActive = request()->route()->parameter('type') === $lowerCaseSubMenuName && $isSectionActive;
                                                            @endphp
                                                            <li class="sub-menu-item">
                                                                <a class="sidebar-menu-link {{ $isSubMenuActive ? 'active' : '' }}" href="{{ route('admin.frontend.sections.index', ['section_key' => $key, 'type' => $lowerCaseSubMenuName]) }}">
                                                                    <p>{{ $subMenuName }}</p>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </li>
                                        @else
                                            <?php
                                                $isSimpleSectionActive = request()->route()->parameter('section_key') === $key;
                                            ?>
                                            <li class="sidebar-menu-item">
                                                <a class="sidebar-menu-link {{ $isSimpleSectionActive ? 'active' : '' }}" href="{{ route('admin.frontend.sections.index', $key) }}">
                                                    <span>
                                                        <i class="{{ __(\Illuminate\Support\Arr::get($section, 'icon', '')) }}"></i>
                                                    </span>
                                                    <p>{{ __(\Illuminate\Support\Arr::get($section, 'name', '')) }}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="menu">
                    <a class="menu-link {{request()->routeIs('admin.blog.*') ? 'active' :''}}" href="{{ route("admin.blog.index") }}">
                        <span class="menu-symbol">
                            <i class="ri-news-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Blog") }}</span>
                    </a>
                </li>
            </ul>

            <ul class="menus">
                <li class="menu">
                    <a class="menu-link {{request()->routeIs('admin.support.*') ? 'active' :''}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-question-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Support") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow($isSupportTicketActive)}}" {{ menuShow($isSupportTicketActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("Support Ticket") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isSupportTicketActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#currency" role="button" aria-expanded="false" aria-controls="currencyList">
                                            <span>
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </span>
                                            <p> {{ translate("All Tickets") }} <small> <i class="ri-arrow-down-s-line"></i> </small>
                                            </p>
                                        </a>

                                        <div class="side-menu-dropdown collapse {{menuShow($isSupportTicketActive)}}" id="currency">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.support.ticket.index']) }}" href="{{route('admin.support.ticket.index')}}">
                                                        <p>{{ translate("Ticket List") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.support.ticket.priority.high']) }}" href="{{route('admin.support.ticket.priority.high')}}">
                                                        <p>{{ translate("High Priority") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.support.ticket.priority.medium']) }}" href="{{route('admin.support.ticket.priority.medium')}}">
                                                        <p>{{ translate("Medium Priority") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.support.ticket.priority.low']) }}" href="{{route('admin.support.ticket.priority.low')}}">
                                                        <p>{{ translate("Low Priority") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{request()->routeIs('admin.support.ticket.running') ? 'active' :''}}" href="{{ route('admin.support.ticket.running') }}">
                                            <span>
                                                <i class="ri-hourglass-line"></i>
                                            </span>
                                            <p>{{ translate("Running tickets") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{request()->routeIs('admin.support.ticket.answered') ? 'active' :''}}" href="{{ route('admin.support.ticket.answered') }}">
                                            <span>
                                                <i class="ri-question-answer-line"></i>
                                            </span>
                                            <p>{{ translate("Answered tickets") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{request()->routeIs('admin.support.ticket.replied') ? 'active' :''}}" href="{{ route('admin.support.ticket.replied') }}">
                                            <span>
                                                <i class="ri-reply-line"></i>
                                            </span>
                                            <p>{{ translate("Replied tickets") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{request()->routeIs('admin.support.ticket.closed') ? 'active' :''}}" href="{{ route('admin.support.ticket.closed') }}">
                                            <span>
                                                <i class="ri-door-closed-line"></i>
                                            </span>
                                            <p>{{ translate("Closed tickets") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>


                <li class="menu">
                    <a class="menu-link {{request()->routeIs('admin.system.*') ? 'active' :''}}" href="javascript:void(0)">
                        <span class="menu-symbol">
                            <i class="ri-settings-3-line"></i>
                        </span>
                        <span class="menu-label">{{ translate("Settings") }}</span>
                        <span class="menu-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                    <div class="sub-menu-wrapper {{menuShow($isSettingActive)}}" {{ menuShow($isSettingActive) == 'show' ? "style='opacity:1;visibility:visible;'" : '' }}>
                        <div class="sub-menu-container">
                            <div class="d-flex align-items-center gap-4 mb-3 px-2 sub-menu-header">
                                <span class="back-to-menu" role="button">
                                    <i class="ri-arrow-left-line"></i>
                                </span>
                                <h6>{{ translate("System Settings") }}</h6>
                            </div>

                            <div class="sidebar-menu-container">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "general" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "general"]) }}">
                                            <span>
                                                <i class="ri-tools-line"></i>
                                            </span>
                                            <p>{{ translate("General") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "email_verifcation" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "email_verifcation"]) }}">
                                            <span>
                                                <i class="ri-mail-check-line"></i>
                                            </span>
                                            <p>{{ translate("Email Verification") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "authentication" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "authentication"]) }}">
                                            <span>
                                                <i class="ri-git-repository-private-line"></i>
                                            </span>
                                            <p>{{ translate("Authentication") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "member" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "member"]) }}">
                                            <span>
                                                <i class="ri-user-settings-line"></i>
                                            </span>
                                            <p>{{ translate("Member") }}</p>
                                        </a>
                                    </li>


                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{menuShow($isCurrencyActive) != 'show' ? 'collapsed' : ''}}" data-bs-toggle="collapse" href="#currency" role="button" aria-expanded="false" aria-controls="currencyList">
                                            <span>
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </span>
                                            <p> {{ translate("Currency") }} <small> <i class="ri-arrow-down-s-line"></i> </small>
                                            </p>
                                        </a>

                                        <div class="side-menu-dropdown collapse {{menuShow($isCurrencyActive)}}" id="currency">
                                            <ul class="sub-menu">
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.system.currency.index']) }}" href="{{route('admin.system.currency.index')}}">
                                                        <p>{{ translate("All Currencies") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.system.currency.active']) }}" href="{{route('admin.system.currency.active')}}">
                                                        <p>{{ translate("Active Currencies") }}</p>
                                                    </a>
                                                </li>
                                                <li class="sub-menu-item">
                                                    <a class="sidebar-menu-link {{ menuActive(['admin.system.currency.inactive']) }}" href="{{route('admin.system.currency.inactive')}}">
                                                        <p>{{ translate("Inactive Currencies") }}</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "webhook" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "webhook"]) }}">
                                            <span>
                                                <i class="ri-webhook-line"></i>
                                            </span>
                                            <p>{{ translate("Webhook") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "recaptcha" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "recaptcha"]) }}">
                                            <span>
                                                <i class="ri-recycle-line"></i>
                                            </span>
                                            <p>{{ translate("reCAPTCHA") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "social_login" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "social_login"]) }}">
                                            <span>
                                                <i class="ri-login-box-line"></i>
                                            </span>
                                            <p>{{ translate("Social Login") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "plugin" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "plugin"]) }}">
                                            <span>
                                                <i class="ri-puzzle-2-line"></i>
                                            </span>
                                            <p>{{ translate("Plugins") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ menuActive(['admin.system.language.index']) }}" href="{{ route('admin.system.language.index') }}">
                                            <span>
                                                <i class="ri-translate-2"></i>
                                            </span>
                                            <p>{{ translate("Language") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ menuActive(['admin.system.spam.word.index']) }}" href="{{ route('admin.system.spam.word.index') }}">
                                            <span>
                                                <i class="ri-article-line"></i>
                                            </span>
                                            <p>{{ translate("Spam words") }}</p>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link {{ request()->type == "seo" ? 'active' : '' }}" href="{{ route("admin.system.setting", ["type" => "seo"]) }}">
                                            <span>
                                                <i class="ri-puzzle-2-line"></i>
                                            </span>
                                            <p>{{ translate("SEO Settings") }}</p>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-link" href="{{ route('admin.system.info') }}">
                                            <span>
                                                <i class="ri-information-2-line"></i>
                                            </span>
                                            <p>{{ translate("System info") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</aside>