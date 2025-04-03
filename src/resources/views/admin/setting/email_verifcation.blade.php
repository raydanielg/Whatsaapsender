@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ textFormat(['_'], $title, ' ') }}</h2>
                <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ textFormat(['_'], $title, ' ') }} </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
        <div class="card">
        
            <div class="card-body pt-0">
                <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm" id="settings-form">
                    @csrf
                    <div class="form-element">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Applicability") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-12 parent">
                                        <div class="form-inner">
                                            <label class="form-label"> 
                                                {{ translate("Email Contact Verification") }} 
                                            </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" 
                                                    for="{{ \App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value }}">
                                                    {{ translate("Turn on/off email verification") }}
                                                </label for="{{ \App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value }}" >
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="{{ \App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value }}" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value }}]" 
                                                        {{ site_settings(\App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value) == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}/>
                                                    <label for="{{ \App\Enums\SettingKey::EMAIL_CONTACT_VERIFICATION->value }}" 
                                                        class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php 
                        $additionalChecks = site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value);
                        $additionalChecks = $additionalChecks 
                                                ? json_decode($additionalChecks, true)
                                                : [];
                        $isInvalidSyntaxActive          = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::INVALID_SYNTAX->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                        $isInvalidDomainActive          = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::INVALID_DOMAIN->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                        $isDisposableDomainActive       = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::DISPOSABLE_DOMAIN->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                        $isDomainTypoActive             = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::DOMAIN_TYPOS->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                        $isRoleBasedEmailCheckActive    = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::ROLE_BASED_EMAIL->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                        $isSuspiciousTldCheckActive     = \Illuminate\Support\Arr::get($additionalChecks, \App\Enums\SettingKey::CHECK_TLD->value) == \App\Enums\StatusEnum::TRUE->status() ? true : false;
                    @endphp

                    <div class="form-element">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3 child d-none">
                                <h5 class="form-element-title">{{ translate("Additional Settings") }}
                                    <span data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        data-bs-title="{{ translate('Setup behaviors for MX, syntax, disposable and typo checks') }}">
                                        <i class="ri-question-line"></i>
                                    </span>
                                </h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9 child d-none">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-invalid-syntax" class="form-label"> 
                                                    {{ translate("Check Invalid syntax") }} 
                                                </label>
                                                
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-invalid-syntax" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::INVALID_SYNTAX->value }}]" 
                                                        {{ $isInvalidSyntaxActive ? "checked" : "" }}>
                                                    <label for="check-invalid-syntax" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-inner mt-3">
                                                <label for="syntax-invalid-message" 
                                                        class="form-label"> 
                                                        {{ translate("Reason message for an invalid syntax") }} 
                                                        <span data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            data-bs-title="{{ translate('If an Email contact address has an invalid syntax this message will be shown for that contact both in admin and user panel') }}">
                                                            <i class="ri-question-line"></i>
                                                        </span>
                                                        <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="syntax-invalid-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::INVALID_SYNTAX_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter invalid syntax reason message') }}" 
                                                    aria-label="{{ translate('Invalid Syntax Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::INVALID_SYNTAX_MESSAGE->value ]) 
                                                    ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::INVALID_SYNTAX_MESSAGE->value] : ""}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-invalid-domain" class="form-label"> 
                                                    {{ translate("Check Invalid Domain") }} 
                                                </label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-invalid-domain" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::INVALID_DOMAIN->value }}]" 
                                                        {{ $isInvalidDomainActive ? "checked" : "" }}>
                                                    <label for="check-invalid-domain" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-inner mt-3">
                                                <label for="mx-record-message" 
                                                    class="form-label"> 
                                                    {{ translate("Reason message for an invalid domain") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('If an Email contact domain is invalid or no mx records were found for that contact below message will be shown for that contact both in admin and user panel') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="mx-record-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::INVALID_DOMAIN_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter invalid domain reason message') }}" 
                                                    aria-label="{{ translate('Invalid Domain Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::INVALID_DOMAIN_MESSAGE->value]) 
                                                    ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::INVALID_DOMAIN_MESSAGE->value] : ""}}"/>
                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-disposable-domain" class="form-label"> 
                                                    {{ translate("Check Disposable Domain") }} 
                                                </label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-disposable-domain" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::DISPOSABLE_DOMAIN->value }}]" 
                                                        {{ $isDisposableDomainActive ? "checked" : "" }}>
                                                    <label for="check-disposable-domain" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-inner mt-3">
                                                <label for="disposable-domain-list" class="form-label">
                                                    {{ translate("Disposable Domains") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('Added domains will be flagged as disposable emails') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                </label>
                                                <select data-placeholder="{{translate('Add Disposable Domains')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::DISPOSABLE_DOMAIN_LIST->value }}][]" 
                                                    data-show="5" 
                                                    id="disposable-domain-list" 
                                                    multiple="multiple">
                                                    <option value=""></option>
                                                    @foreach(json_decode(site_settings(\App\Enums\SettingKey::DISPOSABLE_DOMAIN_LIST->value)) ?? [] as $disposable_domain)
                                                        <option value="{{$disposable_domain}}" selected>{{$disposable_domain}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                                            </div>
                                            <div class="form-inner mt-3">
                                                <label for="disposable-domain-message" 
                                                    class="form-label"> 
                                                    {{ translate("Reason message for a disposable domain") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('If an Email contact domain contains one of the disposable domains specified, then this message will be shown for that contact both in admin and user panel') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="disposable-domain-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::DISPOSABLE_DOMAIN_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter disposable domain reason message') }}" 
                                                    aria-label="{{ translate('Disposable Domain Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::DISPOSABLE_DOMAIN_MESSAGE->value]) 
                                                    ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::DISPOSABLE_DOMAIN_MESSAGE->value] : ""}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-domain-typos" class="form-label"> 
                                                    {{ translate("Check Domain Typos") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('Add typos for common domains') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                </label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-domain-typos" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::DOMAIN_TYPOS->value }}]" 
                                                       {{ $isDomainTypoActive ? "checked" : "" }}>
                                                    <label for="check-domain-typos" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

@php
    $commonDomains = json_decode(site_settings(\App\Enums\SettingKey::COMMON_DOMAIN->value), true);
@endphp

<div id="domain-fields-container">
    @if (!empty($commonDomains))
        @foreach ($commonDomains as $index => $domainData)
            <div class="domain-field-row row align-items-center" data-row-id="{{ uniqid('domain_') }}">
                <div class="col-md-5">
                    <div class="form-inner mt-3">
                        <label for="common-domain-name-{{ $index }}" class="form-label">
                            {{ translate("Common Domain") }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            id="common-domain-name-{{ $index }}" 
                            name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][{{ $index }}][name][]" 
                            class="form-control" 
                            placeholder="{{ translate('Enter common domain name') }}"
                            value="{{ Arr::get($domainData, 'name.0', '') }}">
                        <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-inner mt-3">
                        <label for="domain-typo-list-{{ $index }}" class="form-label">
                            {{ translate("Typos") }}
                        </label>
                        <select id="domain-typo-list-{{ $index }}"
                            data-id="disposable-domain-list"
                            class="form-select select2-search"
                            name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][{{ $index }}][typo][]" 
                            data-placeholder="{{ translate('Add Typos for the common domain') }}"
                            multiple="multiple">
                            @foreach (Arr::get($domainData, 'typo', []) as $typo)
                                <option value="{{ $typo }}" selected>{{ $typo }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" 
                            name="site_settings[{{ \App\Enums\SettingKey::DISPOSABLE_DOMAIN_LIST->value }}][]" 
                            value="" 
                            class="multi-select-fallback" 
                            data-name="site_settings[{{ \App\Enums\SettingKey::DISPOSABLE_DOMAIN_LIST->value }}][]">
                        <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-inner">
                        <label class="form-label">{{ translate("Action") }}</label>
                        <button type="button" class="btn btn-outline-primary border add-field-btn">
                            <i class="ri-add-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger border delete-field-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <!-- Default empty row when no data exists -->
        <div class="domain-field-row row align-items-center" data-row-id="{{ uniqid('domain_') }}">
            <div class="col-md-5">
                <div class="form-inner mt-3">
                    <label for="common-domain-name-0" class="form-label">
                        {{ translate("Common Domain") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                        id="common-domain-name-0" 
                        name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][0][name][]" 
                        class="form-control" 
                        placeholder="{{ translate('Enter common domain name') }}"
                        value="">
                    <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-inner mt-3">
                    <label for="domain-typo-list-0" class="form-label">
                        {{ translate("Typos") }}
                    </label>
                    <select id="domain-typo-list-0"
                        class="form-select select2-search"
                        name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][0][typo][]" 
                        data-placeholder="{{ translate('Add Typos for the common domain') }}"
                        multiple="multiple">
                    </select>
                    <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-inner">
                    <label class="form-label">{{ translate("Action") }}</label>
                    <button type="button" class="btn btn-outline-primary border add-field-btn">
                        <i class="ri-add-line"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger border delete-field-btn">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>



                                            <div class="form-inner mt-3">
                                                <label for="domain-typo-message" 
                                                    class="form-label"> 
                                                    {{ translate("Reason message for a domain typo") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('If an Email contact domain contains one of the typos specified, then this message will be shown for that contact both in admin and user panel') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="domain-typo-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::DOMAIN_TYPO_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter domain typo reason message') }}" 
                                                    aria-label="{{ translate('Domain Typo Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::DOMAIN_TYPO_MESSAGE->value]) 
                                                    ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::DOMAIN_TYPO_MESSAGE->value] : ""}}"/>
                                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-role-based-domain" class="form-label"> 
                                                    {{ translate("Check Role-based Emails") }} 
                                                </label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-role-based-domain" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::ROLE_BASED_EMAIL->value }}]" 
                                                       {{ $isRoleBasedEmailCheckActive ? "checked" : "" }}>
                                                    <label for="check-role-based-domain" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-inner mt-3">
                                                <label for="email-role-list" class="form-label">
                                                    {{ translate("Email Roles") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('Added roles will be flagged as a role-based email addresses') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                </label>
                                                <select data-placeholder="{{translate('Add Roles')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::EMAIL_ROLE_LIST->value }}][]" 
                                                    data-show="5" 
                                                    id="email-role-list"
                                                    multiple="multiple">
                                                    <option value=""></option>
                                                    @foreach(json_decode(site_settings(\App\Enums\SettingKey::EMAIL_ROLE_LIST->value)) ?? [] as $email_role)
                                                        <option value="{{$email_role}}" selected>{{$email_role}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::EMAIL_ROLE_LIST->value }}][]" 
                                                        value="" 
                                                        class="multi-select-fallback" 
                                                        data-name="site_settings[{{ \App\Enums\SettingKey::EMAIL_ROLE_LIST->value }}][]">
                                            </div>
                                            <div class="form-inner mt-3">
                                                <label for="role-based-message" 
                                                    class="form-label"> 
                                                    {{ translate("Reason message for a Role-Based email check") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('If an Email contact contains one of the roles specified, then this message will be shown for that contact both in admin and user panel') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="role-based-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::ROLE_BASED_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter role-based Email reason message') }}" 
                                                    aria-label="{{ translate('Role-Based Email Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::ROLE_BASED_MESSAGE->value]) 
                                                    ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::ROLE_BASED_MESSAGE->value] : ""}}"/>
                                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border p-3 rounded-3">
                                            <div class="custom-test d-flex alig-item-center justify-content-between gap-3">   
                                                <label for="check-tld" class="form-label"> 
                                                    {{ translate("Check Suspicious TLDs") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('Suspicious TLDs (Top Level Domains) are domain extensions that are often associated with spam, scams, or malicious activities due to their low cost and ease of registration. Add tld examples here') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                </label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" 
                                                        class="switch-input" 
                                                        id="check-tld" 
                                                        name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::CHECK_TLD->value }}]" 
                                                       {{ $isSuspiciousTldCheckActive ? "checked" : "" }}>
                                                    <label for="check-tld" class="toggle">
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-inner mt-3">
                                                <label for="tld-list" class="form-label">
                                                    {{ translate("TLDs") }}
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('Added TLDs will be flagged as suspicious if found in any Email Addresses') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                </label>
                                                <select data-placeholder="{{translate('Add TLD')}}" 
                                                    class="form-select select2-search" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::TLD_LIST->value }}][]" 
                                                    data-show="5" 
                                                    id="tld-list" 
                                                    multiple="multiple">
                                                    <option value=""></option>
                                                    @foreach(json_decode(site_settings(\App\Enums\SettingKey::TLD_LIST->value)) ?? [] as $tld)
                                                        <option value="{{$tld}}" selected>{{$tld}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::TLD_LIST->value }}][]" 
                                                    value="" 
                                                    class="multi-select-fallback" 
                                                    data-name="site_settings[{{ \App\Enums\SettingKey::TLD_LIST->value }}][]">
                                            </div>
                                            <div class="form-inner mt-3">
                                                <label for="tld-message" 
                                                    class="form-label"> 
                                                    {{ translate("Reason message for a suspicious TLD") }} 
                                                    <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        data-bs-title="{{ translate('If an Email contact domain contains one of the TLDs specified, then this message will be shown for that contact both in admin and user panel') }}">
                                                        <i class="ri-question-line"></i>
                                                    </span>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <input type="text" 
                                                    id="tld-message" 
                                                    name="site_settings[{{ \App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value }}][{{ \App\Enums\SettingKey::TLD_MESSAGE->value }}]" 
                                                    class="form-control" 
                                                    placeholder="{{ translate('Enter suspicious TLD reason message') }}" 
                                                    aria-label="{{ translate('Suspicious TLD Message') }}" 
                                                    value="{{isset(json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::TLD_MESSAGE->value]) 
                                                        ? json_decode(site_settings(\App\Enums\SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value), true)[\App\Enums\SettingKey::TLD_MESSAGE->value] : ""}}"/>
                                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                            <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush

@push('script-push')
<script>
    "use strict";
    select2_search($('.select2-search').data('placeholder'), null, true);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('domain-fields-container');
        
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-field-btn')) {

                const fieldCount = container.querySelectorAll('.domain-field-row').length + 1;
                const newRow     = document.createElement('div');
                newRow.className = 'domain-field-row';
                newRow.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="form-inner mt-3">
                                <label for="common-domain-name-${fieldCount}" class="form-label">
                                    {{translate("Common Domain")}} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    id="common-domain-name-${fieldCount}" 
                                    name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][${fieldCount}][name][]" 
                                    class="form-control" 
                                    placeholder="{{ translate('Enter disposable domain reason message') }}">
                                <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-inner mt-3">
                                <label for="domain_typo-list-${fieldCount}" class="form-label">
                                    {{ translate("Typos") }}
                                </label>
                                <select id="domain_typo-list-${fieldCount}"
                                    class="form-select select2-search"
                                    name="site_settings[{{ \App\Enums\SettingKey::COMMON_DOMAIN->value }}][${fieldCount}][typo][]" 
                                    data-placeholder="{{translate('Add Typos for the common domain')}}"
                                    data-show="5"
                                    multiple="multiple">
                                    <option value=""></option>
                                </select>
                                <p class="form-element-note">{{ translate("Include TLDs along with the domain") }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-inner">
                                <label class="form-label">
                                    {{ translate("Action") }}
                                </label>
                                <button type="button" class="btn btn-outline-primary border add-field-btn">
                                    <i class="ri-add-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger border delete-field-btn">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(newRow);
                const newSelect = newRow.querySelector('select');
                select2_search($(newSelect).data('placeholder'), null, true);
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-field-btn')) {
                const row = e.target.closest('.domain-field-row');
                if (container.querySelectorAll('.domain-field-row').length > 1) {
                    
                    row.querySelectorAll('select').forEach(select => {
                        if ($(select).data('select2')) {
                            $(select).select2('destroy');
                        }
                    });
                    row.remove();
                }
            }
        });
    });
</script>

<script>
    "use strick";
    $(document).ready(function() {

        setInitialVisibility();
        updateBackgroundClass();
        $('.parent input[type="checkbox"]').change(function() {

            toggleChildren();
        });
        $('.switch-input').on('change', function() {

            updateBackgroundClass();
        });
        $('form').on('submit', function(e) {

            $('.select2-search[multiple]').each(function () {

                let $select = $(this);
                let selectName = $select.attr('name');
                let $fallback = $('.multi-select-fallback[data-name="' + selectName + '"]');
                let selectedValues = $select.val();

                if (!selectedValues || selectedValues.length === 0) {
                    $fallback.prop('disabled', false); 
                } else {
                    $fallback.prop('disabled', true); 
                }
            });
            $('.checkbox-data').each(function() {
                var $checkbox = $(this).find('.switch-input');
                var $hiddenInput = $(this).find('input[type="hidden"]');

                if ($checkbox.is(':checked')) {
                    if ($hiddenInput.length === 0) {
                        $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::TRUE->status() }}">');
                    } else {
                        $hiddenInput.val('{{ \App\Enums\StatusEnum::TRUE->status() }}');
                    }
                } else {
                    if ($hiddenInput.length === 0) {
                        $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::FALSE->status() }}">');
                    } else {
                        $hiddenInput.val('{{ \App\Enums\StatusEnum::FALSE->status() }}');
                    }
                }
            });
        });
    });
</script>
   
@endpush
