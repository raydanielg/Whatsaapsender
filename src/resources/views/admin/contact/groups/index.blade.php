@include('v321.common.css')
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        
        @include('v321.common.header')
        @include('v321.common.search')
       
        <div class="card">
            @include('v321.common.bulk')

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
                                <th scope="col">{{ translate("Group Name") }}</th>
                                <th scope="col">{{ translate("Contacts") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contactGroups as $contactGroup)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" value="{{$contactGroup->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$contactGroup->id}}" />
                                            <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                                        </div>
                                    </td>
                                    <td> {{ $contactGroup->name }} </td>
                                    <td data-label="{{ translate('Group')}}">
                                        <a href="{{route('admin.contact.index', $contactGroup->id)}}" class="badge badge--primary p-2">
                                            <span class="i-badge info-solid pill">
                                                {{translate("View All: ").count($contactGroup->contacts).translate(" contacts")}} <i class="ri-eye-line ms-1"></i>
                                            </span>
                                        </a>
                                    </td>
                                    <td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $contactGroup->status == \App\Enums\Common\Status::ACTIVE->value ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input statusUpdateByUID"
                                                    data-uid="{{ $contactGroup->uid }}"
                                                    data-column="status"
                                                    data-value="{{ 
                                                        $contactGroup->status == 1 || @$contactGroup?->status == \App\Enums\Common\Status::ACTIVE->value
                                                        ? \App\Enums\Common\Status::INACTIVE->value
                                                        : \App\Enums\Common\Status::ACTIVE->value}}"
                                                    data-route="{{route('admin.contact.group.status.update')}}"
                                                    id="{{ 'status_'.$contactGroup->uid }}"
                                                    name="status"/>
                                            <label for="{{ 'status_'.$contactGroup->uid }}" class="toggle">
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle edit-contact-group"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editContactGroup"
                                                    href="javascript:void(0)"
                                                    data-url    = "{{route('admin.contact.group.update', ['uid' => $contactGroup->uid])}}"
                                                    data-name   = "{{$contactGroup->name}}">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-contact-group"
                                                    type            = "button"
                                                    data-url        = "{{route('admin.contact.group.destroy', ['uid' => $contactGroup->uid])}}"
                                                    data-bs-toggle  = "modal"
                                                    data-bs-target  = "#deleteContactGroup">
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
                @include('admin.partials.pagination', ['paginator' => $contactGroups])
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")

    <div class="modal fade" id="addContactGroup" tabindex="-1" aria-labelledby="addContactGroup" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered ">
            <div class="modal-content">
                <form action="{{route('admin.contact.group.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Contact Group") }} </h5>
                        <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                            <i class="ri-close-large-line"></i>
                        </button>
                    </div>
                    <div class="modal-body modal-md-custom-height">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-inner">
                                    <label for="add_name" class="form-label"> {{ translate('Contact Group Name')}} </label>
                                    <input type="text" id="add_name" name="name" placeholder="{{ translate('Enter contact group name')}}" class="form-control" aria-label="name"/>
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

    <div class="modal fade" id="editContactGroup" tabindex="-1" aria-labelledby="editContactGroup" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form action="#" method="POST" id="contactGroupEditModal">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Contact Group") }} </h5>
                        <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                            <i class="ri-close-large-line"></i>
                        </button>
                    </div>
                    <div class="modal-body modal-md-custom-height">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-inner">
                                    <label for="add_name" class="form-label"> {{ translate('Contact Group Name')}} </label>
                                    <input type="text" id="add_name" name="name" placeholder="{{ translate('Enter contact group name')}}" class="form-control" aria-label="name"/>
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

    @php
        $deleteModalData = [
            "message"   => translate("Are you sure to delete this contact group?"),
        ]
    @endphp

    @include('v321.common.delete', $deleteModalData)

    @php
        $bulkDeleteModalData = [
            "url"       => route('admin.contact.group.bulk'),
            "title"     => translate("Are you sure to change the status for the selected data?"),
            "message"   => translate("This action is irreversable"),
        ]
    @endphp

    @include('v321.common.bulk_delete', $bulkDeleteModalData)
@endsection

@include('v321.common.js.select2')

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

            $('.add-contact-group').on('click', function() {

                var modal = $('#addContactGroup');
                modal.modal('show');
            });
            $('.edit-contact-group').on('click', function() {

                var modal = $('#editContactGroup');
                modal.find('form[id=contactGroupEditModal]').attr('action', $(this).data('url'));
                modal.find('form[id=contactGroupEditModal]').attr('method', $(this).data('method'));
			    modal.find('input[name=name]').val($(this).data('name'));
                modal.modal('show');
            });
            $('.delete-contact-group').on('click', function(){
                var modal = $('#deleteContactGroup');
                modal.find('form[id=singleDeleteModal]').attr('action', $(this).data('url'));
            });
            $('.checkAll').click(function() {

                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        });

    })(jQuery);
</script>
@endpush