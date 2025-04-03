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
		<div class="table-filter mb-4">
			<form action="{{route(Route::currentRouteName())}}" class="filter-form">
				
				<div class="row g-3">
					<div class="col-xxl-3 col-lg-4">
						<div class="filter-search">
							<input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
							<span><i class="ri-search-line"></i></span>
						</div>
					</div>

					<div class="col-xxl-6 col-lg-8 offset-xxl-3">
						<div class="filter-action">
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
		<div class="card">
			<div class="card-header">
				<div class="card-header-left">
					<h4 class="card-title">{{ translate("User List") }}</h4>
				</div>
				@if(request()->routeIs('admin.payment.manual.index'))
					<div class="card-header-right">
						<a href="{{route('admin.payment.create')}}" class="i-btn btn--primary btn--sm">
							<i class="ri-add-fill fs-16"></i> {{ translate("Add Method") }}
						</a>
					</div>
				@endif
			</div>
			<div class="card-body px-0 pt-0">
				<div class="table-container">
					<table>
						<thead>
							<tr>
								<th scope="col">{{ translate("Name") }}</th>
								<th scope="col">{{ translate("Image") }}</th>
								<th scope="col">{{ translate("Status") }}</th>
								<th scope="col">{{ translate("Option") }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($payment_methods as $payment_method)
								<tr>
									<td>
										<p class="text-dark fw-semibold">{{ $payment_method->name }}</p>
										<p>{{ translate("Updated At: ") }}<span>{{ $payment_method?->updated_at->toDayDateTimeString()}}</span></p>
									</td>
									<td>
										<span class="payment-logo">
											@if(substr($payment_method->unique_code,0,6) == "MANUAL")
												<img src="{{showImage(config('setting.file_path.manual_payment.path').'/'.$payment_method->image, config('setting.file_path.manual_payment.size'))}}" class="automatic-payment-logo">
											@else
												<img src="{{showImage(config('setting.file_path.automatic_payment.path').'/'.$payment_method->image, config('setting.file_path.automatic_payment.size'))}}" class="automatic-payment-logo">
											@endif
										</span>
									</td>
									<td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $payment_method->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input statusUpdate"
                                                    data-id="{{ $payment_method->id }}"
                                                    data-column="status"
                                                    data-value="{{ $payment_method->status != "" || $payment_method->status ? $payment_method->status : \App\Enums\StatusEnum::TRUE->status() }}"
                                                    data-route="{{route('admin.payment.status.update')}}"
                                                    id="{{ 'status_'.$payment_method->id }}"
                                                    name="is_default"/>
                                            <label for="{{ 'status_'.$payment_method->id }}" class="toggle">
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
									<td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
											@if(substr($payment_method->unique_code,0,6) == "MANUAL")
											<a class = "icon-btn btn-ghost btn-sm success-soft circle"
												type = "button"
												href = "{{route('admin.payment.edit', $payment_method->id)}}">
												<i class="ri-edit-line"></i>
												<span class="tooltiptext"> {{ translate("Edit Payment Method") }} </span>
											</a>
											<button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-manual-payment"
                                                    type="button"
                                                    data-manual-payment-id="{{ $payment_method->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteManualPayment">
                                                <i class="ri-delete-bin-line"></i>
                                                <span class="tooltiptext"> {{ translate("Delete Manual Payment") }} </span>
                                            </button>
											@else
												<a  class = "icon-btn btn-ghost btn-sm success-soft circle"
													type  = "button"
													href  = "{{route('admin.payment.edit', [$payment_method->id, slug($payment_method->name)])}}">
													<i class="ri-edit-line"></i>
													<span class="tooltiptext"> {{ translate("Edit Payment Method") }} </span>
												</a>

												<button class="icon-btn btn-ghost btn-sm info-soft circle text-info quick-view"
														type="button"
														data-payment_currency_code="{{ $payment_method->currency_code }}"
														data-payment_percent_charge="{{ $payment_method->percent_charge }}"
														data-payment_parameter="{{ json_encode($payment_method->payment_parameter) }}"
														data-payment_rate="{{ translate('(per '). getDefaultCurrencyCode($currencies).') '. $payment_method->rate. ' '. $payment_method->currency_code}}"
														data-bs-toggle="modal"
														data-bs-target="#quick_view">
														<i class="ri-information-line"></i>
													<span class="tooltiptext"> {{ translate("Quick View") }} </span>
												</button>
											@endif
                                        </div>
                                    </td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@include('admin.partials.pagination', ['paginator' => $payment_methods])
			</div>
		</div>
	</div>
</main>
@endsection
@section("modal")
<div class="modal fade" id="quick_view" tabindex="-1" aria-labelledby="quick_view" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"> {{ translate("Payment Gateway Information") }} </h5>
				<button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
					<i class="ri-close-large-line"></i>
				</button>
			</div>
			<div class="modal-body modal-lg-custom-height">
				<div class="row g-4">
					<div class="col-md-12 mb-2">
						<div class="form-inner">
							<p><span class="text-dark fw-semibold information-key"></span><span class="Information-value"></span></p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<div class="modal fade actionModal" id="deleteManualPayment" tabindex="-1" aria-labelledby="deleteManualPayment" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.payment.delete')}}" method="POST">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this manual payment method?") }}</h5>
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
@endsection
@push('script-push')
    <script>
        (function($){
            "use strict";

			$('.quick-view').on('click', function() {

				const modal = $('#quick_view');
				const modalBody = modal.find('.modal-body');
				modalBody.empty();
				const dataAttributes = $(this).data();

				for (const [key, value] of Object.entries(dataAttributes)) {

					if (key === 'payment_parameter') {
						const paymentParameters = value
						const parameterDiv = $('<div>').addClass('form-inner');

						for (const [paramKey, paramValue] of Object.entries(paymentParameters)) {
							const paramKeySpan = $('<span>').addClass('text-dark fw-semibold information-key').text(`${textFormat(['_'], paramKey, ' ')}: `);
							const paramValueSpan = $('<span>').addClass('information-value text-break').text(paramValue);
							const paramRow = $('<div>').addClass('row g-4').append($('<div>').addClass('col-md-12').append(paramKeySpan, paramValueSpan));
							parameterDiv.append(paramRow);
						}

						modalBody.append(parameterDiv);
					} else if (key !== 'bsTarget' && key !== 'bsToggle') {

						const keySpan = $('<span>').addClass('text-dark fw-semibold information-key').text(`${textFormat(['_'], key, ' ')}: `);
						const valueSpan = $('<span>').addClass('information-value text-break').text(value);
						const row = $('<div>').addClass('row g-4').append($('<div>').addClass('col-md-12').append(keySpan, valueSpan));
						modalBody.append(row);
					}
				}

				modal.modal('show');
			});
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                mode: "range",
            });

			$('.delete-manual-payment').on('click', function() {

				const modal = $('#deleteManualPayment');
				modal.find('input[name=id]').val($(this).data('manual-payment-id'));
				modal.modal('show');
			});

        })(jQuery);
    </script>
@endpush
