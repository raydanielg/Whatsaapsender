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
                            <li class="breadcrumb-item">
                                <a href="{{ route("user.gateway.sms.android.index") }}">{{ translate("Android gateways") }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="table-filter mb-4">
            <form action="{{route(Route::currentRouteName(), $id)}}" class="filter-form">
                @csrf
                <div class="row g-3">
                    <div class="col-xxl-3 col-xl-4 col-lg-4">
                        <div class="filter-search">
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Filter by sim number") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-7 col-lg-8 offset-xxl-3 offset-xl-1">
                        <div class="filter-action">
                            <select data-placeholder="{{translate('Select A Sim Status')}}" class="form-select select2-search" name="status" aria-label="Default select example">
                                <option value=""></option>
                                <option value="{{ \App\Enums\AndroidApiSimEnum::ACTIVE->value }}">{{ translate("Active") }}</option>
                                <option value="{{ \App\Enums\AndroidApiSimEnum::INACTIVE->value }}">{{ translate("Inactive") }}</option>
                            </select>
                            <div class="input-group">
                                <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                                <span class="input-group-text" id="filterByDate">
                                    <i class="ri-calendar-2-line"></i>
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="filter-action-btn ">
                                    <i class="ri-equalizer-line"></i> {{ translate("Filters") }}
                                </button>
                                
                                <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName(), $id)}}">
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
                    <h4 class="card-title">{{ translate("SIM List") }}</h4>
                </div>
                <div class="card-header-right">
                    <div class="d-flex gap-3 align-item-center">
                        <button class="bulk-action i-btn btn--danger btn--sm bulk-delete-btn d-none">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>

                        <div class="bulk-action form-inner d-none">
                            <select class="form-select" data-show="5" id="bulk_status" name="status">
                                <option disabled selected>{{ translate("Select a status") }}</option>
                                <option value="{{ \App\Enums\AndroidApiSimEnum::INACTIVE->value }}">{{ translate("Inactive") }}</option>
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
                                <th scope="col">{{ translate("Android Gateway Name") }}</th>
                                <th scope="col">{{ translate("SIM Number") }}</th>
                                <th scope="col">{{ translate("Time Interval") }}</th>
                                <th scope="col">{{ translate("Send SMS") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sims as $sim)
		                    <tr class="@if($loop->even)@endif">
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" value="{{$sim->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$sim->id}}" />
                                        <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                                    </div>
                                </td>
			                    <td data-label=">{{translate('Android Gateway Name')}}">
			                    	{{$sim->androidGateway->name}}
			                    </td>

			                    <td data-label=">{{translate('SIM Number')}}">
			                    	{{$sim->sim_number}}
			                    </td>

			                    <td data-label=">{{translate('Time Interval')}}">
			                    	{{$sim->time_interval}}
			                    </td>

			                    <td data-label=">{{translate('Send SMS')}}">
			                    	{{$sim->send_sms}}
			                    </td>

			                    <td data-label=">{{translate('Status')}}">
			                    	<div class="d-flex align-items-center gap-2">
                                        @php echo android_sim_status($sim->status) @endphp
                                    </div>
			                    </td>

			                     <td data-label="{{translate('Option')}}">
                                    <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-android-sim"
                                        type="button"
                                        data-sim-id="{{ $sim->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteAndroidSim">
                                        <i class="ri-delete-bin-line"></i>
                                        <span class="tooltiptext"> {{ translate("Delete Android SIM") }} </span>
                                    </button>
								</td>
		                    </tr>
		                @empty
		                	<tr>
		                		<td class="text-muted text-center" colspan="100%">{{translate('No Data Found')}}</td>
		                	</tr>
		                @endforelse
                        </tbody>
                    </table>
                </div>
                @include('user.partials.pagination', ['paginator' => $sims])
            </div>
        </div>
    </div>
</main>

<div class="modal fade actionModal" id="deleteAndroidSim" tabindex="-1" aria-labelledby="deleteAndroidSim" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.gateway.sms.android.sim.delete')}}" method="POST">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this sim?") }}</h5>
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

<div class="modal fade actionModal" id="bulkAction" tabindex="-1" aria-labelledby="bulkAction" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.gateway.sms.android.sim.bulk')}}" method="POST" enctype="multipart/form-data">
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
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush


@push('script-push')
<script>
	(function($){
		"use strict";
        select2_search($('.select2-search').data('placeholder'));
		$('.delete-android-sim').on('click', function(){
			var modal = $('#deleteAndroidSim');
			modal.find('input[name=id]').val($(this).data('sim-id'));
			modal.modal('show');
		});
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $('.checkAll').click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
	})(jQuery);
</script>
@endpush



