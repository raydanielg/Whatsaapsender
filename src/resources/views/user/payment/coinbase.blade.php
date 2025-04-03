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
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- Coinbase") }} </li>
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
                    <button type="submit" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2" id="JsCheckoutPayment">{{ translate('Pay with Coinbase')}}</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection

@push('script-push')
<script>
    'use strict';
    var paymentForm = document.getElementById('JsCheckoutPayment');
    paymentForm.addEventListener('submit', coinbaseCommerce, false);
    $('#JsCheckoutPayment').on('click',function(e){
        coinbaseCommerce(e);
    })
    function coinbaseCommerce(e){
        e.preventDefault();
        $("#JsCheckoutPayment").html('{{ translate("Please Wait")}}');
        $.ajax({
            url: "{{route('user.coinbase')}}",
            data: {"_token": "{{ csrf_token() }}"},
            type: 'get',
            dataType: 'JSON',
            success: function(response){
                $("#JsCheckoutPayment").html('{{ translate("Pay with Coinbase")}}');
                if (response.error) {
                    notify('error',response.message);
                    return;
                }
                window.location.href = response.redirect_url;
            }
        });
    }
</script>
@endpush
