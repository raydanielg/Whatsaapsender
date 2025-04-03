<section class="pricing-plans pt-100 pb-100" id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center section-header section-header-two align-items-center">
                    <span class="sub-title">{{translate(getArrayValue(@$plan_content->section_value, 'sub_heading'))}}</span>
                    <h3 class="section-title">{{translate(getArrayValue(@$plan_content->section_value, 'heading'))}}</h3>
                    <p class="title-description">{{translate(getArrayValue(@$plan_content->section_value, 'description'))}}</p>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-center">
            @foreach($plans as $key => $plan)
                @if($plan->amount>0)
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-item @if($plan->recommended_status == 1)recommend-item @endif">
                        <div class="pricing-item-top">
                            <div class="pricing-detail">
                                <h5>
                                    <span>
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 17a.833.833 0 01-.833-.833 3.333 3.333 0 00-3.334-3.334.833.833 0 110-1.666 3.333 3.333 0 003.334-3.334.833.833 0 111.666 0 3.333 3.333 0 003.334 3.334.833.833 0 110 1.666 3.333 3.333 0 00-3.334 3.334c0 .46-.373.833-.833.833z" />
                                        </svg>
                                    </span>
                                    {{ucfirst($plan->name)}}
                                </h5>
                            </div>
                            <p>{{$plan->description}}</p>
                        </div>

                        <div class="price">
                            <span>{{$general->currency_symbol}}{{shortAmount($plan->amount)}} </span> <small>/ {{$plan->duration}} {{ translate('Days')}}</small>
                        </div>

                        <div class="pricing-item-bottom">
                            <ul class="pricing-features">
                                @if($plan->carry_forward == App\Enums\StatusEnum::TRUE->status())
                                    <li class="pricing-feature">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                        </span>
                                        <b>{{ translate("Credit carry forward when renewed") }}</b>
                                    </li>
                                @endif
                                @if($plan->type == App\Enums\StatusEnum::TRUE->status())
                                    <li class="pricing-feature">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                        </span>
                                        {{ translate("Use Admin's Gateway for:") }} 
                                        @if($plan->sms->is_allowed == true) {{ translate(" SMS ") }} @endif
                                        @if($plan->sms->android->is_allowed == true) {{ translate(" Android ") }} @endif
                                        @if($plan->email->is_allowed == true) {{ translate(" Email ") }} @endif 
                                    </li>
                                    @if($plan->whatsapp->is_allowed == true)
                                        <li class="pricing-feature">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                            </span>
                                            {{ translate("Add ") }} {{$plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit}} {{ translate(" Whatsapp gateways") }}
                                        </li>
                                    @endif
                                
                                @elseif($plan->type == App\Enums\StatusEnum::FALSE->status())
                                    @if($plan->sms->android->is_allowed)
                                        <li class="pricing-feature">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                            </span>
                                            {{ translate('Add ') }} {{$plan->sms->android->gateway_limit == 0 ? "unlimited" : $plan->sms->android->gateway_limit}} {{ translate(" Android gateways") }}
                                        </li>
                                    @endif
                                    @if($plan->whatsapp->is_allowed)
                                        <li class="pricing-feature">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                            </span>
                                            {{ translate('Add ') }} {{$plan->whatsapp->gateway_limit == 0 ? "unlimited" : $plan->whatsapp->gateway_limit}} {{ translate(" Whatsapp gateways") }}
                                        </li>
                                    @endif
                                    
                                    @if($plan->email->is_allowed == true)
                                        @php 
                                            $gateway_mail 		= (array)@$plan->email->allowed_gateways;
                                            $total_mail_gateway = 0; 
                                            foreach ($gateway_mail as $email_value) { $total_mail_gateway += $email_value; }
                                        @endphp
                                        <li class="pricing-feature">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                            </span>
                                            {{ translate('Add up To ') }} {{ $total_mail_gateway }} {{ translate(" Mail Gateways") }}
                                        </li>
                                    @endif
                                    @if($plan->sms->is_allowed == true)
                                        @php 
                                            $gateway_sms 	   = (array)@$plan->sms->allowed_gateways; 
                                            $total_sms_gateway = 0;
                                            foreach ($gateway_sms as $sms_value) { $total_sms_gateway += $sms_value; }
                                            
                                        @endphp
                                        <li class="pricing-feature">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                            </span>
                                            {{ translate('Add Up To ') }}{{ $total_sms_gateway }}{{ translate(" SMS Gateways") }}
                                        </li>
                                        
                                    @endif
                                   
                                @endif
                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>
                                    {{$plan->sms->credits  ?? "N/A"}} {{ translate('SMS Credit') }}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>
                                    {{$plan->email->credits  ?? "N/A"}} {{ translate('Email Credit') }}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>

                                    {{$plan->whatsapp->credits ?? "N/A"}} {{ translate('WhatsApp Credit') }}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>
                                    {{ translate('1 Credit for '.$general->sms_word_text_count.' plain word')}}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>

                                    {{ translate('1 Credit for '.$general->sms_word_unicode_count.' unicode word')}}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>
                                    {{ translate('1 Credit for '.$general->whatsapp_word_count.' word')}}
                                </li>

                                <li class="pricing-feature">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 520 520" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M239.987 460.841a10 10 0 0 1-7.343-3.213L34.657 243.463A10 10 0 0 1 42 226.675h95.3a10.006 10.006 0 0 1 7.548 3.439l66.168 76.124c7.151-15.286 20.994-40.738 45.286-71.752 35.912-45.85 102.71-113.281 216.994-174.153a10 10 0 0 1 10.85 16.712c-.436.341-44.5 35.041-95.212 98.6-46.672 58.49-108.714 154.13-139.243 277.6a10 10 0 0 1-9.707 7.6z" data-name="6-Check"  opacity="1" data-original="#000000" class=""></path></g></svg>
                                    </span>
                                    {{ translate('1 Credit for per Email')}}
                                </li>
                            </ul>

                            <a href="{{route('user.plan.create')}}" class="ig-btn btn--primary btn--lg w-100">
                                @if($subscription)
                                    @if($plan->id == $subscription->plan_id)
                                        @if(Carbon\Carbon::now()->toDateTimeString() > $subscription->expired_date)
                                            {{ translate("Renew") }}
                                        @else
                                            {{ translate('Current Plan')}}
                                        @endif
                                    @else
                                        {{ translate('Upgrade Plan')}}
                                    @endif
                                @else
                                    {{ translate('Purchase Now')}}
                                @endif
                            </a>
                        </div>

                        @if($plan->recommended_status == 1)
                            <div class="ribbon">
                                <span>{{translate('Recommended')}}</span>
                            </div>
                        @endif

                        <div class="pricing-shape">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"><path opacity=".349" fill-rule="evenodd" clip-rule="evenodd" d="M94.714 9.882a100 100 0 0180.472-2.377L433 111.904 393.686 139l-556.687-6.588L94.714 9.882z" fill="url(#paint0_linear)"/><defs><linearGradient id="paint0_linear" x1="-111.329" y1="17.357" x2="-107.18" y2="149.186" gradientUnits="userSpaceOnUse"><stop offset=".001" stop-color="#E5ECF2"/><stop offset="1" stop-color="#fff"/></linearGradient></defs></svg>
                        </div>

                        <div class="recommend-bg">
                            <img src="https://i.ibb.co/b6SCQyb/64c2522cfdbe0fd7aeb79aa0-cta-bg.png" alt="64c2522cfdbe0fd7aeb79aa0-cta-bg">
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
