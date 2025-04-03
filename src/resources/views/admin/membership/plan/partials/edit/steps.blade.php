<div class="form-element">
     <div class="admin-items d-none">
         <div class="step-wrapper-admin justify-content-center">
             <ul class="progress-steps-admin">
               <li class="step-item-admin activated active">
                 <span>{{ translate("01") }}</span> {{ translate("SMS") }}
               </li>
               <li class="step-item-admin">
                 <span>{{ translate("02") }}</span> {{ translate("WhatsApp") }}
               </li>
               <li class="step-item-admin">
                 <span>{{ translate("03") }}</span> {{ translate("Email") }}
               </li>
             </ul>
         </div>
         <div class="step-content-admin">
               @include('admin.membership.plan.partials.edit.step.admin.sms', ['plan' => $plan])
               @include('admin.membership.plan.partials.edit.step.admin.whatsapp', ['plan' => $plan])
               @include('admin.membership.plan.partials.edit.step.admin.email', ['plan' => $plan])
         </div>
     </div>
     <div class="user-items">
         <div class="step-wrapper-user justify-content-center">
             <ul class="progress-steps-user">
               <li class="step-item-user activated active">
                 <span>{{ translate("01") }}</span> {{ translate("SMS") }}
               </li>
               <li class="step-item-user">
                 <span>{{ translate("02") }}</span> {{ translate("WhatsApp") }}
               </li>
               <li class="step-item-user">
                 <span>{{ translate("03") }}</span> {{ translate("Email") }}
               </li>
             </ul>
         </div>
         <div class="step-content-user">
               @include('admin.membership.plan.partials.edit.step.user.sms', ['plan' => $plan])
               @include('admin.membership.plan.partials.edit.step.user.whatsapp', ['plan' => $plan])
               @include('admin.membership.plan.partials.edit.step.user.email', ['plan' => $plan])
         </div>
     </div>
   </div>