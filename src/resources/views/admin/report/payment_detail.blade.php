@extends('admin.layouts.app')
@section('panel')

	<main class="main-body">
		<div class="container-fluid px-0 main-content">
		<div class="page-header">
			<div class="page-header-left">
			<h2>{{ translate("Customer Information") }}</h2>
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

		<div class="row g-4">
			<div class="col-xl-4 col-lg-6">
			<div class="card h-100">
				<div class="form-header">
				<h4 class="card-title">{{ translate('Member information')}}</h4>
				</div>
				<div class="card-body">
				<div class="ul-list">
					<ul>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Member Name')}}</span>
						<span class="fw-medium">{{$paymentLog->user?->name}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Payment Method')}}</span>
						<span class="fw-medium">{{$paymentLog->paymentGateway?->name}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Date')}}</span>
						<span class="fw-medium">{{getDateTime($paymentLog->created_at)}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Amount')}}</span>
						<span class="fw-semi-bold">{{shortAmount($paymentLog->amount)}} {{getDefaultCurrencyCode(json_decode(site_settings("currencies"), true))}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Charge')}}</span>
						<span class="fw-medium">{{shortAmount($paymentLog->charge)}} {{getDefaultCurrencyCode(json_decode(site_settings("currencies"), true))}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted">{{ translate('Receivable')}}</span>
						<span class="fw-medium">{{shortAmount($paymentLog->final_amount)}} {{getDefaultCurrencyCode(json_decode(site_settings("currencies"), true))}}</span>
					</li>
					<li class="fs-14">
						<span class="text-muted"> {{ translate('Status')}}</span>
						@if($paymentLog->status == 1)
							<span class="i-badge dot primary-soft pill">{{ translate('Pending')}}</span>
						@elseif($paymentLog->status == 2)
							<span class="i-badge dot success-soft pill">{{ translate('Received')}}</span>
						@elseif($paymentLog->status == 3)
							<span class="i-badge dot danger-soft pill">{{ translate('Rejected')}}</span>
						@endif
					</li>
					</ul>
				</div>
				</div>
			</div>
			</div>

			@if($paymentLog->user_data != null)
				<div class="col-xl-4 col-lg-6">
				<div class="card h-100">
					<div class="form-header">
					<h4 class="card-title">{{ translate("Member Data") }}</h4>
					</div>
					<div class="card-body">
						@foreach($paymentLog->user_data as $k => $val)
							@if($val->field_type != 'file')
								<div class="mb-4">
									<label class="form-label">{{labelName($k)}}</label>
									<div class="bg-light rounded-2 p-2 fs-14 text-muted border">
										<p>{{$val->field_name}}</p>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				</div>
				</div>
				<div class="col-xl-4 col-lg-6">
					<div class="card h-100">
						<div class="form-header">
							<h4 class="card-title">{{ translate("User Files") }}</h4>
						</div>
						<div class="card-body">
							<div class="row g-4">
								@foreach($paymentLog->user_data as $k => $val)
									@if($val->field_type == 'file')
									<label class="form-label">{{labelName($k)}}</label>
										<div class="col-lg-4">
											<img src="{{showImage('assets/file/payment/data/'.$val->field_name)}}" class="mt-1" alt="{{ translate('Image')}}">
									</div>
									@endif
								@endforeach
								@if($paymentLog->status == 1)
								<div class="col-12">
									<div class="form-action">
										<button type="submit" class="i-btn btn--primary btn--md approve"> {{ translate("Approve") }} </button>
										<button type="button" class="i-btn btn--dark outline btn--md reject"> {{ translate("Rejected") }} </button>
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
		</div>
	</main>

@endsection
@section("modal")
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
			<form action="{{route('admin.report.payment.approve')}}" method="POST">
				@csrf
                <input type="hidden" name="id" value="{{$paymentLog->id}}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Approve Payment") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height ">
                    <div class="row g-4">
						<div class="action-message">
							<h6>{{ translate('Are you sure you want to approved this application?')}}</h6>
						</div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="feedback" class="form-label">{{translate('Feedback')}}<sup class="text-danger">*</sup></label>
								<textarea required class="form-control" name="feedback" id="feedback" rows="2"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
			<form action="{{route('admin.report.payment.reject')}}" method="POST">
				@csrf
                <input type="hidden" name="id" value="{{$paymentLog->id}}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Reject Payment") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height ">
                    <div class="row g-4">
						<div class="action-message">
							<h6>{{ translate('Are you sure you want to rejected this Payment?')}}</h6>
						</div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="feedback" class="form-label">{{translate('Feedback')}}<sup class="text-danger">*</sup></label>
								<textarea required class="form-control" name="feedback" id="feedback" rows="2"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('script-push')
<script>
	"use strict";

		$('.approve').on('click', function() {

            const modal = $('#approveModal');
			modal.modal('show');
		});

        $('.reject').on('click', function() {

            const modal = $('#rejectModal');
            modal.modal('show');
        });


</script>
@endpush

