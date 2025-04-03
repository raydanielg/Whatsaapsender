@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
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
        <div class="card">
            <div class="card-body pt-0">
                <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                    @csrf
					<div class="form-element">
						<div class="row gy-4">
						  <div class="col-xxl-2 col-xl-3">
							<h5 class="form-element-title">{{ translate("Meta details") }}</h5>
						  </div>
						  <div class="col-xxl-8 col-xl-9">
							<div class="row gy-4">
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="meta_title" class="form-label"> {{ translate("Meta Title") }} <small class="text-danger">*</small></label>
								  <input type="text" id="meta_title" name="site_settings[meta_title]" class="form-control" placeholder="{{ translate('Enter meta title') }}" aria-label="{{ translate('Meta Title') }}" value="{{ site_settings("meta_title") }}"/>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
									<label for="meta_keywords" class="form-label">{{ translate("Meta Keywords") }}</label>
									<select data-placeholder="{{translate('Select Meta Keywords')}}" class="form-select select2-search" data-show="5" name="site_settings[meta_keywords][]"  id="meta_keywords" multiple>
										<option value=""></option>
										@foreach(json_decode(site_settings('meta_keywords'), true) as $file_type)
											<option {{in_array($file_type, json_decode(site_settings("meta_keywords"), true)) ? "selected" :"" }} value="{{$file_type}}">
												{{$file_type}}
											</option>
										@endforeach
									</select>
								</div>
							  </div>
							  <div class="col-md-12">
								<div class="form-inner">
								  <label for="meta_description" class="form-label"> {{ translate("Meta Description") }} </label>
								  <textarea class="form-control" name="site_settings[meta_description]" id="meta_description" rows="2" placeholder="{{ translate('Enter meta description') }}" aria-label="{{ translate('Meta Description') }}">{{ site_settings("meta_description") }}</textarea>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					</div>

                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                            <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
@endpush
@push('script-push')
    <script>
        "use strict";
		select2_search($('.select2-search').data('placeholder'), null, true);
        $(document).ready(function() {
           
        });
    </script>
@endpush
