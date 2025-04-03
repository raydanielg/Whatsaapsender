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
				<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{ translate("Dashboard") }}</a></li>
				<li class="breadcrumb-item active" aria-current="page">
				  {{ translate("Plans") }}
				</li>
			  </ol>
			</nav>
		  </div>
		</div>
	  </div>

	  <div class="row g-4 justify-content-center">
		@foreach($plans as $plan)
			<div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card plan-card">
                    <div class="card-body">
                    <div class="plan-top">
                        <h5 class="plan-title">
                            @if($plan->recommended_status == \App\Enums\StatusEnum::TRUE->status())
                                <span class="i-badge primary-solid pill me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("This plan is recommended by Admin") }}"><i class="ri-sparkling-line"></i></span>
                            @endif
                            {{ucfirst($plan->name)}}
                       
                        </h5>
                        <p class="plan-description">
                            {{$plan->description}}
                        </p>
                        <p class="price-tag"> {{ getDefaultCurrencySymbol(json_decode(site_settings("currencies"), true)) }}{{shortAmount($plan->amount)}}<span>/{{$plan->duration.translate(" Days") }}</span></p>
                        
                        @if($subscription)

                            @if($plan->id == $subscription->plan_id && ($subscription->status != App\Enums\SubscriptionStatus::REQUESTED->value || $subscription->status == App\Enums\SubscriptionStatus::RUNNING->value))

                                @if((Carbon\Carbon::now()->toDateTimeString() > $subscription->expired_date) && $subscription->status == App\Enums\SubscriptionStatus::EXPIRED->value)
                                    <a class="i-btn btn--warning btn--lg"
                                        href="{{ route('user.plan.make.payment', ['id' => $plan->id]) }}">{{ translate("Renew") }}
                                    </a>

                                @elseif($subscription->status == App\Enums\SubscriptionStatus::RUNNING->value || $subscription->status == App\Enums\SubscriptionStatus::RENEWED->value)
                                    <button class="i-btn btn--success btn--lg">{{ translate('Current Plan')}}</button>
                                @endif
                            @else
                                <a class="i-btn btn--info btn--lg"
                                    href="{{ route('user.plan.make.payment', ['id' => $plan->id]) }}">{{ translate('Upgrade Plan')}}
                                </a>

                            @endif
                        @else
                            <a class="i-btn btn--primary btn--lg"
                                href="{{ route('user.plan.make.payment', ['id' => $plan->id]) }}">{{ translate('Purchase Now')}}
                            </a>

                        @endif

                    </div>

                    <div class="price-feature">
                        <h6>{{ translate("What's included") }}</h6>

                        <ul class="price-feature-list">
                            @if($plan->carry_forward == \App\Enums\StatusEnum::TRUE->status())
                                <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i><b>{{ translate("Credit carry forward when renewed") }}</b></li>
                            @endif
                            @if($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true || $plan->sms->is_allowed == true || $plan->email->is_allowed == true)


                                @if($plan->type == \App\Enums\StatusEnum::FALSE->status() && ($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true))

                                    @if($plan->sms->android->is_allowed == true)
                                        <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('Add ')}} {{ $plan->sms->android->gateway_limit == 0 ? "unlimited" : $plan->sms->android->gateway_limit }}{{ translate(" Android Gateways")}}</li>
                                    @endif
                                    @if($plan->whatsapp->is_allowed == true)
                                        <li class="custom-li-height"><i class="bi bi-check-circle-fill"></i>{{ translate('Add ')}} {{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }} {{translate(" Whatsapp devices")}}</li>
                                    @endif

                                @elseif($plan->type == \App\Enums\StatusEnum::TRUE->status() && $plan->sms->android->is_allowed == true && $plan->whatsapp->is_allowed == true)

                                    <li class="custom-li-height"><i class="bi bi-check-circle-fill"></i>{{ translate("Admin Gateways: ")}}
                                        @if($plan->sms->is_allowed == true) {{ translate(" SMS ") }} @endif
                                        @if($plan->sms->android->is_allowed == true) {{ translate(" Android ") }} @endif
                                        @if($plan->email->is_allowed == true) {{ translate(" Email ") }} @endif
                                    </li>

                                @endif

                                @if($plan->type == \App\Enums\StatusEnum::TRUE->status())
                                    @if($plan->whatsapp->is_allowed == true)
                                        <li class="custom-li-height"><i class="bi bi-check-circle-fill"></i>{{ translate('Add ')}}{{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }}{{translate(" Whatsapp devices")}}</li>
                                    @endif

                                @elseif($plan->type == \App\Enums\StatusEnum::FALSE->status())
                                    @if($plan->email->is_allowed == true)
                                        @php
                                            $gateway_mail 		= (array)@$plan->email->allowed_gateways;
                                            $total_mail_gateway = 0;
                                            foreach ($gateway_mail as $email_value) { $total_mail_gateway += $email_value; }
                                        @endphp
                                        <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('Add up To ') }}{{ $total_mail_gateway }} {{ translate(" Mail Gateways") }}</li>
                                    @endif
                                    @if($plan->sms->is_allowed == true)
                                        @php
                                            $gateway_sms 	   = (array)@$plan->sms->allowed_gateways;
                                            $total_sms_gateway = 0;
                                            foreach ($gateway_sms as $sms_value) { $total_sms_gateway += $sms_value; }

                                        @endphp
                                        <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('Add Up To ') }} {{ $total_sms_gateway }} {{ translate(" SMS Gateways") }}</li>
                                    @endif
                                @endif
                            @endif

                            @if(!is_null($plan->sms_gateways))
                                <li><i class="bi bi-check-circle-fill"></i>{{ translate('Add ') }}{{$plan->total_sms_gateway}}{{ translate(" gateways from") }}<br/><b>{{strToUpper(implode(", ",($plan->sms_gateways)))}}</b></li>
                            @endif
                            <li class="flex-wrap custom-li-height"> <i class="bi bi-check-circle-fill"></i>@if($plan->email->credits != -1) {{$plan->email->credits}}{{ translate(' Email Credit') }} @else {{ translate("Unlimited Email Credits")}} @endif <span class="w-100 ps-1 ms-4 lh-1 text-secondary me-0 fs-12">@if(@$plan->email->credits_per_day != 0){{ translate("Spend up to ").$plan->email->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                            <li class="flex-wrap custom-li-height"> <i class="bi bi-check-circle-fill"></i>@if($plan->sms->credits != -1) {{$plan->sms->credits}}{{translate(' SMS Credit') }} @else {{ translate("Unlimited SMS Credits")}} @endif<span class="w-100 ps-1 ms-4 lh-1 text-secondary me-0 fs-12">@if(@$plan->sms->credits_per_day != 0){{ translate("Spend up to ").$plan->sms->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                            <li class="flex-wrap custom-li-height"> <i class="bi bi-check-circle-fill"></i>@if($plan->whatsapp->credits != -1) {{$plan->whatsapp->credits}}{{translate(' WhatsApp Credit') }}@else {{ translate("Unlimited WhatsApp Credits")}} @endif <span class="w-100 ps-1 ms-4 lh-1 text-secondary me-0 fs-12">@if(@$plan->whatsapp->credits_per_day != 0){{ translate("Spend up to ").$plan->whatsapp->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                            <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_count").' plain word')}}</li>
                            <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_unicode_count").' unicode word')}}</li>
                            <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('1 WhatsApp Credit for '.site_settings("whatsapp_word_count").' word')}}</li>
                            <li class="custom-li-height"> <i class="bi bi-check-circle-fill"></i>{{ translate('1 Email Credit per Email')}}</li>
                        </ul>
                    </div>
                    </div>
                </div>
			</div>
		@endforeach
	  </div>
	</div>
</main>
@endsection

@push('script-push')
<script>
	(function($){
		"use strict";
	})(jQuery);
</script>
@endpush
