@extends('user.layouts.app')
@section('panel')
@push('script-include')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- RazorPay") }} </li>
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
                <div class="form-submit row justify-content-center">
                    <button type="submit" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2" id="JsCheckoutPayment">{{ translate('Pay with Razorpay')}}</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection

@push('script-push')
<script type="text/javascript">
        "use strict";
        var options = {
                "key": "{{$paymentMethod->payment_parameter->key_id}}",
                "amount": "{{$paymentLog->final_amount}}",
                "currency": "{{$paymentMethod->currency_code}}",
                "name": "{{site_settings('site_name')}}",
                "description": "Transaction",
                "image": "{{showImage(config('setting.file_path.panel_square_logo.path').'/'.site_settings('panel_square_logo'),config('setting.file_path.panel_square_logo.size'))}}",
                "order_id": "{{$order->id}}",
                "callback_url": "{{route('user.razorpay')}}",
                "prefill": {
                    "name": "{{auth()->user()->name}}",
                    "email": "{{auth()->user()->email}}",
                    "contact": ""
                },
                "notes": {
                    "address": ""
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

        var rzp1 = new Razorpay(options);
        $("#JsCheckoutPayment").on("click",function(e){
            rzp1.open();
            e.preventDefault();
        });
</script>
@endpush
