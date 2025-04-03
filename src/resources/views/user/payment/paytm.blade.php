@extends('user.layouts.app')
@section('panel')
@push('script-include')
<script type="application/javascript" crossorigin="anonymous" src="{{$paymentMethod->payment_parameter->PAYTM_ENVIRONMENT}}/merchantpgpui/checkoutjs/merchants/{{$paymentMethod->payment_parameter->PAYTM_MID}}.js"></script>
@endpush
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
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- PayTM") }} </li>
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
					@csrf
				  <div class="form-submit row justify-content-center">
					  <button type="submit" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2" id="JsCheckoutPayment">{{ translate('Pay with Paytm')}}</button>
				  </div>
			  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection

@push('script-push')
<script type="application/javascript">
	'use strict';
	$("#JsCheckoutPayment").on('click',function(e){
		e.preventDefault();
		$.ajax({
		    url: "{{route('user.paytm.process')}}",
		    data: {
		    	"_token": "{{ csrf_token() }}",
		    	paytm_mid:'{{$paymentMethod->payment_parameter->PAYTM_MID}}',
		    	paytm_website:'{{$paymentMethod->payment_parameter->PAYTM_WEBSITE}}',
		    	paytm_merchant_key:'{{$paymentMethod->payment_parameter->PAYTM_MERCHANT_KEY}}',
		    	paytm_environment:'{{$paymentMethod->payment_parameter->PAYTM_ENVIRONMENT}}'
		    },
		    type: "POST",
		    success: function(response){
		    	if (response.success) {
		    		openJsCheckoutPopup(response.orderId, response.txnToken, '1');
		    	}else{
		    		notify('error',response.message);
		    	}
		    }
		});

	});

	function openJsCheckoutPopup(orderId, txnToken, amount)
	{
		var config = {
			"root": "",
			"flow": "DEFAULT",
			"data": {
				"orderId": orderId,
				"token": txnToken,
				"tokenType": "TXN_TOKEN",
				"amount": amount
				},
				"merchant":{
				"redirect": true
			},
			"handler": {
			"notifyMerchant": function(eventName,data){
				console.log("notifyMerchant handler function called");
				console.log("eventName => ",eventName);
				console.log("data => ",data);
				}
			}
		};
		if(window.Paytm && window.Paytm.CheckoutJS){
			window.Paytm.CheckoutJS.init(config).then(function onSuccess() {
				window.Paytm.CheckoutJS.invoke();
			}).catch(function onError(error){
				console.log("error => ",error);
			});
		}
	}
</script>
@endpush
