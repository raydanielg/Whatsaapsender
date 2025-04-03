@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('admin.gateway.index')
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
                <h4 class="card-title">{{ translate("Contacts") }}</h4>
            </div>
            <div class="card-header-right">
                <div class="d-flex gap-3 align-item-center">
                    <button class="bulk-action i-btn btn--danger btn--sm bulk-delete-btn d-none">
                        <i class="ri-delete-bin-6-line"></i>
                    </button>

                    <div class="bulk-action form-inner d-none">
                        <select class="form-select" data-show="5" id="bulk_status" name="status">
                            <option disabled selected>{{ translate("Select a status") }}</option>
                            <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Enabled") }}</option>
                            <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Disabled") }}</option>
                        </select>
                    </div>

                    <button class="i-btn btn--primary btn--sm add-sms-gateway" type="button" data-bs-toggle="modal" data-bs-target="#addSmsGateway">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add Gateway") }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check">
                                  <input class="check-all form-check-input" type="checkbox" value="" id="checkAll" />
                                  <label class="form-check-label" for="checkedAll"> {{ translate("SL No.") }} </label>
                                </div>
                            </th>
                            <th scope="col">{{ translate("Gateway Name") }}</th>
                            <th scope="col">{{ translate("Gateway Type") }}</th>
                            <th scope="col">{{ translate("Default") }}</th>
                            <th scope="col">{{ translate("Status") }}</th>
                            <th scope="col">{{ translate("Option") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sms_gateways as $sms_gateway)
                            @php
                                $driver_info = json_encode($sms_gateway->sms_gateways);
                            @endphp
                            <tr class="@if($loop->even)@endif">
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" value="{{$sms_gateway->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$sms_gateway->id}}" />
                                        <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                                    </div>
                                </td>
                                <td data-label="{{ translate('Gateway Name')}}"><span class="text-dark">{{ucfirst($sms_gateway->name)}}</span></td>
                                <td data-label="{{ translate('Gateway Type')}}"><span class="text-dark">{{preg_replace('/[[:digit:]]/','', setInputLabel($sms_gateway->type))}}</span></td>

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
                                                    data-route="{{route('admin.gateway.sms.api.status.update')}}"
                                                    id="{{ 'default_'.$sms_gateway->id }}"
                                                    name="is_default"/>
                                            <label for="{{ 'default_'.$sms_gateway->id }}" class="toggle">
                                                <span></span>
                                            </label>
                                        </div>
                                    @endif
                                </td>
                                <td data-label="{{ translate('Status')}}">
                                    <div class="switch-wrapper checkbox-data">
                                        <input {{ $sms_gateway->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                type="checkbox"
                                                class="switch-input statusUpdate"
                                                data-id="{{ $sms_gateway->id }}"
                                                data-column="status"
                                                data-value="{{ $sms_gateway->status }}"
                                                data-route="{{route('admin.gateway.sms.api.status.update')}}"
                                                id="{{ 'status_'.$sms_gateway->id }}"
                                                name="is_default"/>
                                        <label for="{{ 'status_'.$sms_gateway->id }}" class="toggle">
                                            <span></span>
                                        </label>
                                    </div>
                                </td>

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
                                                data-uid="{{ $sms_gateway->uid }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#quick_view">
                                                <i class="ri-information-line"></i>
                                            <span class="tooltiptext"> {{ translate("Quick View") }} </span>
                                        </button>
                                        <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-sms-gateway"
                                                type="button"
                                                data-sms-gateway-id="{{ $sms_gateway->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteSmsGateway">
                                            <i class="ri-delete-bin-line"></i>
                                            <span class="tooltiptext"> {{ translate("Delete SMS Gateway") }} </span>
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
            @include('admin.partials.pagination', ['paginator' => $sms_gateways])
        </div>
    </div>
</div>

@endsection

@section('modal')

<div class="modal fade actionModal" id="bulkAction" tabindex="-1" aria-labelledby="bulkAction" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.gateway.sms.api.bulk')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Do you want to proceed?") }}</h5>
                    <p>{{ translate("This action is irreversable") }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Proceed") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>
<div class="modal fade modal-select2" id="addSmsGateway" tabindex="-1" aria-labelledby="addSmsGateway" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.gateway.sms.api.store')}}" method="POST">
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
                                    <option value="{{$credential->gateway_code}}">{{textFormat(['_'], $credential->name, ' ')}}</option>
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

<div class="modal fade modal-select2" id="updateSmsGateway" tabindex="-1" aria-labelledby="updateSmsGateway" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{ route('admin.gateway.sms.api.update')}}" method="post">
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
                                <select data-placeholder="{{translate('Select a gateway type')}}" class="form-select select-gateway-type gateway_type" data-show="5" id="gateway_type_edit" name="type">
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
        <form action="{{route('admin.gateway.sms.api.delete')}}" method="POST">
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

@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush

@push('script-push')
<script>
	(function($){
		"use strict";

        select2_search($('.select2-search').data('placeholder'), $('#addSmsGateway'));
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

                var data = <?php echo $credentials ?>;
                oldType = $(this).data('gateway_type');

                $.each(data, function(key, value) {
                    var gateway = value['gateway_code'].replace(value['gateway_code'].match(/(\d+)/g)[0], '').trim();
                    var option = $('<option class="text-uppercase gatewayType" value="'+ value['gateway_code'] +'">'+ textFormat(['_'], gateway, ' ') +'</option>');
                    $('.select-gateway-type').append(option);
                    if(oldType == value['gateway_code']){
                        $('.gatewayType').attr("selected",true)
                    }
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
                const modalBodyInformation = modal.find('.modal-body .information-list');
                modalBodyInformation.empty();

                var driver = $(this).data('sms_gateways');
                var uid = $(this).data('uid');

                $.each(driver, function(key, value) {
                    const listItem = $('<li>');
                    const paramKeySpan = $('<span>').text(textFormat(['_'], key, ' '));
                    const arrowIcon = $('<i>').addClass('bi bi-arrow-right');
                    const paramValueSpan = $('<span>').addClass('text-break text-muted').text(value);

                    listItem.append(paramKeySpan).append(arrowIcon).append(paramValueSpan);
                    modalBodyInformation.append(listItem);
                });
                if(uid) {
                    var title = 'gateway_identifier';
                    const listItem = $('<li>');
                    const paramKeySpan = $('<span>').text(textFormat(['_'], title, ' '));
                    const arrowIcon = $('<i>').addClass('bi bi-arrow-right');
                    const paramValueSpan = $(`<span title='${title}'>`).addClass('text-break text-muted').text(uid);

                    listItem.append(paramKeySpan).append(arrowIcon).append(paramValueSpan);
                    modalBodyInformation.append(listItem);
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
