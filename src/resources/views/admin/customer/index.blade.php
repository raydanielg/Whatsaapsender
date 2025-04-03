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
                                <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name or email") }}" />
                                <span><i class="ri-search-line"></i></span>
                            </div>
                        </div>
                        <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                            <div class="filter-action">
                                <select data-placeholder="{{translate('Select A Verification Status')}}" class="form-select select2-search" name="email_verified_status" aria-label="Default select example">
                                    <option value=""></option>
                                    <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Verified") }}</option>
                                    <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Unverified") }}</option>
                                </select>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                                    <span class="input-group-text" id="filterByDate">
                                        <i class="ri-calendar-2-line"></i>
                                    </span>
                                </div>

                                <button type="submit" class="filter-action-btn ">
                                    <i class="ri-menu-search-line"></i> {{ translate("Filter") }}
                                </button>
                                <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                                    <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <h4 class="card-title">{{ translate("User List") }}</h4>
                    </div>
                    <div class="card-header-right">
                        <button class="i-btn btn--primary btn--sm add-user" type="button" data-bs-toggle="modal" data-bs-target="#addUser">
                            <i class="ri-add-fill fs-16"></i> {{ translate("Add User") }}
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">{{ translate("Customer") }}</th>
                                    <th scope="col">{{ translate("Credits") }}</th>
                                    <th scope="col">{{ translate("Joined") }}</th>
                                    <th scope="col">{{ translate("Status") }}</th>
                                    <th scope="col">{{ translate("Option") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>
                                           <div class="d-flex align-items-center gap-2 ">
                                                <span class="user-logo flex-shrink-0">
                                                    <img src="{{showImage(filePath()['profile']['user']['path'].'/'.$customer->image, filePath()['profile']['user']['size'])}}" alt="{{ $customer->username }}">
                                                </span>
                                                <div class="lh-1">
                                                    <p class="text-dark fs-14 fw-semibold mb-1">{{ $customer->name }}</p>
                                                    <a class="text-primary fs-12" >{{ $customer->email }}</a>
                                                </div>
                                           </div>
                                        </td>
                                        <td>
                                           <div class="d-flex align-items-center gap-2 ">
                                                <div class="lh-1">
                                                    <p class="text-dark fs-10 fw-medium mb-1">{{ translate('Email Credits: ') }} {{ $customer->email_credit >= 0 ? $customer->email_credit : translate('Unlimited') }}</p>
                                                    <p class="text-dark fs-10 fw-medium mb-1">{{ translate('SMS Credits: ') }} {{ $customer->sms_credit >= 0 ? $customer->sms_credit : translate('Unlimited') }}</p>
                                                    <p class="text-dark fs-10 fw-medium mb-1">{{ translate('WhatsApp Credits: ') }} {{ $customer->whatsapp_credit >= 0 ? $customer->whatsapp_credit : translate('Unlimited') }}</p>
                                                    
                                                </div>
                                           </div>
                                        </td>
                                        <td>
                                            <span>{{ $customer?->created_at->diffForHumans() }}</span>
                                            <p> {{ $customer?->created_at->toDayDateTimeString() }}</p>
                                        </td>
                                        <td>
                                            <span class="i-badge dot {{$customer->status == \App\Enums\StatusEnum::TRUE->status() ? 'success' : 'danger'}}-soft pill">{{$customer->status == \App\Enums\StatusEnum::TRUE->status() ? 'Active' : 'Banned'}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                               
                                                <a class="icon-btn btn-ghost btn-sm dark-soft circle modify-credits"
                                                    type="button"
                                                    data-uid = "{{ $customer->uid }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modifyCredit">
                                                    <i class="ri-hand-coin-line"></i>
                                                    <span class="tooltiptext"> {{ translate("Add/Deduct Credits") }} </span>
                                                </a>
                                                <a href="{{route('admin.user.details', $customer->id)}}" target="_blank" class="icon-btn btn-ghost btn-sm info-soft circle text-danger">
                                                    
                                                    <i class="ri-profile-line"></i>
                                                    <span class="tooltiptext"> {{ translate("View: ").$customer->name.translate(" profile") }} </span>
                                                </a>
                                                <a href="{{route('admin.user.login', $customer->uid)}}" target="_blank" class="icon-btn btn-ghost btn-sm success-soft circle">
                                                    <i class="ri-logout-box-r-line"></i>
                                                    <span class="tooltiptext"> {{ translate("Login as: ").$customer->name }} </span>
                                                </a>
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
                    @include('admin.partials.pagination', ['paginator' => $customers])
                </div>
            </div>
        </div>
    </main>
@endsection
@section('modal')
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUser" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.user.store')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add New User") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Name')}}<span class="text-danger">*</span> </label>
                                <input type="text" id="name" name="name" placeholder="{{ translate('Enter user\'s name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="email" class="form-label"> {{ translate('Email Address')}}<span class="text-danger">*</span> </label>
                                <input type="text" id="email" name="email" placeholder="{{ translate('Enter user\'s Email address')}}" class="form-control" aria-label="email"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="password" class="form-label"> {{ translate('User Password')}}<span class="text-danger">*</span> </label>
                                <input type="password" id="password" name="password" class="form-control" aria-label="password" placeholder="{{ translate('Enter a password for this user')}}"/>
                                <p class="form-element-note text-danger">{{ translate("User will use this password for login")}}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <div class="form-inner">
                                    <label for="password_confirmation" class="form-label"> {{ translate('Confirm Password')}}<span class="text-danger">*</span> </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" aria-label="password_confirmation" placeholder="{{ translate('Confirm the password')}}"/>
                                    <p class="form-element-note text-danger">{{ translate("Please re-type the password for confirmation")}}</p>
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
<div class="modal fade" id="modifyCredit" tabindex="-1" aria-labelledby="modifyCredit" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.user.modify.credit')}}" method="POST">
                @csrf
                <input type="hidden" name="uid" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Modify User's Credits") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-inner">
                                <label for="type" class="form-label">{{ translate("Select Credit Modification Type") }}<span class="text-danger">*</span></label>
                                <select data-placeholder="{{translate('Select a modification type')}}" class="form-select select2-search" data-show="5" id="type" name="type">
                                    <option value=""></option>
                                    <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Add Credits") }}</option>
                                    <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Deduct Credits") }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="sms_credit" class="form-label"> {{ translate("User's SMS Credit")}} </label>
                                <input type="text" id="sms_credit" name="sms_credit" placeholder="{{ translate('Enter sms credit amount')}}" class="form-control" aria-label="sms_credit"/>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="email_credit" class="form-label">{{ translate("User's Email Credit")}} </label>
                                <input type="text" id="email_credit" name="email_credit" placeholder="{{ translate('Enter email credit amount')}}" class="form-control" aria-label="email_credit"/>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="whatsapp_credit" class="form-label"> {{ translate("User's WhatsApp Credit")}} </label>
                                <input type="text" id="whatsapp_credit" name="whatsapp_credit" placeholder="{{ translate('Enter whatsapp credit amount')}}" class="form-control" aria-label="whatsapp_credit"/>

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
            select2_search($('.select2-search').data('placeholder'));
            $('.add-user').on('click', function() {
                const modal = $('#addUser');
			    modal.modal('show');
            });
            $('.modify-credits').on('click', function(){
                var modal = $('#modifyCredit');
                modal.find('input[name=uid]').val($(this).data('uid'));
                modal.modal('show');
            });

            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                mode: "range",
            });
        })(jQuery);
    </script>
@endpush
