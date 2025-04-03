<div class="step-content-item-user">
     <div class="row gy-4">
       <div class="col-xxl-2 col-xl-3">
         <h5 class="form-element-title">{{ translate("Email (USER)") }}</h5>
       </div>
       <div class="col-xxl-8 col-xl-9">
         <div class="row gy-4 gx-xl-5">
         
           <div class="col-md-12">
             <div class="form-inner-switch">
               <div>
                 <label for="multi_gateway">
                     <p class="fs-16 mb-3"> {{ translate("Allow Users To Make Multiple Email Gateways") }} </p>
                     <span>{{ translate("Choose The Amount Of Gateways Users Can Create From Each Email Gateway Type") }}</span>
                 </label>
               </div>
               <div class="switch-wrapper mb-1">
                 <input {{ $plan->email->is_allowed == true ? 'checked' : ' ' }} type="checkbox" value="true" name="mail_multi_gateway" id="multi_gateway" class="switch-input multiple_gateway allow_user_email">
                 <label for="multi_gateway" class="toggle">
                   <span></span>
                 </label>
               </div>
             </div>
           </div>
           <div class="col-md-6 user-email-credit {{ $plan->email->is_allowed == false ? "d-none" : "" }}">
             <div class="form-inner">
               <label for="email_credit_user" class="form-label"> {{ translate('Email Credit Limit')}} </label>
               <div class="input-group">
                 <input type="number" min="-1" class="form-control" id="email_credit_user" name="email_credit_user" placeholder="{{ translate('Enter Email Credit')}}" aria-label="{{ translate("User Email Credits") }}" aria-describedby="basic-addon2" value="{{ $plan->email->credits }}">
                 <span class="input-group-text fs-14" id="gatewayLimit"> {{ translate("Credit limit") }} </span>
               </div>
               <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
             </div>
           </div>
           <div class="col-md-6 user-email-per-day-credit {{ $plan->email->is_allowed == false ? "d-none" : "" }}">
             <div class="form-inner">
                 <label for="email_credit_per_day_user" class="form-label"> {{ translate("Email per day credit limit") }} </label>
                 <div class="input-group">
                 <input type="number" min="0" class="form-control" id="email_credit_per_day_user" name="email_credit_per_day_user" placeholder="{{ translate('Enter Email Credit')}}" aria-label="Email Credits per day" aria-describedby="basic-addon2" value="{{ $plan->email->credits_per_day ?? '0' }}">
                 <span class="input-group-text fs-14" id="email_credit_per_day_user"> {{ translate("Per Day") }} </span>
                 
                 </div>
                 <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
             </div>
           </div>
           <div class="col-md-9 email_gateway_options {{ $plan->email->is_allowed == true ? '' : 'd-none' }}">
             <div class="form-inner">
               <label for="mail_gateways" class="form-label">{{ translate('Select Email Gateway')}}</label>
               <select class="form-select select2-search" data-show="5" data-placeholder="{{ translate("Choose a gateway") }}" name="mail_gateway_select" id="mail_gateways">
                 <option value=""></option>
                 @foreach($mail_credentials as $mail_credential)
                     <option value="{{strToLower($mail_credential)}}">{{strtoupper($mail_credential)}}</option>
                 @endforeach
             </select>
             </div>
           </div>
           <div class="col-md-3 newEmailData email_gateway_options {{ $plan->email->is_allowed == true ? '' : 'd-none' }}">
             <button class="i-btn btn--primary btn--md w-100 mt-md-4" type="button">
               <i class="ri-add-fill fs-18 "></i> {{ translate("Add new") }} </button>
           </div>
             <div class="oldEmailData email_gateway_options  {{ $plan->email->is_allowed == true ? '' : 'd-none' }}">
                 @if($mail_gateways && $plan->type == \App\Enums\StatusEnum::FALSE->status() && $plan->email->is_allowed == true)
                     @foreach($mail_gateways as $mail_key => $mail_value)
                         <div class="row newEmaildata mt-3">
                             <div class="mb-2 col-lg-5">
                                 <input type="text"  name="mail_gateways[]" class="form-control" value="{{ $mail_key }}" placeholder="{{ strtoupper($mail_key) }}" readonly="true">
                             </div>
                             <div class="mb-2 col-lg-5">
                                 <input name="total_mail_gateway[]" class="form-control" value="{{ $mail_value }}" type="number"  placeholder=" {{ translate('Total Gateways')}}">
                             </div>
                             <div class="col-lg-2 text-end">
                                 <span class="input-group-btn">
                                     <button class="i-btn btn--danger btn--sm removeEmailBtn" type="button">
                                         <i class="ri-delete-bin-2-line"></i>
                                     </button>
                                 </span>
                             </div>
                         </div>
                     @endforeach
                 @endif
             </div>				
           <div class="newEmailDataAdd email_gateway_options {{ $plan->email->is_allowed == true ? '' : 'd-none' }}"></div>
           
         </div>
       </div>
     </div>
     <div class="row">
       <div class="col-xxl-10">
         <div class="form-action justify-content-between">
           <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-user"> {{ translate("Previous") }} </button>
           <button type="button" class="i-btn btn--primary btn--md step-next-btn-user"> {{ translate("Next") }} </button>
           <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
         </div>
       </div>
     </div>
 </div>