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
                        <div class="col-xxl-3 col-lg-3">
                            <div class="filter-search">
                                <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Filter by receiver's number") }}" />
                                <span><i class="ri-search-line"></i></span>
                            </div>
                        </div>
                        <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                            <div class="filter-action">
                                <select data-placeholder="{{translate('Select A Delivery Status')}}" class="form-select select2-search" name="status" aria-label="{{translate('Select A Delivery Status')}}">
                                    <option value=""></option>
                                    @foreach(\App\Enums\CommunicationStatusEnum::toArray() as $key => $value)
                                        <option value="{{ $value }}">{{ ucfirst(strtolower($key)) }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                                    <span class="input-group-text" id="filterByDate">
                                        <i class="ri-calendar-2-line"></i>
                                    </span>
                                </div>

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
                <h4 class="card-title">{{ translate("Communication Logs") }}</h4>
                </div>
                <div class="card-header-right">
                    <div class="d-flex gap-3 align-item-center">
                        <button class="bulk-action i-btn btn--danger btn--sm bulk-delete-btn d-none">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>

                        <div class="bulk-action form-inner d-none">
                            <select class="form-select" data-show="5" id="bulk_status" name="status">
                                <option disabled selected>{{ translate("Select a status") }}</option>
                                @foreach(\App\Enums\CommunicationStatusEnum::toArray() as $key => $value)
                                    @if($value != \App\Enums\CommunicationStatusEnum::SCHEDULE->value)
                                        <option value="{{ $value }}">{{ ucfirst(strtolower($key)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
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
                                    <th scope="col">{{ translate("User") }}</th>
                                    <th scope="col">{{ translate("Sender") }}</th>
                                    <th scope="col">{{ translate("To") }}</th>
                                    <th scope="col">{{ translate("Date & Time") }}</th>
                                    <th scope="col">{{ translate("Status") }}</th>
                                    <th scope="col">{{ translate("Options") }}</th>
                                </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" value="{{$log->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$log->id}}" />
                                            <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                                        </div>
                                    </td>
                                    <td>
                                       
                                        @if($log->user_id)
                                            <a href="{{route('admin.user.details', $log->user_id)}}" class="fw-bold text-dark">{{$log->user?->name}}</a>
                                        @else
                                            <span>{{ translate('Admin')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p> 
                                            {{ array_key_exists('gateway', $log->meta_data) ? ucfirst($log->meta_data['gateway']) : translate("N\A") }}
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ array_key_exists('gateway_name', $log->meta_data) ? $log->meta_data['gateway_name'] : translate("N\A") }}">
                                                <i class="ri-error-warning-line"></i>
                                            </span>
                                        </p>
                                    </td>
                                    <td>
                                        @if($log->campaign_id)
                                            <span class="i-badge pill primary-soft me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Campaign Message") }}">
                                                <i class="ri-megaphone-line"></i>
                                            </span>
                                        @endif
                                        {{ array_key_exists('contact', $log->meta_data) ? $log->meta_data['contact'] : translate("N\A") }} 
                                       
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1 align-items-start">
                                            <span>{{ translate("Initiated At: ") }}{{ $log->created_at ?? 'N\A' }}</span>
                                            <span>{{ translate("Scheduled For: ") }}{{ $log->schedule_at ?? 'N\A' }}</span>
                                            <span>{{ translate("Delivered At: ") }}{{ array_key_exists("delivered_at", $log->meta_data) ? $log->meta_data["delivered_at"] : 'N\A' }}</span>
                                            <span>{{ translate("Updated At: ") }}{{ $log->updated_at ?? 'N\A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @php echo communication_status($log->status) @endphp
                                        @if(App\Enums\CommunicationStatusEnum::FAIL->value == $log->status)
                                            <button data-response-message="{{ $log->response_message }}" class="text-success bg-transparent fs-5 fail-reason">
                                                <i class="ri-file-info-line"></i>
                                            </button>
                                        @endif
                                    </div>
                                    </td>
                                    <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button data-log-id="{{ $log->id }}" data-log-status="{{ $log->status }}" data-log-status-message="{{ translate("Status: ").ucfirst(strtolower(\App\Enums\CommunicationStatusEnum::keyVal($log->status))) }}" data-message="{{ $log->message['message_body'] }}" data-updated-at="{{ Carbon\Carbon::parse($log->updated_at)->toDayDateTimeString() }}" class="icon-btn btn-ghost btn-sm info-soft circle update-log">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button data-log-id="{{ $log->id }}" class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-sms-log" type="button" data-bs-toggle="modal" data-bs-target="#deleteSmsLog">
                                            <i class="ri-delete-bin-line"></i>
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
                @include('admin.partials.pagination', ['paginator' => $logs])
            </div>
        </div>
        </div>
    </main>

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
            <form action="{{route('admin.communication.bulk', ['type' => \App\Enums\ServiceType::SMS->value])}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="action-message">
                        <h5>{{ translate("Are you sure to change the status for the selected data?") }}</h5>
                        <p>{{ translate("This action is irreversable") }}</p>
                    </div>
                    <div class="row mt-3 pending d-none" id="bulkActionDiv">
                        <div class="col-12 mt-3">
                            <div class="form-inner">
                            <label for="chooseMethodBulk" class="form-label">{{ translate("Choose Method") }}</label>
                            <select class="form-select" id="chooseMethodBulk" data-placeholder="{{ translate("Choose a sending method") }}" aria-label="chooseMethodBulk" name="method">
                                <option selected disabled>{{ translate("Select a method") }}</option>
                                <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
                                <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("API Gateway") }}</option>
                            </select>
                            </div>
                        </div>
                        
                    </div>
                    <div id="selectAndroidGatewayBulk" class="row mt-3"></div>
                    <div id="selectApiGatewayBulk" class="row mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                    <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Proceed") }} </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="failReason" tabindex="-1" aria-labelledby="failReason" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered ">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("SMS Failed") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <p class="text-danger text-center response-message text-break"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateLog" tabindex="-1" aria-labelledby="updateLog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered ">
            <div class="modal-content">
                <form action="{{route('admin.communication.status.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update SMS Log Status") }} </h5>
                        <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                            <i class="ri-close-large-line"></i>
                        </button>
                    </div>
                    <div class="modal-body modal-md-custom-height ">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <ul class="information-list">
                                    <li>
                                        <span>{{ translate("SMS Dispatched At: ") }}</span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="text-break text-muted log-updated-at"></span>
                                    </li>
                                    <li>
                                        <span>{{ translate("SMS Message: ") }}</span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="text-break text-muted log-message"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-inner">
                                    <label for="name" class="form-label"> {{ translate('Update Status')}} </label>
                                    <select data-placeholder="{{translate('Select A Delivery Status')}}" class="form-select select2-search" name="status" aria-label="{{translate('Select A Delivery Status')}}">
                                        <option value=""></option>
                                        @foreach(\App\Enums\CommunicationStatusEnum::toArray() as $key => $value)
                                        @if($value != \App\Enums\CommunicationStatusEnum::SCHEDULE->value)
                                            <option value="{{ $value }}">{{ ucfirst(strtolower($key)) }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-3 pending d-none">
                                <div class="form-inner">
                                <label for="chooseMethod" class="form-label">{{ translate("Choose Method") }}</label>
                                <select class="form-select select2-search" id="chooseMethod" data-placeholder="{{ translate("Choose a sending method") }}" aria-label="chooseMethod" name="method">
                                    <option value=""></option>
                                    <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
                                    <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("API Gateway") }}</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div id="selectAndroidGateway" class="row mt-3"></div>
                        <div id="selectApiGateway" class="row mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                        <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade actionModal" id="deleteSmsLog" tabindex="-1" aria-labelledby="deleteSmsLog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
            <div class="modal-header text-start">
                <span class="action-icon danger">
                <i class="bi bi-exclamation-circle"></i>
                </span>
            </div>
            <form action="{{route('admin.communication.delete')}}" method="POST">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id" value="">
                    <div class="action-message">
                        <h5>{{ translate("Are you sure to delete this SMS log") }}</h5>
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
	"use strict";
        select2_search($('.select2-search').data('placeholder'));
		flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        document.addEventListener('DOMContentLoaded', function () {

            const selectElement = document.getElementById('bulk_status');
            const bulkActionDiv = document.getElementById('bulkActionDiv');


            selectElement.addEventListener('change', function () {
                if (selectElement.value == "{{ \App\Enums\CommunicationStatusEnum::PENDING->value }}") {
                    bulkActionDiv.classList.remove('d-none');
                } else {
                    bulkActionDiv.classList.add('d-none');
                }
            });
            $('.bulk-delete-btn').on('click', function () {
                bulkActionDiv.classList.add('d-none');
            });
        });

        $('.fail-reason').on('click', function() {

            const modal = $('#failReason');
            modal.find('.response-message').text($(this).data('response-message'));
            modal.modal('show');
        });
        $('.delete-sms-log').on('click', function() {

            const modal = $('#deleteSmsLog');
            modal.find('input[name=id]').val($(this).data('log-id'));
            modal.modal('show');
        });
        $('.update-log').on('click', function() {

            const modal = $('#updateLog');
            modal.find('.log-message').text($(this).data('message'));
            modal.find('.log-updated-at').text($(this).data('updated-at'));
            modal.find('input[name=id]').val($(this).data('log-id'));
            modal.find('select[name=status]').val($(this).data('log-status')).trigger('change');
            modal.find('select[name=status]').on('change', function() {
                if ($(this).val() == "{{ \App\Enums\CommunicationStatusEnum::PENDING->value }}") {

                    $('.pending').removeClass('d-none');
                } else {

                    $('.pending').addClass('d-none');
                }
            });

            if($(this).data('log-status') == "{{ \App\Enums\CommunicationStatusEnum::PENDING->value }}") {
                $('.pending').removeClass('d-none')
            }
            modal.find('.log-status').text($(this).data('log-status-message'));
            modal.modal('show');
        });
        var androidGateways = @json($android_gateways);

        $('#chooseMethod').change(function() {
            var apiGateways = @json($api_gateways);
            var method = $(this).val();
            var selectAndroidGateway = $('#selectAndroidGateway');
            var selectApiGateway = $('#selectApiGateway');
            selectAndroidGateway.empty();
            selectApiGateway.empty();
            if (method == "{{ \App\Enums\StatusEnum::FALSE->status() }}") {
                var androidGatewayOptions = [
                    { value: '-1', text: 'Automatic' }
                ];

                $.each(androidGateways, function(index, gateway) {
                    androidGatewayOptions.push({
                        value: gateway.id,
                        text: gateway.name,
                        sims: JSON.stringify(gateway.sim_info.map(function(sim) {
                            return {
                                id: sim.id,
                                sim_number: sim.sim_number
                            };
                        }))
                    });
                });

                var androidGatewaySelect = createSelectField(androidGatewayOptions, '{{ translate("Choose an Android Gateway") }}', 'androidGatewaySelect');
                appendField(selectAndroidGateway, '{{ translate("Choose Android Gateway") }}', androidGatewaySelect, 'androidGatewaySelectField', 'col-12');

                androidGatewaySelect.change(function() {
                    var selectedValue = $(this).val();

                    removeField('simSelectField');

                    if (selectedValue != '-1') {
                        $('#androidGatewaySelectField').removeClass('col-12').addClass('col-6');
                        var sims = JSON.parse($(this).find('option:selected').attr('data-sims') || '[]');
                        var simOptions = sims.map(function(sim) {
                            return {
                                value: sim.id,
                                text: sim.sim_number
                            };
                        });
                        var simSelect = createSelectField(simOptions, '{{ translate("Choose a SIM") }}', 'android_gateway_sim_id');
                        appendField(selectAndroidGateway, '{{ translate("Choose SIM") }}', simSelect, 'simSelectField', 'col-6');
                    } else {
                        $('#androidGatewaySelectField').removeClass('col-6').addClass('col-12');
                    }
                });
            } else if (method == "{{ \App\Enums\StatusEnum::TRUE->status() }}") {
                var apiGatewayTypeOptions = [
                    { value: '-1', text: 'Automatic', selected: 'selected' }
                ];

                $.each(apiGateways, function(type, gateways) {
                    apiGatewayTypeOptions.push({
                        value: type,
                        text: textFormat(['_'], type.replace(/^\d+/, ''), ' ')
                    });
                });

                var apiGatewayTypeSelect = createSelectField(apiGatewayTypeOptions, '{{ translate("Select API Gateway Type") }}', 'apiGatewayTypeSelect');
                appendField(selectApiGateway, '{{ translate("Select API Gateway Type") }}', apiGatewayTypeSelect, 'apiGatewayTypeSelectField', 'col-12');

                apiGatewayTypeSelect.change(function() {
                    var selectedType = $(this).val();

                    if (selectedType == '-1') {

                        removeField('apiGatewaySelectField');
                        $('#apiGatewayTypeSelectField').removeClass('col-6').addClass('col-12');
                    } else {
                        removeField('apiGatewaySelectField');
                        $('#apiGatewayTypeSelectField').removeClass('col-12').addClass('col-6');
                        var apiGatewayOptions = Object.entries(apiGateways[selectedType]).map(function([id, name]) {
                            return {
                                value: id,
                                text: name
                            };
                        });
                        var apiGatewaySelect = createSelectField(apiGatewayOptions, '{{ translate("Select API Gateway") }}', 'apiGatewaySelect');
                        appendField(selectApiGateway, '{{ translate("Select API Gateway") }}', apiGatewaySelect, 'apiGatewaySelectField', 'col-6');
                    }
                });
            }
        });

        $('form').submit(function() {

            if(($('#chooseMethodBulk').val() == 0 || $('#chooseMethodBulk').val() == null) && $('#chooseMethod').val() != null && $('#chooseMethod').val() == {{ \App\Enums\StatusEnum::FALSE->status() }}) {

                var androidGatewaySelect  = $('#androidGatewaySelect').val();
                var android_gateway_sim_id  = $('#android_gateway_sim_id').val();
                $('#gateway_id_manual').remove();
                $('#gateway_id_automatic').remove();

                if (androidGatewaySelect != '-1') {

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'gateway_id',
                        id: 'gateway_id_manual',
                        value: android_gateway_sim_id
                    }).appendTo('form');
                } else {

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'gateway_id',
                        id: 'gateway_id_automatic',
                        value: '-1'
                    }).appendTo('form');
                }
            } else if(($('#chooseMethodBulk').val() == 0 || $('#chooseMethodBulk').val() == null) && $('#chooseMethod').val() != null) {

                var apiGatewayTypeSelectField = $('#apiGatewayTypeSelect').val();
                var selectApiGateway = $('#apiGatewaySelect').val();
                $('#gateway_id_manual').remove();
                $('#gateway_id_automatic').remove();

                if (apiGatewayTypeSelectField != '-1') {

                    $('<input>').attr({
                        type: 'hidden',
                        id: 'gateway_id_manual',
                        name: 'gateway_id',
                        value: selectApiGateway
                    }).appendTo('form');
                } else {

                    $('<input>').attr({
                        type: 'hidden',
                        id: 'gateway_id_automatic',
                        name: 'gateway_id',
                        value: -1
                    }).appendTo('form');
                }
            }
        });

        $('#chooseMethodBulk').change(function() {
            var apiGatewaysBulk = @json($api_gateways);
            var methodBulk = $(this).val();
            var selectAndroidGatewayBulk = $('#selectAndroidGatewayBulk');
            var selectApiGatewayBulk = $('#selectApiGatewayBulk');
            selectAndroidGatewayBulk.empty();
            selectApiGatewayBulk.empty();
            if (methodBulk == "{{ \App\Enums\StatusEnum::FALSE->status() }}") {
                var androidGatewayOptionsBulk = [
                    { value: '-1', text: 'Automatic' }
                ];

                $.each(androidGateways, function(index, gateway) {
                    androidGatewayOptionsBulk.push({
                        value: gateway.id,
                        text: gateway.name,
                        sims: JSON.stringify(gateway.sim_info.map(function(sim) {
                            return {
                                id: sim.id,
                                sim_number: sim.sim_number
                            };
                        }))
                    });
                });

                var androidGatewaySelectBulk = createSelectField(androidGatewayOptionsBulk, '{{ translate("Choose an Android Gateway") }}', 'androidGatewaySelectBulk');
                appendField(selectAndroidGatewayBulk, '{{ translate("Choose Android Gateway") }}', androidGatewaySelectBulk, 'androidGatewaySelectFieldBulk', 'col-12');

                androidGatewaySelectBulk.change(function() {
                    var selectedValueBulk = $(this).val();

                    removeField('simSelectField');

                    if (selectedValueBulk != '-1') {
                        $('#androidGatewaySelectFieldBulk').removeClass('col-12').addClass('col-6');
                        var sims = JSON.parse($(this).find('option:selected').attr('data-sims') || '[]');
                        var simOptions = sims.map(function(sim) {
                            return {
                                value: sim.id,
                                text: sim.sim_number
                            };
                        });
                        var simSelect = createSelectField(simOptions, '{{ translate("Choose a SIM") }}', 'android_gateway_sim_id_bulk');
                        appendField(selectAndroidGatewayBulk, '{{ translate("Choose SIM") }}', simSelect, 'simSelectField', 'col-6');
                    } else {
                        $('#androidGatewaySelectFieldBulk').removeClass('col-6').addClass('col-12');
                    }
                });
            } else if (methodBulk == "{{ \App\Enums\StatusEnum::TRUE->status() }}") {
                var apiGatewayTypeOptions = [
                    { value: '-1', text: 'Automatic', selected: 'selected' }
                ];

                $.each(apiGatewaysBulk, function(type, gateways) {
                    apiGatewayTypeOptions.push({
                        value: type,
                        text: textFormat(['_'], type.replace(/^\d+/, ''), ' ')
                    });
                });

                var apiGatewayTypeSelectBulk = createSelectField(apiGatewayTypeOptions, '{{ translate("Select API Gateway Type") }}', 'apiGatewayTypeSelectBulk');
                appendField(selectApiGatewayBulk, '{{ translate("Select API Gateway Type") }}', apiGatewayTypeSelectBulk, 'apiGatewayTypeSelectFieldBulk', 'col-12');

                apiGatewayTypeSelectBulk.change(function() {
                    var selectedType = $(this).val();

                    if (selectedType == '-1') {

                        removeField('apiGatewaySelectFieldBulk');
                        $('#apiGatewayTypeSelectFieldBulk').removeClass('col-6').addClass('col-12');
                    } else {
                        removeField('apiGatewaySelectFieldBulk');
                        $('#apiGatewayTypeSelectFieldBulk').removeClass('col-12').addClass('col-6');
                        var apiGatewayOptions = Object.entries(apiGatewaysBulk[selectedType]).map(function([id, name]) {
                            return {
                                value: id,
                                text: name
                            };
                        });
                        var apiGatewaySelectBulk = createSelectField(apiGatewayOptions, '{{ translate("Select API Gateway") }}', 'apiGatewaySelectBulk');
                        appendField(selectApiGatewayBulk, '{{ translate("Select API Gateway") }}', apiGatewaySelectBulk, 'apiGatewaySelectFieldBulk', 'col-6');
                    }
                });
            }
        });

        $('form').submit(function() {

            if($('#chooseMethodBulk').val() != null && ($('#chooseMethod').val() == '' || $('#chooseMethod').val() == null) && $('#chooseMethodBulk').val() == {{ \App\Enums\StatusEnum::FALSE->status() }}) {

                var androidGatewaySelectBulk = $('#androidGatewaySelectBulk').val();
                var android_gateway_sim_id_bulk = $('#android_gateway_sim_id_bulk').val();
                $('#gateway_id_manual_bulk').remove();
                $('#gateway_id_automatic_bulk').remove();

                if (androidGatewaySelectBulk != '-1') {

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'gateway_id',
                        id: 'gateway_id_manual_bulk',
                        value: android_gateway_sim_id_bulk
                    }).appendTo('form');
                } else {

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'gateway_id',
                        id: 'gateway_id_automatic_bulk',
                        value: '-1'
                    }).appendTo('form');
                }
            } else if($('#chooseMethodBulk').val() != null && ($('#chooseMethod').val() == '' || $('#chooseMethod').val() == null)) {
                var apiGatewayTypeSelectFieldBulk = $('#apiGatewayTypeSelectBulk').val();
                var selectApiGatewayBulk = $('#apiGatewaySelectBulk').val();
                $('#gateway_id_manual').remove();
                $('#gateway_id_automatic').remove();

                if (apiGatewayTypeSelectFieldBulk != '-1') {

                    $('<input>').attr({
                        type: 'hidden',
                        id: 'gateway_id_manual_bulk',
                        name: 'gateway_id',
                        value: selectApiGatewayBulk
                    }).appendTo('form');
                } else {

                    $('<input>').attr({
                        type: 'hidden',
                        id: 'gateway_id_automatic_bulk',
                        name: 'gateway_id',
                        value: '-1'
                    }).appendTo('form');
                }
            }
        });

        function createSelectField(options, placeholder, id) {
            var select = $('<select></select>').addClass('form-select').attr('id', id);
            $.each(options, function(index, option) {
                var opt = $('<option></option>').attr({
                    value: option.value,
                    selected: option.selected,
                    disabled: option.disabled
                }).text(option.text);
                if (option.sims) {
                    opt.attr('data-sims', option.sims);
                }
                select.append(opt);
            });
            return select;
        }
        function appendField(container, labelText, field, fieldId, colClass) {
            var fieldContainer = $('<div></div>').addClass(colClass).attr('id', fieldId);
            var label = $('<label></label>').addClass('form-label').text(labelText);
            fieldContainer.append(label).append(field);
            container.append(fieldContainer);
        }
        function removeField(fieldId) {
            $('#' + fieldId).remove();
        }
</script>
@endpush
