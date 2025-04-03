@extends('user.gateway.index')
@section('tab-content')

<div class="tab-pane active fade show" id="{{url()->current()}}" role="tabpanel">
    <div class="table-filter mb-4">
        <form action="{{route(Route::currentRouteName())}}" class="filter-form">
            @csrf
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
                <h4 class="card-title">{{$title}}</h4>
            </div>
            <div class="card-header-right">
                <button class="i-btn btn--info btn--sm me-2 configure-webhook" type="button" data-bs-toggle="modal" data-bs-target="#configureWebhook">
                    <i class="ri-webhook-line fs-16"></i> {{ translate("Configure Webhook") }}
                </button>
                <button class="i-btn btn--primary btn--sm add-whatsapp-business-account" type="button" data-bs-toggle="modal" data-bs-target="#addWhatsappBusinessAccount">
                    <i class="ri-add-fill fs-16"></i> {{ translate("Add Whatsapp Business Account") }}
                </button>
            </div>
        </div>

            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Business Account Name") }}</th>
                                <th scope="col">{{ translate("Templates") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gateways as $item)
                                <tbody>
                                <tr>

                                    <td data-label="{{translate('Business Account Name')}}">{{textFormat(['_'],$item->name,' ')}}</td>
                                    <td data-label="{{ translate('Templates')}}">
                                        <a href="{{route('user.template.whatsapp.index', ['id' => $item->id])}}" class="badge badge--primary p-2">
                                            <span class="i-badge info-solid pill">
                                                {{ translate('View all').' ('.count($item->template).') ' }} <i class="ri-eye-line ms-1"></i>
                                            </span>
                                        </a>
                                    </td>
                                    <td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm info-soft circle update-whatsapp-business-account"
                                                    type="button"
                                                    data-id="{{$item->id}}"
                                                    data-name="{{$item->name}}"
                                                    data-credentials="{{json_encode($item->credentials)}}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateWhatsappBusinessAccount">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update Android Gateway") }} </span>
                                            </button>
                                            <button type="button"
                                                    class="icon-btn btn-ghost btn-sm info-soft circle text-info sync"
                                                    data-value="{{$item->id}}">
                                                    <i class="ri-loop-right-line"></i>
                                                    <span class="tooltiptext"> {{ translate("Sync Business Account Template") }} </span>
                                            </button>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-whatsapp-cloud-api"
                                                    type="button"
                                                    data-item-id="{{ $item->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteWhatsappCloudApi">
                                                <i class="ri-delete-bin-line"></i>
                                                <span class="tooltiptext"> {{ translate("Delete Whatsapp Cloud Api") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @empty
                                <tbody>
                                <tr>
                                    <td colspan="50"><span class="text-danger">{{ translate('No data Available')}}</span></td>
                                </tr>
                                </tbody>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('user.partials.pagination', ['paginator' => $gateways])
            </div>

    </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="addWhatsappBusinessAccount" tabindex="-1" aria-labelledby="addWhatsappBusinessAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.gateway.whatsapp.cloud.api.save')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate("Add WhatsApp Business Account") }}  </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label" for="name">{{ translate('Business Portfolio Name')}} <span class="text-danger">*</span></label>
                            <input type="text" class="mt-2 form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name')}}" placeholder="{{ translate('Add a name for your Business Portfolio')}}" autocomplete="true" aria-label="name">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        @foreach($credentials["required"] as $creds_key => $creds_value)
                            <div class="{{ $loop->last ? 'col-12' : 'col-6' }} ">
                                <label class="form-label" for="{{ $creds_key }}">{{translate(textFormat(['_'], $creds_key))}} <span class="text-danger">*</span></label>
                                <input type="text" id="{{ $creds_key }}" class="mt-2 form-control" name="credentials[{{$creds_key}}]" value="{{old($creds_key)}}" placeholder="Enter the {{translate(textFormat(['_'], $creds_key))}}"  aria-label="{{$creds_key}}" autocomplete="true">
                            </div>
                        @endforeach
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

<div class="modal fade" id="configureWebhook" tabindex="-1" aria-labelledby="configureWebhook" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.gateway.whatsapp.cloud.api.webhook')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Configure WhatsApp Webhook") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-inner">
                                <label for="callback_url" class="form-label"> {{ translate("Callback URL") }} <small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input disabled type="text" id="callback_url" class="form-control" value="{{route('webhook')."?uid=$user->uid"}}" name="callback_url"/>
                                    <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-inner">
                                <label for="verify_token" class="form-label"> {{ translate("Verify Token") }} <small class="text-danger">*</small></label>
                                <div class="input-group">
                                <input type="text" id="verify_token" class="form-control verify_token" value="{{ $user->webhook_token }}" name="verify_token"/>
                                <span id="reset-primary-color" class="input-group-text generate-token pointer"> <i class="ri-restart-line"></i> </span>
                                <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
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

<div class="modal fade" id="updateWhatsappBusinessAccount" tabindex="-1" aria-labelledby="updateWhatsappBusinessAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.gateway.whatsapp.cloud.api.save')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update WhatsApp Business Account Credentials") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <label for="name" class="form-label">{{ translate('Business API Name')}} <sup class="text--danger">*</sup></label>
                            <div class="input-group">
                                  <input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Update Business API Name')}}" autocomplete="true">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row" id="edit_cred"></div>
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

<div class="modal fade actionModal" id="deleteWhatsappCloudApi" tabindex="-1" aria-labelledby="deleteWhatsappCloudApi" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.gateway.whatsapp.cloud.api.delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this WhatsApp device?") }}</h5>
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

        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $(document).ready(function() {

            $('.copy-text').click(function() {

                var message = "Text copied!";
                copy_text($(this), message);
            });
            $('.add-whatsapp-business-account').on('click', function() {

                const modal = $('#addAndroidGateway');
                modal.modal('show');
            });

            $('.configure-webhook').on('click', function() {


                const modal = $('#configureWebhook');
                modal.modal('show');
            });

            $('.update-whatsapp-business-account').on('click', function() {

                $("#edit_cred").empty();
                var credentials = $(this).data('credentials');
                const modal = $('#updateWhatsappBusinessAccount');
                modal.find('input[name=id]').val($(this).attr('data-id'));
                modal.find('input[name=name]').val($(this).attr('data-name'));
                var html = ``;
                $.each(credentials, function(key, value) {

                    html += `
                        <div class="col-lg-6">
                            <label class="form-label mt-3" for="${key}">${textFormat(['_'],key)}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="credentials[${key}]" value="${value}" placeholder="Enter the ${key}">
                        </div>
                    `;

                });
                $("#edit_cred").append(html);
                modal.modal('show');
            });

            $('.sync').click(function(e) {

                var itemId = $(this).attr('data-value');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var button = $(this);
                var originalIcon = button.find('i').detach();
                if (button.hasClass('disabled')) {
                    return;
                }
                button.append('<span class="loading-spinner spinner-border spinner-border-sm" aria-hidden="true"></span> ');
                button.addClass('disabled');

                $.ajax({
                    url: '{{ route("user.template.refresh") }}',
                    type: 'GET',
                    data: {
                        itemId: itemId
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {

                        button.find('.loading-spinner').remove();
                        button.removeClass('disabled').prepend(originalIcon);

                        if(response.status && response.reload) {
                            location.reload(true);
                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function(xhr, status, error) {

                        button.find('.loading-spinner').remove();
                        button.removeClass('disabled').prepend(originalIcon);
                        notify('error', "Some error occurred");
                    }
                });
            });

            $('.delete-whatsapp-cloud-api').on('click', function() {

                var modal = $('#deleteWhatsappCloudApi');
                modal.find('input[name=id]').val($(this).data('item-id'));
                modal.modal('show');
            });
        });

	})(jQuery);
</script>
@endpush
