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
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>
                    
                    <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                        <div class="filter-action">
                            <select data-placeholder="{{translate('Select A Delivery Status')}}" class="form-select select2-search" name="status" aria-label="{{translate('Select A Delivery Status')}}">
                                <option value=""></option>
                                @foreach(\App\Enums\CampaignStatusEnum::toArray() as $key => $value)
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
              <h4 class="card-title">{{ translate("Campaigns") }}</h4>
            </div>
            <div class="card-header-right">
                <div class="d-flex gap-3 align-item-center">
                    <button class="bulk-action i-btn btn--danger btn--sm bulk-delete-btn d-none">
                        <i class="ri-delete-bin-6-line"></i>
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
                            <th scope="col">{{ translate("Campaign Name") }}</th>
                            <th scope="col">{{ translate("Message Logs") }}</th>
                            <th scope="col">{{ translate("Time & Date") }}</th>
                            <th scope="col">{{ translate("Status") }}</th>
                            <th scope="col">{{ translate("Options") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            @php
                                $type = strtolower(\App\Enums\ServiceType::getValue($campaign->type));
                            @endphp
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" value="{{$campaign->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$campaign->id}}" />
                                        <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                                    </div>
                                </td>
                                <td>{{ $campaign->name }}
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Created By: ") }}{{ $campaign->user_id ? $campaign->user?->name : translate('Admin')}}">
                                        <i class="ri-question-line"></i>
                                    </span>
                                </td>
                                <td data-label="{{ translate('Message Logs')}}">
                                    <a href="{{route("admin.communication.$type.index", ['campaign_id' => $campaign->id])}}" class="badge badge--primary p-2">
                                        <span class="i-badge info-solid pill">
                                            {{ translate('View all').' ('.$campaign->communicationlog->count().') ' }} <i class="ri-eye-line ms-1"></i>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-start ">
                                        <span>{{ translate("Initiated At: ") }}{{$campaign->created_at ? \Carbon\Carbon::parse($campaign->created_at)->toFormattedDateString() : 'N\A'}}</span>
                                        
                                        <span>{{ translate("Scheduled For: ") }}{{ $campaign->schedule_at ? \Carbon\Carbon::parse($campaign->schedule_at)->toFormattedDateString() : 'N\A' }}</span>
                                        <span>{{ translate("Last Execution: ") }}{{ $campaign->updated_at ?? 'N\A' }}</span>
                                        <span class="text-dark fw-medium">{{ $campaign->repeat_time == 0 ? translate("Executes: Once") : translate("Gets delivered in every ").$campaign->repeat_time. " ". ucfirst(strtolower(\App\Enums\CampaignRepeatEnum::getValue($campaign->repeat_format)) ) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php echo campaign_status($campaign->status) @endphp
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a class="icon-btn btn-ghost btn-sm success-soft circle" href="{{ route("admin.communication.$type.campaign.edit", $campaign->id) }}">
                                            <i class="ri-edit-line"></i>
                                            <span class="tooltiptext"> {{ translate("Edit Campaign") }} </span>
                                        </a>
                                        <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-campaign"
                                                type="button"
                                                data-plan-id="{{ $campaign->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCampaign">
                                            <i class="ri-delete-bin-line"></i>
                                            <span class="tooltiptext"> {{ translate("Delete Membership Plan") }} </span>
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
            @include('admin.partials.pagination', ['paginator' => $campaigns])
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
            <form action="{{route('admin.communication.sms.campaign.bulk')}}" method="POST" enctype="multipart/form-data">
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

    <div class="modal fade actionModal" id="deleteCampaign" tabindex="-1" aria-labelledby="deleteCampaign" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
            <div class="modal-header text-start">
                <span class="action-icon danger">
                <i class="bi bi-exclamation-circle"></i>
                </span>
            </div>
            <form action="{{route('admin.communication.sms.campaign.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <div class="action-message">
                        <h5>{{ translate("Are you sure to delete this campaign?") }}</h5>
                        <p>{{ translate("By clicking on 'Delete', you will permanently remove all the related pending logs create by the campaign as well.") }}</p>
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

        $('.fail-reason').on('click', function() {

            const modal = $('#failReason');
            modal.find('.response-message').text($(this).data('response-message'));
            modal.modal('show');
        });
        $('.delete-campaign').on('click', function() {

            const modal = $('#deleteCampaign');
            modal.find('input[name=id]').val($(this).data('plan-id'));
            modal.modal('show');
        });
        $('.update-log').on('click', function() {

            const modal = $('#updateLog');
            modal.find('.log-message').text($(this).data('message'));
            modal.find('.log-updated-at').text($(this).data('updated-at'));
            modal.find('input[name=id]').val($(this).data('log-id'));
            modal.find('select[name=status]').val($(this).data('log-status')).trigger('change');
            modal.find('.log-status').text($(this).data('log-status-message'));
            modal.modal('show');
        });
</script>
@endpush
