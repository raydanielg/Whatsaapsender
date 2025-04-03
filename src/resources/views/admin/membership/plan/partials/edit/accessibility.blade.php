<div class="form-element">
     <div class="row gy-4">
       <div class="col-xxl-2 col-xl-3">
         <h5 class="form-element-title">
           {{ translate("Accessibility") }}
         </h5>
       </div>
       <div class="col-xxl-8 col-xl-9">
         <div class="row gy-4 gx-xl-5">
             <div class="col-12">
                 <div class="form-inner">
                   <div class="form-inner-switch">
                     <label class="pointer" 
                         for="allow_admin_creds">
                         {{ translate("Allow users to use Admin Gateways or Devices") }}
                     </label>
                     <div class="switch-wrapper mb-1">
                       <input type="checkbox" 
                         class="switch-input" 
                         id="allow_admin_creds" 
                         name="allow_admin_creds" 
                         value="{{ $plan->type == \App\Enums\StatusEnum::FALSE->status() ? "true" : "false" }}"
                         {{ $plan->type == \App\Enums\StatusEnum::TRUE->status() ? "checked" : "" }}/>
                       <label for="allow_admin_creds" 
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