<div class="step-content-item-admin">
     <div class="row gy-4">
         <div class="col-xxl-2 col-xl-3">
         <h5 class="form-element-title">{{ translate("Whatsapp (Admin)") }}</h5>
         </div>
         <div class="col-xxl-8 col-xl-9">
         <div class="row gy-4 gx-xl-5">
             <div class="col-md-12">
             <div class="form-inner-switch">
                 <div>
                 <label for="allow_admin_whatsapp">
                     <p class="fs-16 mb-3"> {{ translate("Allow users to add Whatsapp Devices") }} 
                       <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{translate("WhatsApp Cloud API does not have any effect from this option")}}"><i class="ri-information-line"></i></span>
                     </p>
                     <span>{{ translate("Enable unlimited Devices if you set 'Whatsapp Device Limit' value to '-1'") }}</span>
                 </label>
                 
                 </div>
                 <div class="switch-wrapper mb-1">
                 <input type="checkbox" class="switch-input allow_admin_whatsapp" value="true" name="allow_admin_whatsapp" id="allow_admin_whatsapp" {{ $plan->whatsapp->is_allowed == true ? "checked" : "" }}/>
                 <label for="allow_admin_whatsapp" class="toggle">
                     <span></span>
                 </label>
                 </div>
             </div>
             </div>
             <div class="col-md-12 d-none">
               <div class="form-inner">
                   <label for="whatsapp_device_limit" class="form-label"> {{ translate('Whatsapp Device Limit')}}  </label>
                   <div class="input-group">
                   <input type="number" class="form-control" min="-1" id="whatsapp_device_limit" name="whatsapp_device_limit" placeholder="{{ translate('Users can Add upto')}}" aria-label="Whatsapp Device Limit" aria-describedby="basic-addon2" value="{{ $plan->whatsapp->gateway_limit ?? '0' }}">
                   <span class="input-group-text fs-14" id="whatsapp_device_limit"> {{ translate("Device limit") }} </span>
                   </div>
               </div>
             </div>
             <div class="col-md-6 admin-whatsapp-credit">
               <div class="form-inner">
                   <label for="whatsapp_credit_admin" class="form-label"> {{ translate('Whatsapp Credit Limit')}} </label>
                   <div class="input-group">
                   <input type="number" min="-1" class="form-control" id="whatsapp_credit_admin" name="whatsapp_credit_admin" placeholder="{{ translate('Enter Whatsapp Credit')}}" aria-label="{{ translate("WhatsApp Credit Count") }}" aria-describedby="basic-addon2" value="{{ $plan->whatsapp->credits }}">
                   <span class="input-group-text fs-14" id="whatsapp_credit_admin"> {{ translate("Credit limit") }} </span>
                   </div>
                   <p class="form-element-note">{{ translate("Set this value to -1 to allow unlimited credit spending.") }}</p>
               </div>
             </div>
             <div class="col-md-6 admin-whatsapp-per-day-credit">
               <div class="form-inner">
                   <label for="whatsapp_credit_per_day_admin" class="form-label"> {{ translate("Whatsapp per day credit limit") }} </label>
                   <div class="input-group">
                   <input type="number" min="0" class="form-control" id="whatsapp_credit_per_day_admin" name="whatsapp_credit_per_day_admin" placeholder="{{ translate('Enter WhatsApp Credit')}}" aria-label="WhatsApp Credits per day" aria-describedby="basic-addon2" value="{{ $plan->whatsapp->credits_per_day ?? '0' }}">
                   <span class="input-group-text fs-14" id="whatsapp_credit_per_day_admin"> {{ translate("Per Day") }} </span>
                   
                   </div>
                   <p class="form-element-note">{{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}</p>
               </div>
             </div>
         </div>
         </div>
     </div>
     <div class="row">
         <div class="col-xxl-10">
         <div class="form-action justify-content-between">
             <button type="button" class="i-btn btn--dark outline btn--md step-back-btn-admin"> {{ translate("Previous") }} </button>
             <button type="button" class="i-btn btn--primary btn--md step-next-btn-admin"> {{ translate("Next") }} </button>
         </div>
         </div>
     </div>
 </div>