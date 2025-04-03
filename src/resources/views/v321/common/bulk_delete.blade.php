<div class="modal fade actionModal" id="bulkAction" tabindex="-1" aria-labelledby="bulkAction" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered ">
         <div class="modal-content">
         <div class="modal-header text-start">
             <span class="action-icon danger">
             <i class="bi bi-exclamation-circle"></i>
             </span>
         </div>
         <form action="{{Illuminate\Support\Arr::get($bulkDeleteModalData, 'url')}}" method="POST" enctype="multipart/form-data">
             @csrf
             <div class="modal-body">
                 <input type="hidden" name="id" value="">
                 <div class="action-message">
                     <h5>{{Illuminate\Support\Arr::get($bulkDeleteModalData, 'title')}}</h5>
                     <p>{{Illuminate\Support\Arr::get($bulkDeleteModalData, 'message')}}</p>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                 <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Proceed") }} </button>
             </div>
         </form>
         </div>
     </div>
 </div>