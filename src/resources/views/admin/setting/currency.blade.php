@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
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
                    <div class="col-lg-4">
                        <div class="filter-search">
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search for user by Name or Email") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>
                    
                    <div class="col-xxl-6 col-lg-8 offset-xxl-2">
                        <div class="filter-action justify-content-end">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="filter-action-btn ">
                                    <i class="ri-menu-search-line"></i> {{ translate("Filters") }}
                                </button>
                                <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                                    <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h4 class="card-title">{{ translate("Currency List") }}</h4>
                </div>
                <div class="card-header-right">
                    <button class="i-btn btn--primary btn--sm add-currency" type="button" data-bs-toggle="modal" data-bs-target="#addCurrency">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add Currency") }}
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Name") }}</th>
                                <th scope="col">{{ translate("Symbol") }}</th>
                                <th scope="col">{{ translate("Rate") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Default") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($currencies as $currency_code => $currency_value)

                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Name')}}">
                                        <p class="text-dark fw-semibold">{{$currency_value['name']}}</p>
                                    </td>

                                    <td data-label="{{ translate('Symbol')}}">
                                        <p class="text-dark fw-semibold">{{$currency_value['symbol']}}</p>
                                    </td>

                                    <td data-label="{{ translate('Rate')}}">
                                        {{translate("1 ").getDefaultCurrencyCode(json_decode(site_settings('currencies'), true))}} = {{shortAmount($currency_value['rate'])}} {{$currency_code}}
                                    </td>
                                    <td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $currency_value['status'] == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input statusUpdate"
                                                    data-id="{{ $currency_code }}"
                                                    data-column="status"
                                                    data-route="{{route('admin.system.currency.status.update')}}"
                                                    id="{{ 'status_'.$currency_code }}"
                                                    name="status"/>
                                            <label for="{{ 'status_'.$currency_code }}" class="toggle">
                                                <span></span>

                                            </label>
                                        </div>
                                    </td>
                                    <td data-label="{{ translate('Default')}}">
                                        @if( $currency_value['is_default'] == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' )
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="i-badge dot success-soft pill">{{ translate("Default") }}</span>
                                            </div>
                                        @else
                                            <div class="switch-wrapper checkbox-data">
                                                <input  type="checkbox"
                                                        class="switch-input statusUpdate"
                                                        data-column="is_default"
                                                        data-id="{{ $currency_code }}"
                                                        data-route="{{route('admin.system.currency.status.update')}}"
                                                        id="{{ 'default_'.$currency_code }}"
                                                        name="is_default"/>
                                                <label for="{{ 'default_'.$currency_code }}" class="toggle">
                                                    <span></span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>

                                    <td data-label="{{ translate('Option')}}">
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle currency-data"
                                                    type="button"
                                                    data-currency-code="{{ $currency_code }}"
                                                    data-currency-name="{{ $currency_value['name'] }}"
                                                    data-currency-symbol="{{$currency_value['symbol'] }}"
                                                    data-currency-rate="{{ $currency_value['rate'] }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateCurrency">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('admin.partials.array_pagination', ['meta_data' => $currencies])
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade" id="updateCurrency" tabindex="-1" aria-labelledby="updateCurrency" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.system.currency.save')}}" method="POST">
                @csrf
                <input type="text" hidden name="old_code">
                <input type="text" hidden name="old_symbol">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Currency") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="code" class="form-label"> {{ translate('Code')}} </label>
                                <input type="text" id="code" name="code" placeholder="{{ translate('Enter Currency Code')}}" class="form-control" aria-label="code"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Name')}} </label>
                                <input type="text" id="name" name="name" placeholder="{{ translate('Enter Currency Name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="symbol" class="form-label"> {{ translate('Symbol')}} </label>
                                <input type="text" id="symbol" name="symbol" class="form-control" aria-label="symbol" placeholder="{{ translate('Enter Currency Symbol')}}" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <div class="form-inner">
                                    <label for="rate" class="form-label"> {{ translate('Exchange Rate')}} </label>
                                    <input type="number" step="any" min="0" id="rate" name="rate" class="form-control" aria-label="rate" placeholder="{{ translate('Exchange rate with the default currency')}}" />
                                </div>
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

<div class="modal modal-select2 fade" id="addCurrency" tabindex="-1" aria-labelledby="addCurrency" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.system.currency.save')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Currency") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-inner">

                                <label for="country" class="form-label">{{ translate("Select Country") }}</label>
                                <select class="form-select select2-search" id="country">
                                    <option value="">{{ translate("Select a country") }}</option>
                                    @foreach ($countries as $codes)
                                        <option value="{{json_encode($codes['currency'])}}">
                                            {{$codes['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="form-element-note">{{translate("Select a country to auto fill name, symbol and code")}}</p>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="code" class="form-label"> {{ translate('code')}} </label>
                                <input type="text" id="code" name="code" placeholder="{{ translate('Enter currency code')}}" class="form-control" aria-label="code"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Name')}} </label>
                                <input type="text" id="name" name="name" placeholder="{{ translate('Enter currency name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="symbol" class="form-label"> {{ translate('Symbol')}} </label>
                                <input type="text" id="symbol" name="symbol" class="form-control" aria-label="symbol" placeholder="{{ translate('Provide a currency symbol')}}" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <div class="form-inner">
                                    <label for="rate" class="form-label"> {{ translate('Exchange Rate')}} </label>
                                    <input type="number" id="rate" name="rate" class="form-control" aria-label="rate" placeholder="{{ translate('Exchange rate for the currency')}}" />
                                </div>
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

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush
@push('script-push')
<script>
	(function($){
		"use strict";

        select2_search($('.select2-search').data('placeholder'), $('.modal-select2'));
		$(document).ready(function() {

            $('.currency-data').on('click', function() {
                const modal = $('#updateCurrency');
                modal.find('input[name=old_code]').val($(this).attr('data-currency-code'));
                modal.find('input[name=old_symbol]').val($(this).attr('data-currency-symbol'));
                modal.find('input[name=code]').val($(this).attr('data-currency-code'));
                modal.find('input[name=name]').val($(this).attr('data-currency-name'));
                modal.find('input[name=symbol]').val($(this).attr('data-currency-symbol'));
                modal.find('input[name=rate]').val($(this).attr('data-currency-rate'));
			    modal.modal('show');
            });

            $('.add-currency').on('click', function() {
                const modal = $('#addCurrency');
			    modal.modal('show');
            });

            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                mode: "range",
            });

            $('form').on('submit', function(e) {
                $('.checkbox-data').each(function() {
                    var $checkbox = $(this).find('.switch-input');
                    var $hiddenInput = $(this).find('input[type="hidden"]');

                    if ($checkbox.is(':checked')) {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::TRUE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::TRUE->status() }}');
                        }
                    } else {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::FALSE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::FALSE->status() }}');
                        }
                    }
                });
            });

            $('#country').on('change', function() {
        var selectedCountry = JSON.parse($(this).val());
        var countryDetails = getCountryDetails(selectedCountry.code);

        if (countryDetails) {
            $('#addCurrency #code').val(countryDetails.code);
            $('#addCurrency #name').val(countryDetails.name);
            $('#addCurrency #symbol').val(countryDetails.symbol);
        } else {
            // Clear input fields if no country is selected
            $('#addCurrency #code').val('');
            $('#addCurrency #name').val('');
            $('#addCurrency #symbol').val('');
        }
    });

    function getCountryDetails(selectedCountryCode) {
        // Find the country details from the $countries array
        var countryDetails = null;
        @foreach ($countries as $code)
            if ('{{ $code['currency']['code'] }}' === selectedCountryCode) {
                countryDetails = {
                    code: '{{ $code['currency']['code'] }}',
                    name: '{{ $code['currency']['name'] }}',
                    symbol: '{{ $code['currency']['symbol'] }}'
                };
            }
        @endforeach

        return countryDetails;
    }
        });
	})(jQuery);
</script>
@endpush


