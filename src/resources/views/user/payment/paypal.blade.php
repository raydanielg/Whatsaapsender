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
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- Paypal") }} </li>
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
            <div class="card-body mx-auto p-4">
                <div id="paypal-button-container"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection
@push('script-push')

    <script src="https://www.paypal.com/sdk/js?client-id={{$data->client_id}}&currency={{ $data->currency }}"></script>
    <script>
        "use strict";
            paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [
                        {
                            description: "{{ $data->description }}",
                            custom_id: "{{$data->custom_id}}",
                            amount: {
                                currency_code: "{{$data->currency}}",
                                value: "{{$data->amount}}",
                                breakdown: {
                                    item_total: {
                                        currency_code: "{{$data->currency}}",
                                        value: "{{$data->amount}}"
                                    }
                                }
                            }
                        }
                    ]
                });
            },

            onApprove: function (data, actions) {

                return actions.order.capture().then(function (details) {

                    var trx = "{{$data->custom_id}}";
                    window.location = '{{ url('user/ipn/paypal/status')}}/' + trx + '/' + details.id + '/' + details.status
                });
            }
        }).render('#paypal-button-container');
    </script>
@endpush
