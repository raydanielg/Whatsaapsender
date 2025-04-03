@extends('user.layouts.app')
@section('panel')
@push('style-push')
<style> 
    .tablinks {
        background-color: #f1f1f1;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: background-color 0.3s;
    }
     
    .tablinks:hover {
        background-color: var(--secondary-color);
        color: var(--white);
    }
     
    .tablinks.active {
        background-color: var(--primary-color);
        color: var(--white);
    }
     
    .tab-content {
        display: none;
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: 0px 5px 5px 5px;
    }
     
    .active-tab {
        display: block;
    }
</style>
@endpush()
<section>
    <div class="col">
        <div class="row align-items-center gy-3 mb-3">
            <div class="col">
                <div class="tab">
                    <button class="tablinks" onclick="openWpTab(event, 'wp-cloud-api')">{{translate("Cloud API")}}</button>
                    <button class="tablinks" onclick="openWpTab(event, 'wp-node-server')">{{translate("Node Server")}}</button>
                </div>
            </div>
        </div>

        <div id="wp-cloud-api" class="tab-content">
            <div class="form-item">
                <div>
                    <form action="{{route('user.gateway.whatsapp.store', 'webhook')}}" method="POST">
                        @csrf
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{translate('Webhook Setup')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="verify_token">{{ translate('Add A Verify Token For Webhook')}} <span class="text-danger">*</span></label>
                                        <div class="input-group mt-2">
                                            <input title="Make sure to copy this same verify token in your Business Account 'Webhook Configuration'" type="text" class="form-control" name="verify_token" id="verify_token" value="{{ $user->webhook_token }}" placeholder="{{ translate('Enter A Token For Webhook')}}">
                                            <span class="input-group-text generate-token cursor-pointer">
                                                <i class="bi bi-arrow-repeat fs-4 text--success"></i>
                                            </span>
                                            <span class="input-group-text copy-text cursor-pointer">
                                                <i class="fa-regular fa-copy fs-4 text--success"></i>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="col-md-6 mb-4">
                                        <label for="callback_url">{{ translate('Add A CallBack URL For Webhook')}} <span class="text-danger">*</span></label>
                                        <div class="input-group mt-2">
                                            <input readonly title="Make sure to copy this same call back url in your Business Account 'Webhook Configuration'" type="text" class="form-control" name="callback_url" id="callback_url" value="{{route('webhook')."?uid=$user->uid"}}">
                                            <span class="input-group-text copy-text cursor-pointer">
                                                <i class="fa-regular fa-copy fs-4 text--success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="i-btn primary--btn btn--lg">{{translate('Save Webhook Credentials')}}</button>
                    </form>
                </div>
            </div>
            <div class="form-item">
                <div>
                    <form action="{{route('user.gateway.whatsapp.store', 'cloud_api')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="text" name="whatsapp_business_api" value="true" hidden>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{translate('Whatsapp Cloud API Setup')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label for="name">{{ translate('Business Portfolio Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" class="mt-2 form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name')}}" placeholder="{{ translate('Add a name for your Business Portfolio')}}">
                                        @error('name')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    @foreach($credentials["required"] as $creds_key => $creds_value)
                                        <div class="{{ $loop->last ? 'col-12' : 'col-md-6' }} mb-4">
                                            <label for="{{ $creds_key }}">{{translate(textFormat(['_'], $creds_key))}} <span class="text-danger">*</span></label>
                                            <input type="text" class="mt-2 form-control" name="credentials[{{$creds_key}}]" value="{{old($creds_key)}}" placeholder="Enter the {{translate(textFormat(['_'], $creds_key))}}">
                                        </div>

                                    @endforeach
                                   
                                </div>
                                <button type="submit" class="i-btn primary--btn btn--md">{{ translate('Submit')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{translate('WhatsApp Business Account List')}}
                            </h4>

                        </div>
                        <div class="card-body px-0">
                            <div class="responsive-table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>{{ translate('Session Name')}}</th>
                                        <th>{{ translate('Templates')}}</th>
                                        <th>{{ translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    @forelse ($whatsappBusinesses as $item)
                                        <tbody>
                                            <tr>
                                                
                                                <td data-label="{{translate('Session Name')}}">{{$item->name}}</td>
                                                <td data-label="{{translate('Templates')}}">
                                                    <a href="{{route('user.gateway.whatsapp.cloud.template', ['type' => 'whatsapp', 'id' => $item->id])}}" class="badge badge--primary p-2"> {{ translate('view templates ')}} ({{count($item->template)}})</a>
                                                </td>
                                                <td data-label="{{translate('Action')}}">
                                                    <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                                        <a title="Edit" href="javascript:void(0)" class="i-btn primary--btn btn--sm whatsappBusinessApiEdit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#whatsappBusinessApiEdit"
                                                        data-id="{{$item->id}}"
                                                        data-name="{{$item->name}}"
                                                        data-credentials="{{json_encode($item->credentials)}}"><i class="las la-pen"></i>{{translate('Edit')}}</a>

                                                        <a title="Sync Templates" href="" class="i-btn success--btn btn--sm sync" value="{{$item->id}}"><i class="fa-solid fa-rotate"></i>{{translate('Sync Templates')}}</a>
                                                        <a title="Delete" href="" class="i-btn danger--btn btn--sm whatsappDelete" value="{{$item->id}}"><i class="fas fa-trash-alt"></i>{{translate('Trash')}}</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center py-4"><span class="text-danger fw-medium">{{ translate('No data Available')}}</span></td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                                </table>
                            </div>
                            <div class="m-3">
                                {{$whatsappBusinesses->appends(request()->all())->onEachSide(1)->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="wp-node-server" class="tab-content">
            <div class="form-item">
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
                
            </div>
        </div> 
    </div>
</section>
@endsection


<div class="modal fade" id="whatsappDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('user.gateway.whatsapp.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id" value="">
                <div class="modal_body2">
                    <div class="modal_icon2">
                        <i class="las la-trash"></i>
                    </div>
                    <div class="modal_text2 mt-3">
                        <h6>{{ translate('Are you sure to delete')}}</h6>
                    </div>
                </div>
                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="i-btn danger--btn btn--md">{{ translate('Delete')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="whatsappBusinessApiEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Update Whatsapp Business API')}}</h5>
                 <button type="button" class="i-btn bg--lite--danger text--danger btn--sm" data-bs-dismiss="modal"> <i class="las la-times"></i></button>
            </div>
            <form action="{{route('user.gateway.whatsapp.update')}}" method="POST">
                @csrf
                <input type="text" name="whatsapp_business_api" value="true" hidden>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row gx-4 gy-3">
                        <div class="col-lg-12">
                            <label for="name" class="form-label">{{ translate('Business API Name')}} <sup class="text--danger">*</sup></label>
                            <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Update Name')}}">
                            </div>
                        </div>
                        <div id="edit_cred"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="i-btn danger--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="i-btn primary--btn btn--md">{{ translate('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script-push')
<script>
	"use strict";

        $(document).ready(function() {
            $('.sync').click(function(e) {
                e.preventDefault();
                var itemId = $(this).attr('value'); 
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var a = $(this);
                a.addClass('disabled').append('<span class="loading-spinner spinner-border spinner-border-sm" aria-hidden="true"></span> ');
                $.ajax({
                    url: '{{ route("user.gateway.whatsapp.cloud.refresh") }}',
                    type: 'GET', 
                    data: {
                        itemId: itemId 
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    success: function(response) {
                        a.find('.loading-spinner').remove();
                        a.removeClass('disabled');
                        
                     
                        if(response.status && response.reload){
                            location.reload(true);
                            notify('success', "Successfully synced Templates");
                        } else {
                            notify('error', "Could Not Sync Templates");
                        }
                    },
                    error: function(xhr, status, error) {
                        a.find('.loading-spinner').remove();
                        notify('error', "Some error occured");
                    }
                });
            });
        });

        function openWpTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
            localStorage.setItem('selectedTab', tabName);
        }

        window.onload = function() {
            var selectedTab = localStorage.getItem('selectedTab');
            if (selectedTab) {
                document.getElementById(selectedTab).style.display = "block";
                var tablinks = document.getElementsByClassName("tablinks");
                for (var i = 0; i < tablinks.length; i++) { 
                    if (tablinks[i].textContent.trim().toLowerCase().replace(/\s+/g, '-') === selectedTab.slice(3)) {
                        tablinks[i].classList.add("active");
                    }
                }
            } else { 
                document.getElementsByClassName('tablinks')[0].click();
            }
        }

        
        
</script>
@endpush