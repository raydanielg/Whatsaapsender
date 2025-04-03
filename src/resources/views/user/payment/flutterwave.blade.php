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
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- Flutter Wave") }} </li>
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
                        <button type="button" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2 payment-btn" id="btn-confirm" onClick="payWithFlutterwave()">{{ translate('Pay Now')}}</button>
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

<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script>
    "use strict";
    var btn = document.querySelector("#btn-confirm");
    btn.setAttribute("type", "button");
    const API_publicKey = "{{$paymentMethod->payment_parameter->public_key}}";

    function payWithFlutterwave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "{{$paymentLog->user->email}}",
            amount: "{{round($paymentLog->final_amount, 2)}}",
            customer_phone: "",
            currency: "{{$paymentMethod->currency_code}}",
            txref: "{{$paymentLog->trx_number}}",
            onclose: function () {
                notify('error','Transaction was not completed, window closed.');
            },
            callback: function (response) {
                var txref = response.tx.txRef;
                var status = response.tx.status;
                var chargeResponse = response.tx.chargeResponseCode;
                if (chargeResponse == "00" || chargeResponse == "0") {
                    window.location = '{{ url('user/flutterwave') }}/' + txref + '/' + status;
                } else {
                    window.location = '{{ url('user/flutterwave') }}/' + txref + '/' + status;
                }
                // x.close(); // use this to close the modal immediately after payment.
            }
        });
}
</script>
@endpush