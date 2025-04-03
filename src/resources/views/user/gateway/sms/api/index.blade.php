@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('user.gateway.index')
@section('tab-content')

<div class="tab-pane active fade show" id="{{url()->current()}}" role="tabpanel">
    <div class="table-filter mb-4">
        <form action="{{route(Route::currentRouteName())}}" class="filter-form">
           
            <div class="row g-3">
                <div class="col-xxl-3 col-xl-4 col-lg-4">
                    <div class="filter-search">
                        <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
                        <span><i class="ri-search-line"></i></span>
                    </div>
                </div>

                <div class="col-xxl-5 col-xl-6 col-lg-7 offset-xxl-4 offset-xl-2">
                    <div class="filter-action">

                        <div class="input-group">
                            <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                            <span class="input-group-text" id="filterByDate">
                                <i class="ri-calendar-2-line"></i>
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="filter-action-btn ">
                                <i class="ri-menu-search-line"></i> {{ translate("Filter") }}
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
                <h4 class="card-title">{{ translate("SMS Gateways") }}</h4>
            </div>
            <div class="card-header-right">
                <div class="d-flex gap-3 align-item-center">

                    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                        <button class="i-btn btn--primary btn--sm add-sms-gateway" type="button" data-bs-toggle="modal" data-bs-target="#addSmsGateway">
                            <i class="ri-add-fill fs-16"></i> {{ translate("Add Gateway") }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">{{ translate("Gateway Name") }}</th>
                            <th scope="col">{{ translate("Gateway Type") }}</th>
                            @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())<th scope="col">{{ translate('Default')}}</th>@endif
                            <th scope="col">{{ translate("Status") }}</th>
                            @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())<th scope="col">{{ translate('Option')}}</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sms_gateways as $sms_gateway)
                            @php
                                $driver_info = json_encode($sms_gateway->sms_gateways);
                            @endphp
                            <tr class="@if($loop->even)@endif">

                                <td data-label="{{ translate('Gateway Name')}}"><span class="text-dark">{{ucfirst($sms_gateway->name)}}</span></td>
                                <td data-label="{{ translate('Gateway Type')}}"><span class="text-dark">{{preg_replace('/[[:digit:]]/','', setInputLabel($sms_gateway->type))}}</span></td>
                                @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <td data-label="{{ translate('Default') }}">
                                        @if($sms_gateway->is_default == \App\Enums\StatusEnum::TRUE->status())
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="i-badge dot success-soft pill">{{ translate("Default") }}</span>
                                            </div>
                                        @else
                                            <div class="switch-wrapper checkbox-data">
                                                <input {{ $sms_gateway->is_default == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                        type="checkbox"
                                                        class="switch-input statusUpdate"
                                                        data-id="{{ $sms_gateway->id }}"
                                                        data-column="is_default"
                                                        data-value="{{ $sms_gateway->is_default }}"
                                                        data-route="{{route('user.gateway.sms.api.status.update')}}"
                                                        id="{{ 'default_'.$sms_gateway->id }}"
                                                        name="is_default"/>
                                                <label for="{{ 'default_'.$sms_gateway->id }}" class="toggle">
                                                    <span></span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $sms_gateway->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input statusUpdate"
                                                    data-id="{{ $sms_gateway->id }}"
                                                    data-column="status"
                                                    data-value="{{ $sms_gateway->status }}"
                                                    data-route="{{route('user.gateway.sms.api.status.update')}}"
                                                    id="{{ 'status_'.$sms_gateway->id }}"
                                                    name="is_default"/>
                                            <label for="{{ 'status_'.$sms_gateway->id }}" class="toggle">
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                @else
                                    <td data-label="{{ translate('Status')}}">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="i-badge dot {{ $sms_gateway->status == App\Enums\StatusEnum::FALSE->status() ? 'danger' : 'success' }}-soft pill">{{ $sms_gateway->status == App\Enums\StatusEnum::FALSE->status() ? translate("Inactive") : translate("Active")}}</span>
                                        </div>
                                    </td>
                                @endif
                                @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle update-sms-gateway"
                                                    type="button"
                                                    data-id="{{$sms_gateway?->id}}"
                                                    data-gateway_type="{{$sms_gateway?->type}}"
                                                    data-gateway_name="{{$sms_gateway?->name}}"
                                                    data-gateway_credentials="{{json_encode($sms_gateway?->sms_gateways)}}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateSmsGateway">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update") }} </span>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm info-soft circle text-info quick-view"
                                                    type="button"
                                                    data-sms_gateways="{{ json_encode($sms_gateway->sms_gateways) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#quick_view">
                                                    <i class="ri-information-line"></i>
                                                <span class="tooltiptext"> {{ translate("Quick View") }} </span>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-sms-gateway"
                                                    type="button"
                                                    data-sms-gateway-id="{{ $sms_gateway->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteLanguage">
                                                <i class="ri-delete-bin-line"></i>
                                                <span class="tooltiptext"> {{ translate("Delete SMS Gateway") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('user.partials.pagination', ['paginator' => $sms_gateways])
        </div>
    </div>
</div>

@endsection

@section('modal')

@if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
    <div class="modal fade" id="addSmsGateway" tabindex="-1" aria-labelledby="addSmsGateway" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <form action="{{route('user.gateway.sms.api.store')}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add SMS Gateway") }} </h5>
                        <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                            <i class="ri-close-large-line"></i>
                        </button>
                    </div>
                    <div class="modal-body modal-lg-custom-height">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-inner">
                                    <label for="name" class="form-label"> {{ translate('Gateway Name')}} </label>
                                    <input type="text" id="name" name="name" placeholder="{{ translate('Enter Gateway Name')}}" class="form-control" aria-label="name"/>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-inner">
                                    <label for="country-code" class="form-label">{{ translate("Gateway Type") }}</label>
                                    <select data-placeholder="{{translate('Select a gateway type')}}" class="form-select select2-search gateway_type" data-show="5" id="add_gateway_type" name="type">
                                        <option value=""></option>
                                        @foreach($credentials as $credential)
                                            @if(array_key_exists('allowed_gateways', (array)$user->runningSubscription()->currentPlan()->sms) && $user->runningSubscription()->currentPlan()->sms?->allowed_gateways !=null )
                                                @foreach($user->runningSubscription()->currentPlan()->sms->allowed_gateways as $key => $value)

                                                    @php $remaining = isset($gatewayCount[$credential->gateway_code]) ? $value - $gatewayCount[$credential->gateway_code] : $value; @endphp
                                                    @if((preg_replace('/_/','',strtolower($key)) == preg_replace('/ /','',strtolower($credential->name)) || str_contains(preg_replace('/ /','',strtolower($credential->name)),preg_replace('/_/','',strtolower($key)) )) && $remaining > 0)
                                                        <option value="{{$credential->gateway_code}}">{{strtoupper($credential->name)}} ({{translate("Remaining Gateway: ").$remaining}})</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row newdataadd"></div>
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

    <div class="modal fade" id="updateSmsGateway" tabindex="-1" aria-labelledby="updateSmsGateway" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <form action="{{ route('user.gateway.sms.api.update')}}" method="post">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update SMS Gateway") }} </h5>
                        <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                            <i class="ri-close-large-line"></i>
                        </button>
                    </div>
                    <div class="modal-body modal-lg-custom-height">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-inner">
                                    <label for="name" class="form-label"> {{ translate('Gateway Name')}} </label>
                                    <input type="text" id="name" name="name" placeholder="{{ translate('Enter Gateway Name')}}" class="form-control" aria-label="name"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-inner">
                                    <label for="country-code" class="form-label">{{ translate("Gateway Type") }}</label>
                                    <select data-placeholder="{{translate('Select a gateway type')}}" class="form-select select-gateway-type gateway_type select2-search" data-show="5" id="gateway_type_edit" name="type">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row newdataadd"></div>
                            <div class="row oldData"></div>
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

    <div class="modal fade" id="quick_view" tabindex="-1" aria-labelledby="quick_view" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate("SMS Gateway Information") }}</h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="information-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal">{{ translate("Close") }}</button>
                    <button type="button" class="i-btn btn--primary btn--md">{{ translate("Save") }}</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade actionModal" id="deleteSmsGateway" tabindex="-1" aria-labelledby="deleteSmsGateway" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
            <div class="modal-header text-start">
                <span class="action-icon danger">
                <i class="bi bi-exclamation-circle"></i>
                </span>
            </div>
            <form action="{{route('user.gateway.sms.api.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <div class="action-message">
                        <h5>{{ translate("Are you sure to delete this sms_gateway?") }}</h5>
                        <p>{{ translate("By clicking on 'Delete', you will permanently remove the sms_gateway from the application") }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                    <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Delete") }} </button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endif

@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush

@push('script-push')
<script>
	(function($){
		"use strict";

        select2_search($('.select2-search').data('placeholder'));
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $(document).ready(function() {

            $('.checkAll').click(function(){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
            var oldType = '';
            var oldInfo = [];

            $('.add-sms-gateway').on('click', function() {

                const modal = $('#addSmsGateway');
                modal.modal('show');
            });

            $('.update-sms-gateway').on('click', function() {

                $('.newdataadd').empty();
                $('.oldData').empty();
                $('.select-gateway-type').empty();
                $('.active').attr("selected",false);
                $('.inactive').attr("selected",false);
                $('.gatewayType').attr("selected",false);

                var modal = $('#updateSmsGateway');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=name]').val($(this).data('gateway_name'));
                modal.find('#gateway_type_edit').append(`<option class="text-uppercase gatewayType" value="${$(this).data('gateway_type')}" selected>${$(this).data('gateway_type').replace($(this).data('gateway_type').match(/(\d+)/g)[0], '').trim()}</option>`);
                var previousType = $(this).data('gateway_type');
                $(this).data('gateway_status') == 1 ? $('.active').attr("selected",true) : $('.inactive').attr("selected",true);


                var data = <?php echo $credentials ?>;
                oldType = $(this).data('gateway_type');

                var user = <?php echo json_encode(@$user->runningSubscription()->currentPlan()->sms->allowed_gateways ?? []) ?>;
                $.each(data, function(key, value) {

                    $.each(user, function(u_key, u_value){
                        if(u_key.replace(/_/g, '') == value.name.toLowerCase() && u_value > 0){
                            var gateway = value['gateway_code'].replace(value['gateway_code'].match(/(\d+)/g)[0], '').trim();
                            if(oldType != value) {
                                var previous = $('<option class="text-uppercase gatewayType" disabled">'+ previousType +'</option>');
                            }
                            var option = $('<option class="text-uppercase gatewayType" value="'+ value['gateway_code'] +'">'+ gateway +'</option>');
                            $('.select-gateway-type').append(previous, option);
                        }
                    });

                });



                oldInfo = $(this).data('gateway_credentials');

                $.each(oldInfo, function(key, value) {
                    var filterkey = key.replace("_", " ");
                    var div   = $('<div class="mt-4 col-lg-6"></div>');
                    var label = $('<label for="' + key + '" class="form-label text-capitalize">' + filterkey + '<sup class="text--danger">*</sup></label>');
                    var input = $('<input type="text" class="form-control" id="' + key + '" value="' + value + '" name="driver_information[' + key + ']" placeholder="Enter ' + filterkey + '" required>');
                    div.append(label, input);
                    $('.oldData').append(div);
                });

                modal.modal('show');
            });

            $('.gateway_type').on('change', function() {

               $('.newdataadd').empty();
               var data = <?php echo $credentials ?>;
               var newType = this.value;

               if(newType != oldType){

                   $.each(data, function(key, v) {
                       $('.oldData').empty();
                       if(v['gateway_code'] == newType) {

                       var creds = v['credential'];
                       $.each(creds, function(key, v) {


                               var filterkey = key.replace("_", " ");
                               var div   = $('<div class="mt-4 col-lg-6"></div>');
                               var label = $('<label for="' + key + '" class="form-label text-capitalize">' + filterkey + '<sup class="text-danger">*</sup></label>');
                               var input = $('<input type="text" class="form-control" id="' + key + '" name="driver_information[' + key + ']" placeholder="Enter ' + filterkey + '" required>');
                               div.append(label, input);
                               $('.newdataadd').append(div);
                           });
                       }
                   });
               }
               else{

                   $.each(oldInfo, function(key, value) {
                       var filterkey = key.replace("_", " ");
                       var div   = $('<div class="mt-4 col-lg-6"></div>');
                       var label = $('<label for="' + key + '" class="form-label text-capitalize">' + filterkey + '<sup class="text--danger">*</sup></label>');
                       var input = $('<input type="text" class="form-control" id="' + key + '" value="' + value + '" name="driver_information[' + key + ']" placeholder="Enter ' + filterkey + '" required>');
                       div.append(label, input);
                       $('.oldData').append(div);
                   });
               }
            });

            $('.quick-view').on('click', function() {
                const modal = $('#quick_view');
                const modalBody = modal.find('.modal-body .information-list');
                modalBody.empty();

                const dataAttributes = $(this).data();

                for (const [key, value] of Object.entries(dataAttributes)) {
                    if (key === 'sms_gateways') {
                        const sms_gateways = value;
                        for (const [paramKey, paramValue] of Object.entries(sms_gateways)) {
                            const listItem = $('<li>');
                            const paramKeySpan = $('<span>').text(textFormat(['_'], paramKey, ' '));
                            const arrowIcon = $('<i>').addClass('bi bi-arrow-right');
                            const paramValueSpan = $('<span>').addClass(' text-muted').text(paramValue);

                            listItem.append(paramKeySpan).append(arrowIcon).append(paramValueSpan);
                            modalBody.append(listItem);
                        }
                    } else if (key !== 'bsTarget' && key !== 'bsToggle') {
                        const listItem = $('<li>');
                        const keySpan = $('<span>').text(textFormat(['_'], key, ' '));
                        const arrowIcon = $('<i>').addClass('bi bi-arrow-right');
                        const valueSpan = $('<span>').addClass(' text-muted').text(value);

                        listItem.append(keySpan).append(arrowIcon).append(valueSpan);
                        modalBody.append(listItem);
                    }
                }

                modal.modal('show');
            });

            $('.delete-sms-gateway').on('click', function() {

                var modal = $('#deleteSmsGateway');
                modal.find('input[name=id]').val($(this).data('sms-gateway-id'));
                modal.modal('show');
            });
        });
	})(jQuery);
</script>
@endpush
