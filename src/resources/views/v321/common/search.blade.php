<div class="table-filter mb-4">
     <form action="{{route(Route::currentRouteName())}}" class="filter-form">
         <div class="row g-3">
             <div class="col-lg-3">
                 <div class="filter-search">
                     <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
                     <span><i class="ri-search-line"></i></span>
                 </div>
             </div>
             <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                 <div class="filter-action">
                     <select data-placeholder="{{translate('Select A Status')}}" class="form-select select2-search" name="status" aria-label="Default select example">
                         <option value=""></option>
                         <option {{ request()->status == \App\Enums\Common\Status::ACTIVE->value ? 'selected' : ''  }} value="{{ \App\Enums\Common\Status::ACTIVE->value }}">{{ translate("Active") }}</option>
                         <option {{ request()->status == \App\Enums\Common\Status::INACTIVE->value  ? 'selected' : ''  }} value="{{ \App\Enums\Common\Status::INACTIVE->value  }}">{{ translate("Inactive") }}</option>
                     </select>
                     <div class="input-group">
                         <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                         <span class="input-group-text" id="filterByDate">
                             <i class="ri-calendar-2-line"></i>
                         </span>
                     </div>
                     <button type="submit" class="filter-action-btn ">
                         <i class="ri-menu-search-line"></i> {{ translate("Filter") }}
                     </button>
                     <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                         <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                     </a>
                 </div>
             </div>
         </div>
     </form>
 </div>