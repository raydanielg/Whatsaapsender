@extends('user.layouts.app')
@section('panel')
<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="row gy-4">
          <div class="col-md-12">
            <div class="page-header-left">
              <h2>{{ $title }}</h2>
              <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.dashboard') }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.plan.create') }}">{{ translate("Buy Or Renew Plan") }}</a>
                    </li>
                    <li class="breadcrumb-item">
						<a href="{{ route('user.plan.make.payment', $id) }}">{{ translate("Make Payment") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- Paystack") }} </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header pt-4 justify-content-center">
              <h4 class="card-title">{{translate($title)}}</h4>
            </div>
            <div class="card-body p-4">
				<form id="paymentForm">
					<form id="paymentForm">
						@csrf
						  <div class="form-submit row justify-content-center">
							<button type="submit" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2" onclick="payWithPaystack(event)">{{ translate('Pay With Paystack')}}</button>
						</div>
					  </form>
					
				</form>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
   
@endsection
@push('script-include')
    <script src="https://js.paystack.co/v2/inline.js"></script>
@endpush

@push('script-push')
<script>
	'use strict';
	var paymentForm = document.getElementById('paymentForm');
	paymentForm.addEventListener('submit', payWithPaystack, false);
	function payWithPaystack(e){
		e.preventDefault();
	 	var handler = PaystackPop.setup({
	    	key: '{{$paymentMethod->payment_parameter->public_key}}',
	    	email: '{{$paymentLog->user->email}}',
	    	amount: '{{round($paymentLog->final_amount, 2)*100}}',
	    	currency: '{{$paymentMethod->currency_code}}',
			ref: '{{trxNumber()}}',
	    	callback: function(response){
	    		$.ajax({
				    url: "{{route('user.payment.with.paystack')}}",
				    data: {reference : response.reference},
				    type: "GET",
				    success: function(response){
				    	// console.log(response);
				    	notify('success',response.message);
				    	window.location.href = "{{route('user.dashboard')}}";
				    }
				});
	    	},
	    	onClose: function() {
	      		notify('error','Transaction was not completed, window closed.');
	    	},
	  	});
	  handler.openIframe();
	}
</script>
@endpush
