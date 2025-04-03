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
                    <div class="col-xxl-4 col-lg-5 col-sm-7">
                        <div class="filter-search">
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>

                    <div class="col-xxl-8 col-lg-7 col-sm-5">
                        <div class="filter-action justify-content-sm-end flex-row">
                            <button type="submit" class="filter-action-btn ">
                                <i class="ri-menu-search-line"></i> {{ translate("Filters") }}
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
                    <h4 class="card-title">{{ translate("Attribute List") }}</h4>
                </div>
                <div class="card-header-right">
                    <button class="i-btn btn--primary btn--sm add-contact-attrivute" type="button" data-bs-toggle="modal" data-bs-target="#addContactAttribute">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add Contact Attribute") }}
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Attribute Name") }}</th>
                                <th scope="col">{{ translate("Attribute Type") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($contactAttributes as $key => $value)
                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Name')}}">
                                        <p class="text-dark fw-semibold">{{textFormat(['_'], $key, ' ')}}</p>
                                    </td>
                                    <td data-label="{{ translate('Attribute Type')}}">
                                        @php echo contact_meta($value["type"]) @endphp
                                    </td>
                                    <td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $value['status'] == \App\Enums\StatusEnum::TRUE->status() || $value['status'] == \App\Enums\Common\Status::ACTIVE->value ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input contactAttributestatusUpdate"
                                                    data-id="{{ $key }}"
                                                    data-column="status"
                                                    data-route="{{route('user.contact.settings.status.update')}}"
                                                    id="{{ 'status_'.$key }}"
                                                    name="status"/>
                                            <label for="{{ 'status_'.$key }}" class="toggle">
                                                <span></span>

                                            </label>
                                        </div>
                                    </td>

                                    <td data-label="{{ translate('Option')}}">
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle update-contact-settings"
                                                    type="button"
                                                    data-contact-setting-key="{{ $key }}"
                                                    data-contact-setting-type="{{ $value["type"] }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateContactSettings">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update Contact Attribute") }} </span>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-contact-settings"
                                                type="button"
                                                data-contact-setting-key="{{ $key }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteContactSettings">
                                            <i class="ri-delete-bin-line"></i>
                                            <span class="tooltiptext"> {{ translate("Delete Contact Attribute") }} </span>
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
                @include('user.partials.array_pagination', ['meta_data' => $contactAttributes])
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade" id="addContactAttribute" tabindex="-1" aria-labelledby="addContactAttribute" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.contact.settings.save')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Contact Setting") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="attribute_name" class="form-label"> {{ translate('Attribute Name')}} </label>
                                <input type="text" id="attribute_name" name="attribute_name" placeholder="{{ translate('Enter attribute name')}}" class="form-control" aria-label="attribute_name"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-inner">
                                <label for="attribute_type" class="form-label">{{ translate("Select Type of the attribute") }}</label>
                                <select class="form-select" id="attribute_type" name="attribute_type">
                                    <option selected disabled> {{ translate("--Select One Type--")}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::DATE }}"> {{ translate('Date')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::BOOLEAN }}"> {{ translate('Boolean')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::NUMBER }}"> {{ translate('Number')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::TEXT }}"> {{ translate('Text')}}</option>
                                </select>
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
<div class="modal fade" id="updateContactSettings" tabindex="-1" aria-labelledby="updateContactSettings" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.contact.settings.save')}}" method="POST">
                @csrf
                <input type="text" id="old_attribute_name" name="old_attribute_name" hidden/>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Contact Setting") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="attribute_name" class="form-label"> {{ translate('Attribute Name')}} </label>
                                <input type="text" id="attribute_name" name="attribute_name" placeholder="{{ translate('Enter attribute name')}}" class="form-control" aria-label="attribute_name"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-inner">
                                <label for="attribute_type" class="form-label">{{ translate("Select Type of the attribute") }}</label>
                                <select class="form-select" id="attribute_type" name="attribute_type">
                                    <option selected disabled> {{ translate("--Select One Type--")}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::DATE }}"> {{ translate('Date')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::BOOLEAN }}"> {{ translate('Boolean')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::NUMBER }}"> {{ translate('Number')}}</option>
                                    <option value="{{ \App\Models\GeneralSetting::TEXT }}"> {{ translate('Text')}}</option>
                                </select>
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
<div class="modal fade actionModal" id="deleteContactSettings" tabindex="-1" aria-labelledby="deleteContactSettings" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.contact.settings.delete')}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">

                <input type="hidden" name="attribute_name" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this contact attribute?") }}</h5>
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


@push('script-push')
<script>
    (function($){
        "use strict";

        $('.update-contact-settings').on('click', function() {

			var modal = $('#updateContactSettings');
            modal.find('input[name=old_attribute_name]').val(textFormat(['_'],$(this).data('contact-setting-key'),' '));
			modal.find('input[name=attribute_name]').val(textFormat(['_'],$(this).data('contact-setting-key'),' '));
			modal.find('select[name=attribute_type]').val($(this).data('contact-setting-type'));
			modal.modal('show');
		});
        $('.delete-contact-settings').on('click', function(){

			var modal = $('#deleteContactSettings');
			modal.find('input[name=attribute_name]').val($(this).data('contact-setting-key'));
		});
        $('.contactAttributestatusUpdate').on('change', function() {
            const status = this.checked ? true : false;
            const name = $(this).data('id');

            $.ajax({
                method: 'POST',
                url: $(this).data('route'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'status': status,
                    'name': name
                },
                dataType: 'json',
                success: function (response) {
                    if (response) {
                        const responseStatus = response.status ? "success" : "error";

                        if (typeof response.message === 'object' && response.message !== null) {
                            for (let key in response.message) {
                                if (response.message.hasOwnProperty(key)) {
                                    notify('error', response.message[key][0] || response.message[key]);
                                }
                            }
                        } else {

                            notify(responseStatus, response.message || (responseStatus === 'success' ? 'Contact Attribute Status Updated Successfully' : 'Could Not Update Contact Status'));
                        }

                        if (response.reload) {
                            location.reload();
                        }
                    }
                },
                error: function (error) {
                    if (error && error.responseJSON) {
                        if (error.responseJSON.errors) {

                            for (let i in error.responseJSON.errors) {
                                notify('error', error.responseJSON.errors[i][0]);
                            }
                        } else {

                            notify('error', error.responseJSON.message || 'An error occurred');
                        }
                    } else {
                        
                        notify('error', error.message || 'Request failed');
                    }
                }
            });
        });
    })(jQuery);
</script>
@endpush

