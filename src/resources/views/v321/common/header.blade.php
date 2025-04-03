<div class="page-header">
     <div class="page-header-left">
       <h2>{{ $title }}</h2>
          <div class="breadcrumb-wrapper">
               <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item">
                              <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                         </li>
                         <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                    </ol>
               </nav>
          </div>
     </div>
</div>