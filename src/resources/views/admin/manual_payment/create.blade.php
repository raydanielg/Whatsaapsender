@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('admin.layouts.app')
@section('panel')
<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ $title}}</h2>
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
			<div class="form-header">
				<h4 class="card-title">{{ $title }}</h4>
			</div>
            <div class="card-body pt-0">
                <form action="{{route('admin.payment.store')}}" method="POST" enctype="multipart/form-data">
					@csrf

                    <div class="form-element">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("General Configuration") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
									<div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="name" class="form-label"> {{ translate("Payment Gateway Name") }} <small class="text-danger">*</small></label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="{{ translate('Enter percentage charge amount') }}" aria-label="{{ translate('Enter payment gateway name') }}"/>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="percent_charge" class="form-label"> {{ translate("Percentage Charge") }} <small class="text-danger">*</small></label>
                                            <input type="number" id="percent_charge" name="percent_charge" class="form-control" placeholder="{{ translate('Enter percentage charge amount') }}" aria-label="{{ translate('Enter percentage charge amount') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="currency_code" class="form-label">{{ translate("Payment Gateway Currency") }}</label>
                                            <select data-placeholder="{{translate('Select a currency')}}" class="form-select select2-search" data-show="5" id="currency_code" name="currency_code">
                                                <option value=""></option>
                                                @foreach($currencies as $key => $currency)
                                                    <option data-rate_value="{{shortAmount($currency['rate'])}}" value="{{ $key }}">{{$key}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="form-inner">
                                            
                                            <label for="rate" class="form-label"> {{ translate('Currency Rate')}} <sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">{{translate("1 ").getDefaultCurrencyCode($currencies)}}</span>
                                                <input type="text" name="rate" class="method-rate form-control">
                                                <span class="input-group-text limittext"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-inner">
                                            <label for="image" class="form-label">
                                                {{translate("Payment Gateway Image")}} <small class="text-danger" >* ({{config("setting")['file_path']['automatic_payment']['size']}})</small>
                                            </label>
                                            <input class="form-control"  type="file" name="image" class="preview" data-size = "150x150">
                                            <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="form-element child">
						<div class="row gy-4">
							<div class="col-xxl-2 col-xl-3">
								<h5 class="form-element-title">{{ translate("Payment Information") }}</h5>
							</div>
							<div class="col-xxl-8 col-xl-9">
								<div class="row gy-4">
									<div class="col-md-12">
										<label for="payment_gw_info" class="form-label">{{ translate("Gateway Description") }}<sup class="text--danger">*</sup></label>
										<textarea name="payment_gw_info" id="payment_gw_info" class="form-control" placeholder="{{translate('Give payment gateway information')}}"></textarea>
										<p class="form-element-note">{{ translate("When making a payment using this gateway user will be shown this description field") }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-element">
						<div class="row gy-4">
							<div class="col-xxl-2 col-xl-3">
								<h5 class="form-element-title">{{ translate("User Information") }}</h5>
							</div>
							<div class="col-xxl-8 col-xl-9">
								<div class="row newdata-row gy-4 align-items-end">
									<div class="col-xxl-5 col-md-5">
										<div class="form-inner">
										<label for="fildName" class="form-label"> {{ translate('User Field Name')}} </label>
										<input name="field_name[]" class="form-control" type="text" placeholder="{{translate('User Field Name')}}">
										</div>
									</div>
									<div class="col-xxl-5 col-md-5 col-sm-9">
										<div class="form-inner">
										<label for="typeSelect" class="form-label"> {{ translate("Type") }} </label>
										<select name="field_type[]" class="form-control" id="typeSelect">
											<option value="text">
												{{ translate('Input Text')}}
											</option>
												<option value="file">
												{{ translate('File')}}
											</option>
											<option value="textarea">
												{{ translate('Textarea')}}
											</option>
										</select>
										</div>
									</div>
									<div class="col-xxl-2 col-md-2 col-sm-3">
										<div class="d-flex align-items-center gap-2">
										</div>
									</div>
								</div>
								<div class="newdataadd"></div>
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
	(function($){
		"use strict";
		select2_search($('.select2-search').data('placeholder'));
		$("#currency_code").on('change', function(){
			var value = $(this).find("option:selected").text();
			if(value != '') {
				$(".limittext").text(value);
			} else {
				$(".limittext").text("{{ translate('Select A Currency') }}");
			}
			
			$(".method-rate").val($('select[name=currency_code] :selected').data('rate_value'));
		}).change();
	})(jQuery);
</script>
<script>
    (function($) {
		"use strict";

		let initialFieldsHtml = '';
		let dynamicFieldsHtml = '';

		function updatePlusIcon() {
			$('.newdata-row').each(function(index) {
				$(this).find('.newData').remove();
			});
			const lastRow = $('.newdata-row').last();
			if (lastRow.length > 0) {
				lastRow.find('.d-flex').append('<button type="button" class="icon-btn btn-md primary-soft hover newData"><i class="ri-add-line"></i><span class="tooltiptext"> {{ translate("Add new") }} </span></button>');
			}
		}

		function updateDeleteIcons() {
			$('.removeBtn').show();

			if ($('.newdata-row').length <= 1) {
				$('.removeBtn').hide();
			}
		}

    	function bindEvents() {
			$(document).on('click', '.newData', function() {
				var html = `
					<div class="row newdata-row gy-4 align-items-end mt-1">
						<div class="col-xxl-5 col-md-5">
							<div class="form-inner">
								<label for="fildName" class="form-label"> {{ translate('User Field Name')}} </label>
								<input name="field_name[]" class="form-control" type="text" placeholder=" {{ translate('User Field Name')}}">
							</div>
						</div>
						<div class="col-xxl-5 col-md-5 col-sm-9">
							<div class="form-inner">
								<label for="typeSelect" class="form-label"> {{ translate("Type") }} </label>
								<select name="field_type[]" class="form-control" id="typeSelect">
									<option value="text">{{ translate('Input Text')}}</option>
									<option value="file">{{ translate('File')}}</option>
									<option value="textarea">{{ translate('Textarea')}}</option>
								</select>
							</div>
						</div>
						<div class="col-xxl-2 col-md-2 col-sm-3">
							<div class="d-flex align-items-center gap-2">
								<button type="button" class="icon-btn btn-md danger-soft hover removeBtn">
									<i class="ri-delete-bin-line"></i>
									<span class="tooltiptext"> {{ translate("Delete") }} </span>
								</button>
							</div>
						</div>
					</div>`;

				$('.newdataadd').append(html);
				updatePlusIcon();
				updateDeleteIcons();
			});

			$(document).on('click', '.removeBtn', function() {
				$(this).closest('.newdata-row').remove();
				updatePlusIcon();
				updateDeleteIcons();
			});

			$(document).on('click', 'button[type="reset"]', function() {
				$('.newdataadd').html(dynamicFieldsHtml);
				$('.existing-fields').html(initialFieldsHtml);
				updatePlusIcon();
				updateDeleteIcons();
			});
		}

		$(document).ready(function() {
			initialFieldsHtml = $('.existing-fields').html();
			dynamicFieldsHtml = $('.newdataadd').html();

			bindEvents();
			updatePlusIcon();
			updateDeleteIcons();
		});
	})(jQuery);

</script>
@endpush
