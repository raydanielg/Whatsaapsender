@extends('update.master')
@section('content')
<section>
    <form action="{{ route('admin.update.verify.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card p-4">
            <div class="card-body">
                <div class="row">
                    <div class="mb-5 p-4 text-center  d-flex  align-items-center justify-content-center flex-column position-relative gap-2">
                        <div class="update-logo">
                            @if(site_settings("theme_mode") == (string)\App\Enums\StatusEnum::FALSE->status())
                                <img src="{{showImage("assets/file/default/xsender_light.png")}}" alt="{{translate('Site Logo')}}">
                            @else 
                                <img src="{{showImage("assets/file/default/xsender_dark.png")}}" alt="{{translate('Site Logo')}}">
                            @endif
                        </div>
                        <h6 class="text-dark version-text">{{translate("In order to finalize the update please verify yourself ")}}</h6>
                        
                    </div> 
                </div>
            
                <div class="row gx-0 gy-5 update-content">
                    <div class="form-wrapper mb-0">
                        
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="envato_purchase_key" class="form-label">{{ translate('Envato Purchase Key')}}<sup class="text-danger">*</sup></label>
                                <input type="envato_purchase_key" class="form-control" id="envato_purchase_key" placeholder="{{ translate('Enter the key')}}" name="purchased_code" required="">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="admin_username" class="form-label">{{ translate('Admin Username')}}<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="admin_username"  name="username" placeholder="{{ translate('Enter Admin Username')}}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="admin_password" class="form-label">{{ translate('Admin Password')}}<sup class="text-danger">*</sup></label>
                                <input type="password" class="form-control" id="admin_password"  name="password" placeholder="{{ translate('Enter Admin Password')}}" aria-describedby="emailHelp">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex align-items-center justify-content-center text-end mt-5">
                <button type="submit" class="i-btn btn--primary btn--md update-btn">{{ translate('Submit')}}</button>
            </div>
        </div>
    </form>
</section>
@endsection