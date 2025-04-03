<div class="step-content-item-admin active">
     <div class="row gy-4">
         <div class="col-xxl-2 col-xl-3">
           <h5 class="form-element-title accessibility">
             {{ translate("SMS(Admin)") }}
           </h5>
         </div>
         <div class="col-xxl-8 col-xl-9">
           <div class="row gy-4 gx-xl-5">
               <div class="col-md-6">
                 <div class="form-inner-switch">
                     <div>
                       <label for="allow_admin_sms">
                           <p class="fs-16 mb-3">
                             {{ translate("Admin's SMS Gateways") }}
                           </p>
                           <span>
                             {{ translate("Enable users to use Admin's SMS Gateways") }}
                           </span>
                       </label>
                     </div>
                     <div class="switch-wrapper mb-1">
                       <input class="switch-input allow_admin_sms" 
                         type="checkbox" 
                         value="true" 
                         name="allow_admin_sms" 
                         id="allow_admin_sms" 
                         {{$plan->sms->is_allowed == true ? "checked" : ""}}>
                       <label for="allow_admin_sms" 
                         class="toggle">
                           <span></span>
                       </label>
                     </div>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-inner-switch">
                     <div>
                       <label for="allow_admin_android">
                           <p class="fs-16 mb-3">
                             {{ translate("Admin's Android Gateways") }}
                           </p>
                           <span>
                             {{ translate("Enable users to use Admin's Android Gateways") }}
                           </span>
                       </label>
                     </div>
                     <div class="switch-wrapper mb-1">
                       <input class="switch-input allow_admin_sms" 
                         type="checkbox" 
                         value="true" 
                         name="allow_admin_android"
                         id="allow_admin_android" 
                         {{ $plan->sms->android->is_allowed == true ? "checked" : "" }}>
                       <label for="allow_admin_android" 
                         class="toggle">
                           <span></span>
                       </label>
                     </div>
                 </div>
               </div>
               <div class="col-md-6 admin-sms-credit">
                 <div class="form-inner">
                     <label for="sms_credit_admin" 
                       class="form-label"> 
                       {{ translate("SMS credit limit") }} 
                     </label>
                     <div class="input-group">
                       <input type="number" 
                         min="-1" 
                         class="form-control" 
                         id="sms_credit_admin" 
                         name="sms_credit_admin" 
                         placeholder="{{ translate('Enter SMS Credit')}}" 
                         aria-label="Recipient's username" 
                         aria-describedby="basic-addon2" 
                         value="{{ $plan->sms->credits }}">
                       <span class="input-group-text fs-14" 
                         id="sms_credit_admin"> 
                         {{ translate("Credit limit") }} 
                       </span>
                     </div>
                     <p class="form-element-note">
                       {{ translate("Set this value to -1 to allow unlimited credit spending.") }}
                     </p>
                 </div>
               </div>
               <div class="col-md-6 admin-sms-per-day-credit">
                 <div class="form-inner">
                     <label for="sms_credit_per_day_admin" 
                       class="form-label"> 
                       {{ translate("SMS per day credit limit") }} 
                     </label>
                     <div class="input-group">
                       <input type="number" 
                         min="0" 
                         class="form-control" 
                         id="sms_credit_per_day_admin" 
                         name="sms_credit_per_day_admin" 
                         placeholder="{{ translate('Enter SMS Credit')}}" 
                         aria-label="SMS Credits per day" 
                         aria-describedby="basic-addon2" 
                         value="{{ $plan->sms->credits_per_day ?? '0' }}">
                       <span class="input-group-text fs-14" 
                         id="sms_credit_per_day_admin"> 
                         {{ translate("Per Day") }} 
                       </span>
                     </div>
                     <p class="form-element-note">
                       {{ translate("Set this value to 0 to allow unlimited credit spending per day.") }}
                     </p>
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