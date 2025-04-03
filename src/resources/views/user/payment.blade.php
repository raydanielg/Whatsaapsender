@extends('user.layouts.app')
@section('panel')
<section>
	<div class="container-fluid p-0 mb-3 pb-2">
		<div class="card">
			<div class="card-header bg--lite--violet">
				<h6 class="card-title text-center">{{@$title}}</h6>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="card-body">
						<ul class="list-group">
							<li class="list-group-item d-flex justify-content-between align-items-center">
								{{ translate('Amount')}}
								<small>{{$general->currency_symbol}}{{shortAmount($paymentLog->amount)}}</small>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								{{ translate('Charge')}}
								<small>{{$general->currency_symbol}}{{shortAmount($paymentLog->charge)}}</small>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								{{ translate('Payable')}}
								<small>{{$general->currency_symbol}}{{shortAmount($paymentLog->amount + $paymentLog->charge)}}</small>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								{{ translate('In')}} {{$paymentLog->paymentGateway->currency->name}}
								<small>{{shortAmount($paymentLog->final_amount)}}</small>
							</li>
						</ul>
						<div class="mt-3">
							@if(substr($paymentLog->paymentGateway->unique_code,0,6) == "MANUAL")
							<a href="{{route('user.manual.payment.confirm')}}" class="i-btn primary--btn btn--md">{{ translate('Pay Now')}}</a>
							@else
								<a href="{{route('user.payment.confirm')}}" class="i-btn primary--btn btn--md">{{ translate('Pay Now')}}</a>
							@endif
						</div>
					</div>
				</div>

				<div class="col-lg-6 card-body">

					<div class="payment-gateway-image">
						<img src="{{showImage(filePath()['payment_method']['path'].'/'.$paymentLog->paymentGateway->image,filePath()['payment_method']['size'])}}" class="rounded mx-auto d-block" alt="{{$paymentLog->paymentGateway->name}}">
					</div>
					<h5 class="text-center mt-3">{{$paymentLog->paymentGateway->name}}</h5>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('style-push')
<style type="text/css">
	.payment-gateway-image img{
		width: 200px;
		height: 150px;
	}
</style>
@endpush
