@extends('admin.layouts.app')
@section('panel')
    <section>
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{translate('Login Page Content')}}</h4>
                </div>
                <form action="{{route('admin.general.setting.frontend.section.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="card-body">
                    <div class="form-wrapper">
                        <h4 class="form-wrapper-title mb-4">{{translate('Admin Section')}}</h4>
                        <div class="row g-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-item">
                                        <label for="admin_bg" class="form-label">{{translate('Background Image')}}</label><sup class="text--danger">*</sup></label>
                                        <input type="file" name="admin_bg" id="admin_bg" class="form-control">
                                    </div>
                                </div>
                                <div class=" col-lg-6">
                                    <div class="form-item">
                                        <label for="admin_card" class="form-label">{{translate('Card Image')}}</label>
                                        <input type="file" name="admin_card" id="admin_card" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-wrapper">
                        <h4 class="form-wrapper-title mb-4">{{translate('User Section')}}</h4>
                        <div class="row g-4">
                            <div class="row">
                                <div class="mb-3 col-lg-12">
                                    <label for="heading" class="form-label">{{ translate('Heading')}} <sup class="text--danger">*</sup></label>
                                    <input type="text" name="heading" id="heading" class="form-control" value="{{@$general->frontend_section->heading}}" placeholder="{{ translate('Enter Heading')}}" required>
                                </div>

                                <div class="mb-3 col-lg-12">
                                    <label for="sub_heading" class="form-label">{{ translate('Sub Heading')}} <sup class="text--danger">*</sup></label>
                                    <textarea class="form-control" id="sub_heading" name="sub_heading" rows="5" placeholder="{{ translate('Enter Sub Heading')}}" required="">{{@$general->frontend_section->sub_heading}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="i-btn primary--btn btn--lg">{{translate('Submit')}}</button>
                </form>
                </div>
			</div>
        </div>
    </section>
@endsection
