@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('user.layouts.app')
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
                                <a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
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
                    <div class="col-lg-3">
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
            <h4 class="card-title">{{ translate("Communication Logs") }}</h4>
        </div>
        <div class="card-body px-0 pt-0">
            <div class="table-container">
                <table>
                    <thead>
                            <tr>
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
                                    <div class="d-flex flex-column gap-1 align-items-start ">
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
            @include('user.partials.pagination', ['paginator' => $logs])
        </div>
      </div>
    </div>
</main>

@endsection
@section("modal")
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
            <form action="{{route('user.communication.status.update')}}" method="POST" enctype="multipart/form-data">
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
<div class="modal fade actionModal" id="deleteSmsLog" tabindex="-1" aria-labelledby="deleteSmsLog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.communication.delete')}}" method="POST">
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

            modal.modal('show');
        });
</script>
@endpush
