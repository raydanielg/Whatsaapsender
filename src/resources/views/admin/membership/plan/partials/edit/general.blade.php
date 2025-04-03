<div class="form-element">
     <div class="row gy-4">
       <div class="col-xxl-2 col-xl-3">
         <h5 class="form-element-title">
           {{ translate("Basic Informations") }}
         </h5>
       </div>
       <div class="col-xxl-8 col-xl-9">
         <div class="row gy-4 gx-xl-5">
           <div class="col-md-6">
             <div class="form-inner">
               <label for="name" 
                 class="form-label">
                 {{ translate("Plan Name") }}
                 <span class="text-danger">*</span>
               </label>
               <input type="text" 
                 id="name" 
                 class="form-control" 
                 placeholder="{{ translate("Enter membership plan name") }}" 
                 name="name" 
                 aria-label="name" 
                 value="{{ $plan->name }}"/>
             </div>
           </div>
           <div class="col-md-6">
             <div class="form-inner">
               <label for="duration" 
                 class="form-label">
                 {{ translate("Plan Duration") }}
               </label>
               <div class="input-group">
                 <input type="number" 
                    min="0"
                   name="duration" 
                   id="duration" 
                   class="form-control" 
                   placeholder="{{ translate("Enter membership plan duration") }}" 
                   value="{{ $plan->duration }}"/>
                 <span id="reset-primary-color" 
                   class="input-group-text" 
                   role="button"> {{ translate("Days") }} 
                 </span>
               </div>
             </div>
           </div>
           <div class="col-12">
             <div class="form-inner">
               <label for="description" 
                 class="form-label">
                 {{ translate("Plan Description") }}
               </label>
               <textarea type="text" 
                 name="description" 
                 id="description" 
                 class="form-control" 
                 placeholder="{{ translate("Write description for the membership plan") }}" 
                 aria-label="description">{{ $plan->description }}</textarea>
             </div>
           </div>
           <div class="col-md-6">
             <div class="form-inner">
               <label for="amount" 
                 class="form-label"> 
                 {{ translate("Amount") }} 
               </label>
               <div class="input-group">
                 <input type="text" 
                   id="amount" 
                   step="0.01" 
                   min="0"
                   class="form-control" 
                   placeholder="{{ translate("Enter membership plan price") }}" 
                   aria-label="amount" 
                   name="amount" 
                   value="{{ $plan->amount }}"/>
                 <span id="reset-primary-color" 
                   class="input-group-text" 
                   role="button"> 
                   {{ getDefaultCurrencyCode(json_decode(site_settings('currencies'), true)) }} 
                 </span>
               </div>
             </div>
           </div>
           <div class="col-6">
             <div class="form-inner">
               <label class="form-label"> 
                 {{ translate("Carry forward") }} 
               </label>
               <div class="form-inner-switch">
                 <label class="pointer" 
                   for="allow_carry_forward">
                   {{ translate("Turn on/off pricing plan carry forward") }}
                 </label>
                 <div class="switch-wrapper mb-1">
                   <input type="checkbox" 
                     class="switch-input" 
                     id="allow_carry_forward" 
                     name="allow_carry_forward" 
                     value="true" 
                     {{ $plan->carry_forward == \App\Enums\StatusEnum::TRUE->status() ? "checked" : ""}}/>
                   <label for="allow_carry_forward" 
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