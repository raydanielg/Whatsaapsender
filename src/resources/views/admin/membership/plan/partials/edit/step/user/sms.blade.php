<div class="step-content-item-user active">
     <div class="row gy-4">
       <div class="col-xxl-2 col-xl-3">
          <h5 class="form-element-title accessibility">
               {{ translate("SMS(User)") }}
          </h5>
       </div>
       <div class="col-xxl-8 col-xl-9">
          <div class="row gy-4 gx-xl-5">
               <div class="col-md-6 user_android">
                    <div class="form-inner-switch">
                         <div>
                              <label for="allow_user_android">
                                   <p class="fs-16 mb-3">
                                        {{ translate("Allow users to add Android Gateways") }}
                                   </p>
                                   <span>
                                        {{ translate("Enable unlimited Android Gateways if you set the value to '-1'") }}
                                   </span>
                              </label>
                         </div>
                         <div class="switch-wrapper mb-1">
                              <input class="switch-input allow_user_sms" 
                                   type="checkbox" 
                                   value="true" 
                                   name="allow_user_android" 
                                   id="allow_user_android" 
                                   {{ $plan->sms->android->is_allowed == true ? "checked" : "" }}>
                              <label for="allow_user_android" 
                                   class="toggle">
                                   <span></span>
                              </label>
                         </div>
                    </div>
               </div>
               <div class="col-md-6">
                    <div class="form-inner-switch">
                         <div>
                              <label for="sms_gateway">
                                   <p class="fs-16 mb-3">
                                        {{ translate("Allow Users To Add Multiple SMS Gateways") }}
                                   </p>
                                   <span>
                                        {{ translate("Choose The Amount Of Gateways Users Can Create From Each SMS Gateway Type") }}
                                   </span>
                              </label>
                         </div>
                         <div class="switch-wrapper mb-1">
                              <input class="switch-input allow_user_sms sms_gateway" 
                                   type="checkbox" 
                                   value="true" 
                                   name="sms_multi_gateway" 
                                   id="sms_gateway" 
                                   {{ $plan->sms->is_allowed == true ? 'checked' : ' ' }}>
                              <label for="sms_gateway" 
                                   class="toggle">
                                   <span></span>
                              </label>
                         </div>
                    </div>
               </div>
               <div class="col-md-6 user-sms-credit {{ $plan->sms->is_allowed == true || $plan->sms->android->is_allowed == true ? "" : "d-none" }}">
                    <div class="form-inner">
                         <label for="sms_credit_user" 
                              class="form-label"> 
                              {{ translate("SMS credit limit") }} 
                         </label>
                         <div class="input-group">
                              <input value="{{ $plan->sms->credits }}" 
                                   min="-1" 
                                   type="number" 
                                   class="form-control" 
                                   id="sms_credit_user" 
                                   name="sms_credit_user" 
                                   placeholder="{{ translate('Enter SMS Credit')}}" >
                              <span class="input-group-text fs-14" 
                                   id="sms_credit_user"> 
                                   {{ translate("Credit limit") }} 
                              </span>
                         </div>
                         <p class="form-element-note">
                              {{ translate("Set this value to -1 to allow unlimited credit spending.") }}
                         </p>
                    </div>
               </div>
               <div class="col-md-6 user-sms-per-day-credit {{ $plan->sms->is_allowed == true || $plan->sms->android->is_allowed == true ? "" : "d-none" }}">
                    <div class="form-inner">
                         <label for="sms_credit_per_day_user" 
                              class="form-label"> 
                              {{ translate("SMS per day credit limit") }} 
                         </label>
                         <div class="input-group">
                              <input type="number" 
                                   min="0" 
                                   value="{{ $plan->sms->credits_per_day ?? '0' }}" 
                                   class="form-control" 
                                   id="sms_credit_per_day_user" 
                                   name="sms_credit_per_day_user" 
                                   placeholder="{{ translate('Enter SMS Credit')}}" 
                                   aria-label="SMS Credits per day" 
                                   aria-describedby="Per day SMS credit usage number">
                              <span class="input-group-text fs-14" 
                                   id="sms_credit_per_day_user"> 
                                   {{ translate("Per Day") }} 
                              </span>
                         </div>
                         <p class="form-element-note">
                              {{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}
                         </p>
                    </div>
               </div>
               <div class="col-md-12 d-none">
                    <div class="form-inner">
                         <label for="user_android_gateway_limit" 
                              class="form-label"> 
                              {{ translate("Android Gateway Limit") }} 
                         </label>
                         <div class="input-group">
                              <input value="{{array_key_exists("gateway_limit",(array)$plan->sms->android) && $plan->type == App\Enums\StatusEnum::FALSE->status() ? $plan->sms->android->gateway_limit : "0"}}" 
                                   type="number" 
                                   class="form-control" 
                                   min="-1"
                                   id="user_android_gateway_limit" 
                                   name="user_android_gateway_limit" 
                                   placeholder="{{ translate('Users can Add upto')}}" 
                                   aria-label="Android Gateway Limit" 
                                   aria-describedby="Total number of android gateway. ">
                              <span class="input-group-text fs-14" 
                                   id="user_android_gateway_limit"> 
                                   {{ translate("Gateway limit") }}
                              </span>
                         </div>
                    </div>
               </div>
               <div class="col-md-9 sms_gateway_options {{ $plan->sms->is_allowed == true ? '' : 'd-none' }}">
                    <div class="form-inner">
                         
                         <label for="sms_gateways" 
                              class="form-label">
                              {{ translate("Select SMS Gateways") }}
                         </label>
                         <select class="form-select select2-search" 
                              data-show="5" 
                              data-placeholder="{{ translate("Choose a gateway") }}"
                              name="sms_gateway_select" 
                              id="sms_gateways">
                              <option value=""></option>
                              @foreach($sms_credentials as $sms_credential)
                                   <option value="{{($sms_credential)}}">{{preg_replace('/[[:digit:]]/','', setInputLabel($sms_credential))}}</option>
                              @endforeach
                         </select>
                    </div>
               </div>                                   
               <div class="col-md-3 newSmsdata sms_gateway_options {{ $plan->sms->is_allowed == true ? '' : 'd-none' }}">
                    <button class="i-btn btn--primary btn--md w-100 mt-md-4" 
                         type="button">
                         <i class="ri-add-fill fs-18 "></i> 
                         {{ translate("Add new") }} 
                    </button>
               </div>
               <div class="oldSmsData sms_gateway_options {{ $plan->sms->is_allowed == true ? '' : 'd-none' }}">

                    @if($sms_gateways && $plan->type == App\Enums\StatusEnum::FALSE->status() && $plan->sms->is_allowed == true)

                         @foreach($sms_gateways as $sms_key => $sms_value)
                         
                              <div class="row newSmsdata mt-3">
                                   <div class="mb-2 col-lg-5">
                                        <input readonly="true" 
                                             name="sms_gateways[]" 
                                             class="form-control" 
                                             value="{{ $sms_key }}" 
                                             type="text" 
                                             placeholder="{{ strtoupper($sms_key) }}">
                                   </div>
                                   <div class="mb-2 col-lg-5">
                                        <input name="total_sms_gateway[]" 
                                             class="form-control" 
                                             value="{{ $sms_value }}" 
                                             type="number" 
                                             placeholder="{{ translate('Total Gateways')}}">
                                   </div>
                                   <div class="col-lg-2 text-end">
                                        <span class="input-group-btn">
                                             <button class="i-btn btn--danger btn--sm removeSmsBtn" 
                                                  type="button">
                                                  <i class="ri-delete-bin-2-line"></i>
                                             </button>
                                        </span>
                                   </div>
                              </div>
                         @endforeach
                    @endif
               </div>	
               <div class="newSmsDataAdd sms_gateway_options {{ $plan->sms->is_allowed == true ? '' : 'd-none' }}"></div>
          </div>
       </div>
     </div>
     <div class="row">
       <div class="col-xxl-10">
         <div class="form-action justify-content-between">
           <button type="button" 
               class="i-btn btn--dark outline btn--md step-back-btn-user"> 
               {{ translate("Previous") }} 
          </button>
           <button type="button" 
               class="i-btn btn--primary btn--md step-next-btn-user"> 
               {{ translate("Next") }} 
          </button>
         </div>
       </div>
     </div>
 </div>