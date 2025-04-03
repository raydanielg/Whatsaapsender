@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ $title.' ('.$payment_method?->name.')' }}</h2>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ $title.' ('.$payment_method?->name.')' }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body pt-0">
                <form action="{{route('admin.payment.automatic.update', $payment_method->id)}}" method="POST" enctype="multipart/form-data">
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
                                            <label for="currency_code" class="form-label">{{ translate("Payment Gateway Currency") }}</label>
                                            <select data-placeholder="{{translate('Select a currency')}}" class="form-select select2-search" data-show="5" id="currency_code" name="currency_code">
                                                <option value=""></option>
                                                @foreach($currencies as $key => $currency)
                                                    <option {{ $key == $payment_method->currency_code ? 'selected' : '' }} data-rate_value="{{shortAmount($currency['rate'])}}" value="{{ $key }}">{{$key}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="image" class="form-label">
                                                {{translate("Payment Gateway Image")}} <small class="text-danger" >* ({{config("setting")['file_path']['automatic_payment']['size']}})</small>
                                            </label>
                                            <input class="form-control"  type="file" name="image" class="preview" data-size = "150x150">
                                            <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="percent_charge" class="form-label"> {{ translate("Percentage Charge") }} <small class="text-danger">*</small></label>
                                            <input type="text" id="percent_charge" name="percent_charge" class="form-control" placeholder="{{ translate('Enter percentage charge amount') }}" aria-label="{{ translate('Enter percentage charge amount') }}" value="{{ $payment_method->percent_charge }}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            
                                            <label for="rate" class="form-label"> {{ translate('Currency Rate')}} <sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">{{translate("1 ").getDefaultCurrencyCode($currencies)}}</span>
                                                <input type="text" name="rate" class="method-rate form-control" value="{{shortAmount($payment_method->rate)}}">
                                                <span class="input-group-text limittext"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-element child">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ $payment_method?->name.translate(" Credentials") }}</h5>
                            </div>
                            <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    @foreach($payment_method->payment_parameter as $key => $parameter)
                                        <div class="col-md-12">
                                            <label for="{{$key}}" class="form-label">{{ucwords(str_replace('_', ' ', $key))}} <sup class="text--danger">*</sup></label>
                                            <input type="text" name="method[{{$key}}]" id="{{$key}}" value="{{$parameter}}" class="form-control" placeholder=" {{ translate('Give Valid Data')}}" required>
                                        </div>
                                    @endforeach
                                </div>
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

        var initialRate = $(".method-rate").val();
        var initialCurrency = $("#currency_code").val();

        function updateCurrencyInfo() {
            var selectedOption = $("#currency_code").find("option:selected");
            var value = selectedOption.text();
            $(".limittext").text(value);

            if ($("#currency_code").val() !== initialCurrency) {
                $(".method-rate").val(selectedOption.data('rate_value'));
            } else {
                $(".method-rate").val(initialRate);
            }
        }

        $("#currency_code").on('change', function(){
            updateCurrencyInfo();
        });

        updateCurrencyInfo();
    })(jQuery);
</script>
@endpush
