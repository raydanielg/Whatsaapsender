<div class="modal fade actionModal" id="deleteContactGroup" tabindex="-1" aria-labelledby="deleteContactGroup" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered ">
         <div class="modal-content">
         <div class="modal-header text-start">
             <span class="action-icon danger">
             <i class="bi bi-exclamation-circle"></i>
             </span>
         </div>
         <form action="#" method="POST" id="singleDeleteModal">
             @csrf
             <input type="hidden" name="_method" value="DELETE">
             <div class="modal-body">
                 <div class="action-message" id="singleDeleteModalMessage">
                     <h5>{{Illuminate\Support\Arr::get($deleteModalData, 'message')}}</h5>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                 <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Delete") }} </button>
             </div>
         </form>
         </div>
     </div>
 </div>