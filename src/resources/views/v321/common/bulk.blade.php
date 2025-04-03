<div class="card-header">
     <div class="card-header-left">
         <h4 class="card-title">{{ translate("Contacts") }}</h4>
     </div>
     <div class="card-header-right">
         <div class="d-flex gap-3 align-item-center">
             <button class="bulk-action i-btn btn--danger outline btn--sm bulk-delete-btn d-none">
                 <i class="ri-delete-bin-6-line"></i>
             </button>

             <div class="bulk-action form-inner d-none">
                 <select class="form-select" data-show="5" id="bulk_status" name="status" placeholder="{{translate('Select a Status')}}">
                     <option disabled selected>{{ translate("Select a status") }}</option>
                     <option value="{{ \App\Enums\Common\Status::ACTIVE->value }}">{{ translate("Active") }}</option>
                     <option value="{{ \App\Enums\Common\Status::INACTIVE->value }}">{{ translate("Inactive") }}</option>
                 </select>
             </div>

             <div class="card-header-right">
                 <button class="i-btn btn--primary btn--sm add-contact-group" type="button" data-bs-toggle="modal" data-bs-target="#addContactGroup">
                     <i class="ri-add-fill fs-16"></i> {{ translate("Add Group") }}
                 </button>
             </div>
         </div>
     </div>
 </div>