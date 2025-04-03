@extends('admin.gateway.index')
@section('tab-content')

<div class="tab-pane active fade show" id="{{url()->current()}}" role="tabpanel">
    <div class="table-filter mb-4">
        <form action="{{route(Route::currentRouteName())}}" class="filter-form">
            
            <div class="row g-3">
                <div class="col-xxl-3 col-xl-4 col-lg-4">
                    <div class="filter-search">
                        <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by Name") }}" />
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
                <button class="i-btn btn--info btn--sm whatsapp-server-settings" type="button" data-bs-toggle="modal" data-bs-target="#whatsappServerSetting">
                    <i class="ri-server-line"></i> {{ translate("Server Settings") }}
                </button>
                @if($server_status)
                    <button class="i-btn btn--primary btn--sm add-whatsapp-device" type="button" data-bs-toggle="modal" data-bs-target="#addWhatsappDevice">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add Whatsapp Device") }}
                    </button>
                @endif
            </div>
        </div>
        @if($server_status)
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Session Name") }}</th>
                                <th scope="col">{{ translate("WhatsApp Number") }}</th>
                                <th scope="col">{{ translate("Minimum Delay") }}</th>
                                <th scope="col">{{ translate("Maximum Delay") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gateways as $item)
                                <tbody>
                                <tr>

                                    <td data-label="{{translate('Session Name')}}">{{$item->name}}</td>
                                    <td data-label="{{translate('WhatsApp Number')}}" >{{$item->credentials && array_key_exists("number", $item->credentials) && $item->credentials["number"] != " "? $item->credentials["number"] : 'N/A'}}</td>
                                    <td data-label="{{translate('Time Delay')}}" >{{$item->credentials && array_key_exists("min_delay", $item->credentials) ? convertTime($item->credentials["min_delay"]) : "N/A"}}</td>
                                    <td data-label="{{translate('Time Delay')}}" >{{$item->credentials && array_key_exists("max_delay", $item->credentials) ? convertTime($item->credentials["max_delay"]) : "N/A"}}</td>
                                    <td data-label="{{translate('Status')}}">
                                        @if($item->status == 'initiate')
                                            <span class="i-badge dot primary-soft pill text-capitalize">{{ translate("Initiate") }}</span>
                                        @elseif($item->status == 'connected')
                                            <span class="i-badge dot success-soft pill text-capitalize">{{ translate("Connected") }}</span>
                                        @else
                                            <span class="i-badge dot danger-soft pill text-capitalize">{{ translate("Disconnected") }}</span>
                                        @endif

                                    </td>
                                    <td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm info-soft circle update-whatsapp-device"
                                                    type="button"
                                                    data-id="{{$item->id}}"
                                                    data-min_delay="{{$item->credentials && array_key_exists("min_delay", $item->credentials) ? $item->credentials['min_delay'] : null}}"
                                                    data-max_delay="{{$item->credentials && array_key_exists("max_delay", $item->credentials) ? $item->credentials['max_delay'] : null}}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateWhatsappDevice">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Update Android Gateway") }} </span>
                                            </button>
                                            @if($item->status == 'initiate')
                                                <button class="icon-btn btn-ghost btn-sm success-soft circle qrQuote textChange{{$item->id}}"
                                                        value="{{$item->id}}"
                                                        type="button"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvasQrCode"
                                                        aria-controls="offcanvasQrCode">

                                                    <i class="ri-qr-code-fill"></i>
                                                    <span class="tooltiptext"> {{ translate("Scan") }} </span>
                                                </button>

                                            @elseif($item->status == 'connected')
                                                <button class="icon-btn btn-ghost btn-sm danger-soft circle deviceDisconnection{{$item->id}}"
                                                        onclick="return deviceStatusUpdate('{{$item->id}}','disconnected','deviceDisconnection','Disconnecting','Connect')"
                                                        value="{{$item->id}}"
                                                        type="button">

                                                        <i class="ri-wifi-off-fill"></i>
                                                    <span class="tooltiptext"> {{ translate("Disconnect") }} </span>
                                                </button>

                                            @else
                                                <button class="icon-btn btn-ghost btn-sm success-soft circle qrQuote textChange{{$item->id}}"
                                                    value="{{$item->id}}"
                                                    type="button"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasQrCode"
                                                    aria-controls="offcanvasQrCode">

                                                <i class="ri-qr-code-fill"></i>
                                                <span class="tooltiptext"> {{ translate("Scan") }} </span>
                                            </button>
                                            @endif
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-whatsapp-device"
                                                type="button"
                                                data-item-id="{{ $item->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteWhatsappDevice">
                                            <i class="ri-delete-bin-line"></i>
                                            <span class="tooltiptext"> {{ translate("Delete Whatsapp device") }} </span>
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
                @include('admin.partials.pagination', ['paginator' => $gateways])
            </div>
        @else
            <div class="card">
                <div class="card-header">
                   <span>{{ translate('Node Server Offline')}} <i class="fas fa-info-circle"></i></span>

                    <div class="header-with-btn">
                        <span class="d-flex align-items-center gap-2"> 
                            <a href="" class="badge badge--primary"> <i class="fas fa-refresh"></i>  {{ translate('Try Again') }}</a>
                        </span>
                    </div>

                </div>

                <div class="card-body">
                    <h6 class="text--danger">{{ translate('Unable to connect to WhatsApp node server. Please configure the server settings and try again.') }}</h6>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="whatsappServerSetting" tabindex="-1" aria-labelledby="addWhatsappDevice" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.gateway.whatsapp.device.server.update')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Configure Server Settings") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="server_url" class="form-label">{{ translate('WhatsApp Server URL')}}<span class="text-danger">*</span></label>
                                <input type="text" id="server_url" name="server_url" placeholder="{{ translate('Enter Whatsapp Server URL')}}" class="form-control" aria-label="server_url" value="{{ env('WP_SERVER_URL') }}" readonly="true"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="server_host" class="form-label"> {{ translate('WhatsApp Server Host')}}<span class="text-danger">*</span> </label>
                                <input type="text" id="server_host" name="server_host" placeholder="{{ translate('Enter Whatsapp Server Host')}}" class="form-control" aria-label="server_host" value="{{ env('NODE_SERVER_HOST') }}" required/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="server_port" class="form-label"> {{ translate('WhatsApp Server Port')}} <span class="text-danger">*</span></label>
                                <input type="number" id="server_port" name="server_port" placeholder="{{ translate('Enter Whatsapp Server Port')}}" class="form-control" aria-label="server_port" value="{{ env('NODE_SERVER_PORT') }}" required/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="max_retries" class="form-label"> {{ translate('Maximum Retries')}}<span class="text-danger">*</span> </label>
                                <input type="number" id="max_retries" name="max_retries" placeholder="{{ translate('Enter The Maximum Amount of Retries')}}" class="form-control" aria-label="max_retries" value="{{ env('MAX_RETRIES') }}" required/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-inner">
                                <label for="reconnect_interval" class="form-label"> {{ translate('Reconnect Interval')}}<span class="text-danger">*</span> </label>
                                <input type="number" id="reconnect_interval" name="reconnect_interval" placeholder="{{ translate('Enter Reconnect Interval Duration')}}" class="form-control" aria-label="reconnect_interval" value="{{ env('RECONNECT_INTERVAL') }}" required/>
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

<div class="modal fade" id="addWhatsappDevice" tabindex="-1" aria-labelledby="addWhatsappDevice" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.gateway.whatsapp.device.save')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add WhatsApp Device") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="name" class="form-label">{{ translate('Session/Device Name')}}<span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" placeholder="{{ translate('Enter whatsapp session name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="min_delay" class="form-label"> {{ translate('Minimum Delay')}} </label>
                                <input type="number" id="min_delay" name="min_delay" placeholder="{{ translate('Enter minimum delay for this device')}}" class="form-control" aria-label="min_delay"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="max_delay" class="form-label"> {{ translate('Maximum Delay')}} </label>
                                <input type="number" id="max_delay" name="max_delay" placeholder="{{ translate('Enter maximum delay for this device')}}" class="form-control" aria-label="max_delay"/>
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

<div class="modal fade" id="updateWhatsappDevice" tabindex="-1" aria-labelledby="updateWhatsappDevice" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.gateway.whatsapp.device.save')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update WhatsApp Device") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="min_delay" class="form-label"> {{ translate('Minimum Delay')}} </label>
                                <input type="text" id="min_delay" name="min_delay" placeholder="{{ translate('Enter item gateway name')}}" class="form-control" aria-label="min_delay"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="max_delay" class="form-label"> {{ translate('Maximum Delay')}} </label>
                                <input type="text" id="max_delay" name="max_delay" placeholder="{{ translate('Enter item gateway name')}}" class="form-control" aria-label="max_delay"/>
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

<div class="modal fade actionModal" id="deleteWhatsappDevice" tabindex="-1" aria-labelledby="deleteWhatsappDevice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.gateway.whatsapp.device.delete')}}" method="POST">
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasQrCode" aria-labelledby="offcanvasQrCode" data-bs-backdrop="static">
    <div class="offcanvas-header justify-content-between bg-light">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ translate("Connect Whatsapp") }}</h5>
        <button
            type="button"
            class="icon-btn btn-sm dark-soft hover circle modal-closer"
            data-bs-dismiss="offcanvas"
            onclick="return deviceStatusUpdate('','initiate','','','')">
            <i class="ri-close-large-line"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="d-flex flex-column justify-content-between h-100">
        <div class="p-3">
            <input type="hidden" name="scan_id" id="scan_id" value="">
            <ul class="information-list border-0 p-0">

            <li>
                <p>{{ translate('1. Open WhatsApp on your phone')}}</p>
            </li>

            <li>
                <p>{{ translate('2. Tap Menu  or Settings  and select Linked Devices')}}</p>
            </li>

            <li>
                <p>{{ translate('3. Point your phone to this screen to capture the code')}}</p>
            </li>
            </ul>
            <div class="qr-code mt-5">
                <img id="qrcode" src="" alt="">
            </div>
        </div>

        <div class="py-xl-5 py-4 px-3 bg-light mt-5">
            <div class="text-center  h-100">
                <h6 class="mb-2">{{ translate("Tutorial") }}</h6>
                <a class="fs-14 text-info" href="https://support.igensolutionsltd.com/help-center"><i class="ri-information-2-line fs-18"></i>{{ translate("Need help to get started?") }}</a>

                <div class="mt-4">
                <img src="https://static.whatsapp.net/rsrc.php/v3/yB/r/7Y1jh45L_8V.png" alt="whatsapp">
                </div>
            </div>
        </div>
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

        $(document).on('click', '.qrQuote', function(e) {

            e.preventDefault()
            var id = $(this).attr('value')
            var url = "{{route('admin.gateway.whatsapp.device.server.qrcode')}}"
            $.ajax({
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                url:url,
                data: {id:id},
                dataType: 'json',
                method: 'post',
                beforeSend: function() {

                    $('.textChange'+id).html(`<i class="ri-loader-2-line"></i>
                                                    <span class="tooltiptext"> {{ translate("Loading") }} </span>`);
                },
                success: function(res) {

                    $("#scan_id").val(res.response.id);
                    if (res.data.message && res.data.qr && res.data.status===200) {

                        $('#qrcode').attr('src', res.data.qr);
                        notify('success', res.data.message);
                        sleep(10000).then(() => {

                            wapSession(res.response.id);
                        });
                    } else if (res.data.message) {

                        notify('error', res.data.message);
                    }
                },
                complete: function(){
                    $('.textChange'+id).html(`<i class="ri-qr-code-fill"></i>
                                                    <span class="tooltiptext"> {{ translate("Scan") }} </span>`);
                },
                error: function(e) {
                    notify('error','Something went wrong')
                }
            });
        });

        function wapSession(id) {

            $.ajax({

                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                url:"{{route('admin.gateway.whatsapp.device.server.status')}}",
                data: {id:id},
                dataType: 'json',
                method: 'post',
                success: function(res) {

                    $("#scan_id").val(res.response.id);
                    if (res.data.qr!=='') {

                        $('#qrcode').attr('src',res.data.qr);
                    }

                    if (res.data.status===301) {

                        sleep(2500).then(() => {

                            $('.qrQuote').offcanvas('hide');
                            location.reload();
                        });
                    } else {

                        sleep(10000).then(() => {

                            wapSession(res.response.id);
                        });
                    }
                }
            })
        }



        $(document).ready(function() {

            $('.whatsapp-server-settings').on('click', function() {

                const modal = $('#whatsappServerSetting');
                modal.modal('show');
            });
            $('.add-whatsapp-device').on('click', function() {

                const modal = $('#addAndroidGateway');
                modal.modal('show');
            });

            $('.update-whatsapp-device').on('click', function() {

                const modal = $('#updateWhatsappDevice');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=min_delay]').val($(this).data('min_delay'));
                modal.find('input[name=max_delay]').val($(this).data('max_delay'));
                modal.modal('show');
            });

            $('.delete-whatsapp-device').on('click', function() {

                var modal = $('#deleteWhatsappDevice');
                modal.find('input[name=id]').val($(this).data('item-id'));
                modal.modal('show');
            });
        });

	})(jQuery);
</script>
@endpush
