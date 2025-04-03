<section class="plan pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row g-4 align-items-end mb-60">
        <div class="col-8">
          <div class="section-title mb-0">
            <h3> {{getTranslatedArrayValue(@$plan_content->section_value, 'heading') }} <span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="{{ route('pricing') }}" class="i-btn btn--dark outline btn--md pill"> {{ translate("More") }} <i class="bi bi-arrow-right fs-20"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="pt-md-5">
        <div class="plan-card-wrapper">
          <div class="row g-xl-0 g-4">
            @foreach($plans->take(3) as $plan)
            <div class="col-xl-4 col-md-6">
              <div class="plan-card h-100 {{$plan->recommended_status == \App\Enums\StatusEnum::TRUE->status() ? 'recommend mt-md-0 mt-5' : ''}}">
                @if($plan->recommended_status == \App\Enums\StatusEnum::TRUE->status())
                  <div class="recommend-tag">{{ translate("Recommended") }}</div>
                @endif
                <span class="plan-title"> {{ucfirst($plan->name)}}</span>
                <div class="price">
                  <span>{{ getDefaultCurrencySymbol(json_decode(site_settings("currencies"), true)) }}</span>
                  <h5>{{shortAmount($plan->amount)}}</h5>
                  <p>{{ $plan->duration ?? translate('N/A')}} {{ translate(" / Days")  }}</p>
                </div>
                <p class="plan-desc">  {{$plan->description}} </p>
                <ul class="pricing-list">
                  @if($plan->carry_forward == \App\Enums\StatusEnum::TRUE->status())
                      <li> <i class="bi bi-check2"></i><b>{{ translate("Credit carry forward when renewed") }}</b></li>
                  @endif
                  @if($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true || $plan->sms->is_allowed == true || $plan->email->is_allowed == true)


                      @if($plan->type == \App\Enums\StatusEnum::FALSE->status() && ($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true))

                          @if($plan->sms->android->is_allowed == true)
                              <li> <i class="bi bi-check2"></i>{{ translate('Add ')}} {{ $plan->sms->android->gateway_limit == 0 ? "unlimited" : $plan->sms->android->gateway_limit }}{{ translate(" Android Gateways")}}</li>
                          @endif
                          @if($plan->whatsapp->is_allowed == true)
                              <li><i class="bi bi-check2"></i>{{ translate('Add ')}} {{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }} {{translate(" Whatsapp devices")}}</li>
                          @endif

                      @elseif($plan->type == \App\Enums\StatusEnum::TRUE->status() && $plan->sms->android->is_allowed == true && $plan->whatsapp->is_allowed == true)

                          <li><i class="bi bi-check2"></i>{{ translate("Admin Gateways: ")}}
                              @if($plan->sms->is_allowed == true) {{ translate(" SMS ") }} @endif
                              @if($plan->sms->android->is_allowed == true) {{ translate(" Android ") }} @endif
                              @if($plan->email->is_allowed == true) {{ translate(" Email ") }} @endif
                          </li>

                      @endif

                      @if($plan->type == \App\Enums\StatusEnum::TRUE->status())
                          @if($plan->whatsapp->is_allowed == true)
                              <li><i class="bi bi-check2"></i>{{ translate('Add ')}}{{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }}{{translate(" Whatsapp devices")}}</li>
                          @endif

                      @elseif($plan->type == \App\Enums\StatusEnum::FALSE->status())
                          @if($plan->email->is_allowed == true)
                              @php
                                  $gateway_mail 		= (array)@$plan->email->allowed_gateways;
                                  $total_mail_gateway = 0;
                                  foreach ($gateway_mail as $email_value) { $total_mail_gateway += $email_value; }
                              @endphp
                              <li> <i class="bi bi-check2"></i>{{ translate('Add up To ') }}{{ $total_mail_gateway }} {{ translate(" Mail Gateways") }}</li>
                          @endif
                          @if($plan->sms->is_allowed == true)
                              @php
                                  $gateway_sms 	   = (array)@$plan->sms->allowed_gateways;
                                  $total_sms_gateway = 0;
                                  foreach ($gateway_sms as $sms_value) { $total_sms_gateway += $sms_value; }

                              @endphp
                              <li> <i class="bi bi-check2"></i>{{ translate('Add Up To ') }} {{ $total_sms_gateway }} {{ translate(" SMS Gateways") }}</li>
                          @endif
                      @endif
                  @endif

                  @if(!is_null($plan->sms_gateways))
                      <li><i class="bi bi-check2"></i>{{ translate('Add ') }}{{$plan->total_sms_gateway}}{{ translate(" gateways from") }}<br/><b>{{strToUpper(implode(", ",($plan->sms_gateways)))}}</b></li>
                  @endif
                  <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->email->credits != -1) {{$plan->email->credits}}{{ translate(' Email Credit') }} @else {{ translate("Unlimited Email Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->email?->credits_per_day != 0){{ translate("Spend up to ").@$plan->email?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                  <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->sms->credits != -1) {{$plan->sms->credits}}{{translate(' SMS Credit') }} @else {{ translate("Unlimited SMS Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->sms?->credits_per_day != 0){{ translate("Spend up to ").@$plan->sms?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                  <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->whatsapp->credits != -1) {{$plan->whatsapp->credits}}{{translate(' WhatsApp Credit') }}@else {{ translate("Unlimited WhatsApp Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->whatsapp?->credits_per_day != 0){{ translate("Spend up to ").@$plan->whatsapp?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                  <li> <i class="bi bi-check2"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_count").' plain word')}}</li>
                  <li> <i class="bi bi-check2"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_unicode_count").' unicode word')}}</li>
                  <li> <i class="bi bi-check2"></i>{{ translate('1 WhatsApp Credit for '.site_settings("whatsapp_word_count").' word')}}</li>
                  <li> <i class="bi bi-check2"></i>{{ translate('1 Email Credit per Email')}}</li>
                </ul>
                <div class="plan-action">
                  <a href="{{ route("login") }}" class="i-btn btn--primary outline btn--xl pill w-100"> {{ translate("Purchase Now") }} </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
       
        @if(count($plans) > 0) 
          @if(request()->routeIs('pricing') && $plan->count() >= 3)
            <div class="plan-card-wrapper mt-130">
              <div class="row g-xl-0 g-4">
                @foreach($plans->skip(3) as $plan)
                <div class="col-xl-4 col-md-6">
                  <div class="plan-card h-100">
                    <span class="plan-title"> {{ucfirst($plan->name)}}</span>
                    <div class="price">
                      <span>{{ getDefaultCurrencySymbol(json_decode(site_settings("currencies"), true)) }}</span>
                      <h5>{{shortAmount($plan->amount)}}</h5>
                      
                      <p>{{ $plan->duration ?? translate('N/A')}} {{ translate(" / Days")  }}</p>
                    </div>
                    <p class="plan-desc">  {{$plan->description}} </p>
                    <ul class="pricing-list">
                      @if($plan->carry_forward == \App\Enums\StatusEnum::TRUE->status())
                          <li> <i class="bi bi-check2"></i><b>{{ translate("Credit carry forward when renewed") }}</b></li>
                      @endif
                      @if($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true || $plan->sms->is_allowed == true || $plan->email->is_allowed == true)


                          @if($plan->type == \App\Enums\StatusEnum::FALSE->status() && ($plan->sms->android->is_allowed == true || $plan->whatsapp->is_allowed == true))

                              @if($plan->sms->android->is_allowed == true)
                                  <li> <i class="bi bi-check2"></i>{{ translate('Add ')}} {{ $plan->sms->android->gateway_limit == 0 ? "unlimited" : $plan->sms->android->gateway_limit }}{{ translate(" Android Gateways")}}</li>
                              @endif
                              @if($plan->whatsapp->is_allowed == true)
                                  <li><i class="bi bi-check2"></i>{{ translate('Add ')}} {{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }} {{translate(" Whatsapp devices")}}</li>
                              @endif

                          @elseif($plan->type == \App\Enums\StatusEnum::TRUE->status() && $plan->sms->android->is_allowed == true && $plan->whatsapp->is_allowed == true)

                              <li><i class="bi bi-check2"></i>{{ translate("Admin Gateways: ")}}
                                  @if($plan->sms->is_allowed == true) {{ translate(" SMS ") }} @endif
                                  @if($plan->sms->android->is_allowed == true) {{ translate(" Android ") }} @endif
                                  @if($plan->email->is_allowed == true) {{ translate(" Email ") }} @endif
                              </li>

                          @endif

                          @if($plan->type == \App\Enums\StatusEnum::TRUE->status())
                              @if($plan->whatsapp->is_allowed == true)
                                  <li><i class="bi bi-check2"></i>{{ translate('Add ')}}{{ $plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit }}{{translate(" Whatsapp devices")}}</li>
                              @endif

                          @elseif($plan->type == \App\Enums\StatusEnum::FALSE->status())
                              @if($plan->email->is_allowed == true)
                                  @php
                                      $gateway_mail 		= (array)@$plan->email->allowed_gateways;
                                      $total_mail_gateway = 0;
                                      foreach ($gateway_mail as $email_value) { $total_mail_gateway += $email_value; }
                                  @endphp
                                  <li> <i class="bi bi-check2"></i>{{ translate('Add up To ') }}{{ $total_mail_gateway }} {{ translate(" Mail Gateways") }}</li>
                              @endif
                              @if($plan->sms->is_allowed == true)
                                  @php
                                      $gateway_sms 	   = (array)@$plan->sms->allowed_gateways;
                                      $total_sms_gateway = 0;
                                      foreach ($gateway_sms as $sms_value) { $total_sms_gateway += $sms_value; }

                                  @endphp
                                  <li> <i class="bi bi-check2"></i>{{ translate('Add Up To ') }} {{ $total_sms_gateway }} {{ translate(" SMS Gateways") }}</li>
                              @endif
                          @endif
                      @endif

                      @if(!is_null($plan->sms_gateways))
                          <li><i class="bi bi-check2"></i>{{ translate('Add ') }}{{$plan->total_sms_gateway}}{{ translate(" gateways from") }}<br/><b>{{strToUpper(implode(", ",($plan->sms_gateways)))}}</b></li>
                      @endif

                      <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->email->credits != -1) {{$plan->email->credits}}{{ translate(' Email Credit') }} @else {{ translate("Unlimited Email Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->email?->credits_per_day != 0){{ translate("Spend up to ").@$plan->email?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                      <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->sms->credits != -1) {{$plan->sms->credits}}{{translate(' SMS Credit') }} @else {{ translate("Unlimited SMS Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->sms?->credits_per_day != 0){{ translate("Spend up to ").@$plan->sms?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                      <li class="d-flex flex-wrap"> <i class="bi bi-check2"></i>@if($plan->whatsapp->credits != -1) {{$plan->whatsapp->credits}}{{translate(' WhatsApp Credit') }}@else {{ translate("Unlimited WhatsApp Credits")}} @endif <span class="w-100 ps-1 ms-4 mt-1 fs-14 lh-1 text-primary fw-medium me-0">@if(@$plan->whatsapp?->credits_per_day != 0){{ translate("Spend up to ").@$plan->whatsapp?->credits_per_day.translate(" credits per day") }}@else {{ translate("Spend unlimited credits per day") }}@endif</span></li>
                      <li> <i class="bi bi-check2"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_count").' plain word')}}</li>
                      <li> <i class="bi bi-check2"></i>{{ translate('1 SMS Credit for '.site_settings("sms_word_unicode_count").' unicode word')}}</li>
                      <li> <i class="bi bi-check2"></i>{{ translate('1 WhatsApp Credit for '.site_settings("whatsapp_word_count").' word')}}</li>
                      <li> <i class="bi bi-check2"></i>{{ translate('1 Email Credit per Email')}}</li>
                    </ul>
                    <div class="plan-action">
                      <a href="{{ route("login") }}" class="i-btn btn--primary outline btn--xl pill w-100"> {{ translate("Purchase Now") }} </a>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          @endif
        @endif
      </div>
    </div>
  </section>
