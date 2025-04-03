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
                        <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name, contact number or email") }}" />
                        <span><i class="ri-search-line"></i></span>
                    </div>
                </div>
                
                <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                    <div class="filter-action">
                        <select data-placeholder="{{translate('Select Email Verification Status')}}" class="form-select select2-search" name="email_verification" aria-label="Default select example">
                            <option value=""></option>
                            <option {{ request()->email_verification == 'verified' ? 'selected' : ''  }} value="{{ 'verified' }}">{{ translate("Verified") }}</option>
                            <option {{ request()->email_verification == 'unverified' ? 'selected' : ''  }} value="{{ 'unverified' }}">{{ translate("Unverified") }}</option>
                        </select>
                        <select data-placeholder="{{translate('Select A Status')}}" class="form-select select2-search" name="status" aria-label="Default select example">
                            <option value=""></option>
                            <option {{ request()->status == \App\Enums\Common\Status::ACTIVE->value ? 'selected' : ''  }} value="{{ \App\Enums\Common\Status::ACTIVE->value }}">{{ translate("Active") }}</option>
                            <option {{ request()->status == \App\Enums\Common\Status::INACTIVE->value ? 'selected' : ''  }} value="{{ \App\Enums\Common\Status::INACTIVE->value }}">{{ translate("Inactive") }}</option>
                        </select>
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
                            <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName(), ['id' => $groupId])}}">
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
                            <option value="{{ \App\Enums\Common\Status::ACTIVE->value }}">{{ translate("Active") }}</option>
                            <option value="{{ \App\Enums\Common\Status::INACTIVE->value }}">{{ translate("Inactive") }}</option>
                        </select>
                    </div>
                    <a class="i-btn btn--info btn--sm export-csv">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Export Contacts") }}
                    </a>
                    
                    <a class="i-btn btn--primary btn--sm" href="{{ route("user.contact.create.with_group", ['group_id' => $groupId]) }}">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Create Contact") }}
                    </a>
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
                  <th scope="col">{{ translate("Contact Name") }}</th>
                  <th scope="col">{{ translate("Group") }}</th>
                  <th scope="col">{{ translate("SMS") }}</th>
                  <th scope="col">{{ translate("WhatsApp") }}</th>
                  <th scope="col">{{ translate("Email") }}</th>
                  <th scope="col">{{ translate("Status") }}</th>
                  <th scope="col">{{ translate("Option") }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" value="{{$contact->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$contact->id}}" />
                                <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                            </div>
                        </td>
                        <td>
                            {{ $contact->first_name || $contact->last_name ? $contact->first_name. ' '. $contact->last_name : translate("N\A") }}
                        </td>
                        <td data-label="{{ translate('Group')}}">
                            <a href="{{route('user.contact.group.index', $contact->group_id)}}" class="badge badge--primary p-2">
                                <span class="i-badge info-solid pill">
                                    {{translate("View: ").$contact->group?->name}} <i class="ri-eye-line ms-1"></i>
                                </span>
                            </a>
                        </td>
                        <td>{{ $contact->sms_contact ?? translate("N\A") }}</td>
                        <td>{{ $contact->whatsapp_contact ?? translate("N\A") }}</td>
                        <td> 
                            @if(site_settings('email_contact_verification') == \App\Enums\StatusEnum::TRUE->status())
                            <div class="d-flex align-items-center gap-2">
                                <div id="spinner-{{ $contact->uid }}" class="spinner-border text-primary d-none" role="status" style="width: 1rem; height: 1rem;">
                                    <span class="visually-hidden"></span>
                                </div>
                                <i id="verify-icon-{{ $contact->uid }}" class="ri-loop-left-line cursor-pointer" onclick="verifyEmailAndUpdate('{{ route('user.verify.email') }}', '{{ $contact->uid }}', '{{ $contact->email_contact }}')"></i>
                                <span class="i-badge {{$contact->email_verification == 'verified' ? 'success' : 'danger'}}-soft pill">
                                    @if($contact->email_verification == 'verified') 
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{translate($contact->email_verification)}}" class="ri-check-double-line"></i>
                                    @else
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{translate($contact->email_verification)}}" class="ri-close-line"></i>
                                    @endif
                                </span>
                                <p>{{ $contact->email_contact ?? translate("N\A") }}</p>
                            </div>
                            @else
                                <p>{{ $contact->email_contact ?? translate("N\A") }}</p>
                            @endif
                        </td>
                        <td data-label="{{ translate('Status')}}">
                            <div class="switch-wrapper checkbox-data">
                                <input {{ $contact->status == \App\Enums\Common\Status::ACTIVE->value ? 'checked' : '' }}
                                        type="checkbox"
                                        class="switch-input statusUpdate"
                                        data-id="{{ $contact->id }}"
                                        data-column="status"
                                        data-value="{{ $contact->status == \App\Enums\Common\Status::ACTIVE->value ? \App\Enums\Common\Status::INACTIVE->value : \App\Enums\Common\Status::ACTIVE->value}}"
                                        data-route="{{route('user.contact.status.update')}}"
                                        id="{{ 'status_'.$contact->id }}"
                                        name="status"/>
                                <label for="{{ 'status_'.$contact->id }}" class="toggle">
                                    <span></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @php
                                $data = [];
                                $data["name"] = $contact->first_name." ".$contact->last_name;
                                if ($contact->whatsapp_contact !== null) {
                                    $data["whatsapp_number"] = $contact->whatsapp_contact;
                                }

                                if ($contact->sms_contact !== null) {
                                    $data["sms_contact"] = $contact->sms_contact;
                                }

                                if ($contact->email_contact !== null) {
                                    $data["email_contact"] = $contact->email_contact;
                                }

                                if($contact->meta_data) {

                                    foreach($contact->meta_data as $key => $value) {
                                        $data[$key] = $value;
                                    }
                                }

                                $data["contact_added"]   = Carbon\Carbon::parse($contact->created_at)->toDayDateTimeString();
                                $data["contact_updated"] = Carbon\Carbon::parse($contact->updated_at)->toDayDateTimeString();

                            @endphp
                                <button class="icon-btn btn-ghost btn-sm info-soft circle text-info quick-view"
                                        type="button"
                                        data-contact_information="{{json_encode($data)}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#quick_view">
                                        <i class="ri-information-line"></i>
                                    <span class="tooltiptext"> {{ translate("Quick View") }} </span>
                                </button>

                                <button class="icon-btn btn-ghost btn-sm success-soft circle update-contact" data-bs-toggle="modal" data-bs-target="#updateContact" href="javascript:void(0)"
                                    data-uid              ="{{$contact->uid}}"
                                    data-first_name       ="{{$contact->first_name}}"
                                    data-last_name        ="{{$contact->last_name}}"
                                    data-group_id         ="{{$contact->group_id}}"
                                    data-attributes       ="{{json_encode($contact->meta_data)}}"
                                    data-whatsapp_contact ="{{$contact->whatsapp_contact}}"
                                    data-email_contact    ="{{$contact->email_contact}}"
                                    data-sms_contact      ="{{$contact->sms_contact}}"
                                    data-status           ="{{$contact->status}}">
                                    <i class="ri-edit-line"></i></button>
                                        <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-contact"
                                        type="button"
                                        data-url        = "{{route('user.contact.destroy', ['uid' => $contact->uid])}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteContact">
                                    <i class="ri-delete-bin-line"></i>
                                <span class="tooltiptext"> {{ translate("Delete Single Contact") }} </span>
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
          @include('user.partials.pagination', ['paginator' => $contacts])
        </div>
      </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade" id="quick_view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate("Email Gateway Information") }}</h5>
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

<div class="modal fade actionModal" id="bulkAction" tabindex="-1" aria-labelledby="bulkAction" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.contact.bulk')}}" method="POST" enctype="multipart/form-data">
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

<div class="modal fade" id="updateContact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.contact.store')}}" method="POST">
                @csrf
                <input type="hidden" name="uid">
                <input type="hidden" name="single_contact" value="true">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Contact") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <label class="form-label" for="group_id">{{ translate('Select a Group')}}</label>
                            <select data-placeholder="{{translate('Select a group')}}" class="form-select select2-search" name="group_id" id="group_id">
                                <option value=""></option>
                                @foreach($groups as $name => $id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="first_name" class="form-label"> {{ translate('Contact First Name')}} <sup class="text--danger">*</sup></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder=" {{ translate('Enter First Name')}}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="last_name" class="form-label"> {{ translate('Contact Last Name')}} <sup class="text--danger">*</sup></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder=" {{ translate('Enter Last Name')}}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="whatsapp_contact" class="form-label"> {{ translate('WhatsApp Number')}}</label>
                            <input type="text" class="form-control" id="whatsapp_contact" name="whatsapp_contact" placeholder=" {{ translate('Enter WhatsApp Number')}}">
                        </div>
                        <div class="col-lg-6">
                            <label for="sms_contact" class="form-label"> {{ translate('SMS Number')}}</label>
                            <input type="text" class="form-control" id="sms_contact" name="sms_contact" placeholder=" {{ translate('Enter SMS Number')}}">
                        </div>
                        <div class="col-lg-12">
                            <label for="email_contact" class="form-label"> {{ translate('Email Address')}}</label>
                            @if(site_settings('email_contact_verification') == \App\Enums\StatusEnum::TRUE->status())
                                <div class="input-group">
                                    <input type="text" 
                                        class="form-control check-email-address" 
                                        id="email_contact" 
                                        name="email_contact" 
                                        placeholder="{{ translate('Enter Email Address') }}" 
                                        data-url = "{{ route('user.verify.email') }}">
                                    <span class="input-group-text" 
                                        role="button"
                                        data-verified-button-text="{{ translate('Verified') }}"
                                        data-verified-button-icon="ri-check-double-fill"
                                        data-unverified-button-text="{{ translate('Unverified') }}"
                                        data-unverified-button-icon="ri-close-line"
                                        id="verify_email_button"
                                        onclick="verifyEmailHandler()">
                                        <span id="verify_email_text">{{ translate('Verify') }}</span>
                                        <span id="loading_spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            @else
                                <input type="text" class="form-control check-email-address" id="email_contact" name="email_contact" placeholder="{{ translate('Enter Email Address') }}" >
                            @endif
                        </div>
                    </div>

                    <div class="row addExtraAttribute"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade actionModal" id="deleteContact" tabindex="-1" aria-labelledby="deleteContact" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.contact.destroy')}}" method="POST" id="singleContactDelete">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div class="modal-body">
                <input type="hidden" name="uid" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this contact?") }}</h5>
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
@include('partials.email_verify')
@include('partials.email_check')
@include('partials.export_csv', ['csv_data' => $csv_data])
<script type="text/javascript">

    function verifyEmailAndUpdate(url, uid, email) {

        const spinner = document.getElementById(`spinner-${uid}`);
        const icon = document.getElementById(`verify-icon-${uid}`);
        spinner.classList.remove('d-none'); 
        icon.classList.add('d-none'); 
        verifyEmailAjax(url, email, function(response) {
        
            notify(response.status ? 'success' : 'error', response.message);
            if (response.status) {
                updateEmailVerification(uid, true);
            } else {
                updateEmailVerification(uid, false);
            }
        }).always(function() {

            spinner.classList.add('d-none'); 
            icon.classList.remove('d-none');
        });
    }

    function updateEmailVerification(uid, email_verification) {
        $.ajax({
            url: '{{ route("user.contact.update.email.verification") }}',
            method: 'POST',
            data: {
                uid: uid,
                email_verification: email_verification,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status) {
                    
                    notify('success', response.message);
                    
                } else {

                    notify('error', response.message);
                }
                if(response.reload) {
                    
                    window.location.reload()
                }
            },
            error: function(xhr) {
                notify('error', `{{translate("An error occurred while updating email verification.")}}`);
            }
        });
    }
</script>
<script>
    (function($){
        "use strict";

        select2_search($('.select2-search').data('placeholder'));
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $('.update-contact').on('click', function() {
            var modal = $('#updateContact');
            modal.find('.addExtraAttribute').empty();
            modal.find('input[name=uid]').val($(this).data('uid'));
            modal.find('input[name=first_name]').val($(this).data('first_name'));
            modal.find('input[name=last_name]').val($(this).data('last_name'));
            modal.find('input[name=whatsapp_contact]').val($(this).data('whatsapp_contact'));
            modal.find('input[name=sms_contact]').val($(this).data('sms_contact'));
            modal.find('input[name=email_contact]').val($(this).data('email_contact'));
            modal.find('select[name=group_id]').val($(this).data('group_id')).trigger('change');

            var attributes = $(this).data('attributes');
            var filtered_meta_data = JSON.parse('{!! json_encode($filtered_meta_data) !!}');
            console.log(attributes)
            console.log(filtered_meta_data)
            $.each(filtered_meta_data, function (key, meta) {

                if (meta.status !== true) return;

                var value = attributes[key] ? attributes[key].value : '';

                if (meta.type == {{ App\Enums\ContactAttributeEnum::DATE->value }}) {
                    modal.find('.addExtraAttribute').append(
                        `<div class="mt-3 col-lg-6">
                            <label for="${key}" class="form-label">{{ textFormat(["_"], '${convertToTitleCase(key)}')}}</label>
                            <input type="date" value="${value}" class="static-flatpicker form-control" 
                                name="meta_data[${key}::${meta.type}]" placeholder="Enter {{ textFormat(["_"], '${convertToTitleCase(key)}') }}">
                        </div>`
                    );
                }

                if (meta.type == {{ App\Enums\ContactAttributeEnum::BOOLEAN->value }}) {
                    
                    modal.find('.addExtraAttribute').append(
                        `<div class="mt-3 col-lg-6">
                            <label for="${key}" class="form-label">{{ textFormat(["_"], '${convertToTitleCase(key)}')}}</label>
                            <select class="form-select" name="meta_data[${key}::${meta.type}]" required>
                                <option ${value != "true" || value != "false" ? 'selected' : ''} disabled>-- Select An Option --</option>
                                <option ${value == "true" ? 'selected' : ''} value="true">{{ translate("Yes") }}</option>
                                <option ${value == "false" ? 'selected' : ''} value="false">{{ translate("No") }}</option>
                            </select>
                        </div>`
                    );
                }

                if (meta.type == {{ App\Enums\ContactAttributeEnum::NUMBER->value }}) {
                    modal.find('.addExtraAttribute').append(
                        `<div class="mt-3 col-lg-6">
                            <label for="${key}" class="form-label">{{ textFormat(["_"], '${convertToTitleCase(key)}')}}</label>
                            <input type="number" value="${value}" class="form-control" 
                                name="meta_data[${key}::${meta.type}]" placeholder="Enter {{ textFormat(["_"], '${convertToTitleCase(key)}') }}">
                        </div>`
                    );
                }

                if (meta.type == {{ App\Enums\ContactAttributeEnum::TEXT->value }}) {
                    modal.find('.addExtraAttribute').append(
                        `<div class="mt-3 col-lg-6">
                            <label for="${key}" class="form-label">{{ textFormat(["_"], '${convertToTitleCase(key)}')}}</label>
                            <input type="text" value="${value}" class="form-control" 
                                name="meta_data[${key}::${meta.type}]" placeholder="Enter {{ textFormat(["_"], '${convertToTitleCase(key)}') }}">
                        </div>`
                    );
                }
            });
        });

        function convertToTitleCase(str) {
            return str.replace(/_/g, ' ').replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }
        $('.delete-contact').on('click', function(){
			var modal = $('#deleteContact');
			modal.find('form[id=singleContactDelete]').attr('action', $(this).data('url'));
		});
        $('.checkAll').click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $('.quick-view').on('click', function() {
            const modal = $('#quick_view');
            const modalBody = modal.find('.modal-body .information-list');
            modalBody.empty();

            var driver = $(this).data('contact_information');

            $.each(driver, function(key, value) {

                const listItem = $('<li>');
                const paramKeySpan = $('<span>').text(textFormat(['_'], key, ' '));
                const arrowIcon = $('<i>').addClass('bi bi-arrow-right');
                var paramValueSpan = '';
                if(jQuery.type(value) === "object") {

                    paramValueSpan = $('<span>').addClass('text-break text-muted').text((value.value === "true" ? "Yes" : (value.value === "false" ? "No" : value.value)));

                } else {

                    paramValueSpan = $('<span>').addClass('text-break text-muted').text(value);
                }


                listItem.append(paramKeySpan).append(arrowIcon).append(paramValueSpan);
                modalBody.append(listItem);
            });

            modal.modal('show');
        });
    })(jQuery);


</script>
@endpush

