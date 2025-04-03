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
                <h4 class="card-title">{{ $title }}</h4>
            </div>
            <div class="card-header-right">
                <div class="d-flex gap-3 align-item-center">
                    <a class="i-btn btn--info btn--sm" href="{{ site_settings("app_link") }}">
                        <i class="ri-download-line"></i> {{ translate("Download APK File") }}
                    </a>
                    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                        <button class="i-btn btn--primary btn--sm" type="button" data-bs-toggle="modal" data-bs-target="#addAndroidGateway">
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
                            <th scope="col">{{ translate("Name") }}</th>
                            @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                <th scope="col">{{ translate("Password") }}</th>
                            @endif
                            <th scope="col">{{ translate("SIM List") }}</th>
                            <th scope="col">{{ translate("Status") }}</th>
                            @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                <th scope="col">{{ translate("Option") }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($androids as $android)
                            <tr class="@if($loop->even)@endif">

                                <td data-label="{{ translate('Name') }}">
                                    {{$android->name}}
                                </td>
                                @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <td data-label="{{ translate('Password') }}">
                                        <span id="password-display-{{$loop->index}}" class="password-display">
                                            ********
                                        </span>
                                        <i id="toggle-password-{{$loop->index}}" class="ri-eye-line" style="cursor: pointer;" onclick="togglePasswordVisibility({{$loop->index}})"></i>
                                        <input type="hidden" id="actual-password-{{$loop->index}}" value="{{ $android->show_password }}" />
                                    </td>
                                @endif
                                <td data-label="{{ translate('SIM List')}}">
                                    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <a href="{{route('user.gateway.sms.android.sim.index', $android->id)}}" class="badge badge--primary p-2">
                                        <span class="i-badge info-solid pill">
                                            {{ translate('View all').' ('.count($android->simInfo).') ' }} <i class="ri-eye-line ms-1"></i>
                                        </span>
                                    </a>
                                    @else
                                        <p class="badge badge--primary p-2">
                                            <span class="i-badge info-solid pill">
                                                {{ translate('Available Sim:').' ('.count($android->simInfo).') ' }}
                                            </span>
                                        </p>
                                    @endif
                                </td>

                                <td data-label="{{ translate('Status')}}">
                                    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <div class="switch-wrapper checkbox-data">
                                        <input {{ $android->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                type="checkbox"
                                                class="switch-input statusUpdate"
                                                data-id="{{ $android->id }}"
                                                data-column="status"
                                                data-value="{{ $android->status }}"
                                                data-route="{{route('user.gateway.sms.android.status.update')}}"
                                                id="{{ 'status_'.$android->id }}"
                                                name="is_default"/>
                                        <label for="{{ 'status_'.$android->id }}" class="toggle">
                                            <span></span>
                                        </label>
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="i-badge dot {{ $android->status == App\Enums\StatusEnum::FALSE->status() ? 'danger' : 'success' }}-soft pill">{{ $android->status == App\Enums\StatusEnum::FALSE->status() ? translate("Inactive") : translate("Active")}}</span>
                                    </div>
                                    @endif
                                </td>
                                @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                    <td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle update-android-gateway"
                                                    type="button"
                                                    data-android-id="{{ $android->id }}"
                                                    data-android-name="{{ $android->name }}"
                                                    data-android-password="{{ $android->show_password }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateAndroidGateway">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update Android Gateway") }} </span>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-android-gateway"
                                                    type="button"
                                                    data-android-id="{{ $android->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteAndroidGateway">
                                                <i class="ri-delete-bin-line"></i>
                                                <span class="tooltiptext"> {{ translate("Delete Android Gateway") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
			                @empty
			                	<tr>
			                		<td class="text-muted text-center" colspan="100%">{{ translate('No Data Found') }}</td>
			                	</tr>
			                @endforelse
                    </tbody>
                </table>
            </div>
            @include('user.partials.pagination', ['paginator' => $androids])
        </div>
    </div>
</div>

@endsection

@section('modal')

@if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
<div class="modal fade" id="addAndroidGateway" tabindex="-1" aria-labelledby="addAndroidGateway" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.gateway.sms.android.store')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Android Gateway") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Android Gateway Name')}} </label>
                                <input type="text" id="name_add" name="name" placeholder="{{ translate('Enter android gateway name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="password" class="form-label"> {{ translate('Android Gateway Password')}} </label>
                                <input type="password" id="password_add" name="password" placeholder="{{ translate('Enter android gateway password')}}" class="form-control" aria-label="password"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="password_confirmation" class="form-label"> {{ translate('Confirm Android Gateway Password')}} </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ translate('confirm android gateway password')}}" class="form-control" aria-label="password_confirmation"/>
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

<div class="modal fade" id="updateAndroidGateway" tabindex="-1" aria-labelledby="updateAndroidGateway" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.gateway.sms.android.update')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Android Gateway") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Android Gateway Name')}} </label>
                                <input type="text" id="name_update" name="name" placeholder="{{ translate('Enter android gateway name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="password" class="form-label"> {{ translate('Android Gateway Password')}} </label>
                                <input type="text" id="password_update" name="password" placeholder="{{ translate('Enter android gateway password')}}" class="form-control" aria-label="password"/>
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

<div class="modal fade actionModal" id="deleteAndroidGateway" tabindex="-1" aria-labelledby="deleteAndroidGateway" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.gateway.sms.android.delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this Android Gateway?") }}</h5>
                    <p>{{ translate("By clicking on 'Delete', you will permanently remove the android gateway from the application") }}</p>
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


@push('script-push')
<script>
    function togglePasswordVisibility(index) {
        const passwordDisplay = document.getElementById('password-display-' + index);
        const actualPassword  = document.getElementById('actual-password-' + index).value;
        const toggleIcon      = document.getElementById('toggle-password-' + index);
        
        if (passwordDisplay.innerText === '********') {

            passwordDisplay.innerText = actualPassword;
            toggleIcon.classList.remove('ri-eye-line');
            toggleIcon.classList.add('ri-eye-off-line');
        } else {
            
            passwordDisplay.innerText = '********';  
            toggleIcon.classList.remove('ri-eye-off-line');
            toggleIcon.classList.add('ri-eye-line');
        }
    }
</script>
<script>
	(function($){
		"use strict";

		$('.update-android-gateway').on('click', function(){
            const modal = $('#updateAndroidGateway');
            modal.find('input[name=id]').val($(this).data('android-id'));
			modal.find('input[name=name]').val($(this).data('android-name'));
			modal.find('input[name=password]').val($(this).data('android-password'));
			modal.modal('show');
		});

		$('.delete-android-gateway').on('click', function(){
			var modal = $('#deleteAndroidGateway');
			modal.find('input[name=id]').val($(this).data('android-id'));
			modal.modal('show');
		});

        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $('.add-android-gateway').on('click', function() {

            const modal = $('#addAndroidGateway');
            modal.modal('show');
        });
	})(jQuery);
</script>
@endpush
