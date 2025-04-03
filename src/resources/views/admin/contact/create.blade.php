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
        <div class="card">
            <div class="form-header">
            <div class="row gy-4 align-items-center">
                <div class="col-xxl-2 col-xl-3">
                    <h4 class="card-title">{{ translate("Contact Upload Mode") }}</h4>
                </div>
                <div class="col-xxl-10 col-lg-9 col-md-8">
                    <div class="form-tab">
                      <ul class="nav" role="tablist">
                        <li class="nav-item" role="presentation">
                          <a class="nav-link active" data-bs-toggle="tab" href="#single-tab-pane" role="tab" aria-selected="true">
                            <i class="bi bi-people-fill"></i> {{ translate("Single contact") }} </a>
                        </li>
                        <li class="nav-item" role="presentation">
                          <a class="nav-link" data-bs-toggle="tab" href="#upload-tab-pane" role="tab" aria-selected="false" tabindex="-1">
                            <i class="bi bi-file-earmark-plus-fill"></i> {{ translate("Import CSV file") }} </a>
                        </li>
                      </ul>
                    </div>
                  </div>
            </div>
            </div>
            <div class="card-body pt-0">
                <form id="contact_store" action="{{route('admin.contact.store')}}" method="POST">
                    @csrf
                    <div class="form-element">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Group") }}</h5>
                          </div>
                          <div class="col-xxl-7 col-xl-9">
                            <div class="form-inner">
                              <label for="ChooseGroup" class="form-label">{{ translate("Choose a group") }}<span>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Choose a group to store the contacts') }}">
                                    <i class="ri-question-line"></i>
                                  </span>
                                </span>
                              </label>
                              
                              <select data-placeholder="{{translate('Select a group')}}" class="form-select select2-search" id="group_id" name="group_id" aria-label="ChooseGroup">
                                <option value=""></option>
                                
                                @foreach($groups as $name => $id)
                                    <option {{$groupId == $id ? 'selected' : ''}} value="{{$id}}">{{$name}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="single-tab-pane" role="tabpanel">
                            <input hidden type="text" name="single_contact">
                            <div class="form-element">
                                <div class="row gy-3">
                                    <div class="col-xxl-2 col-xl-3">
                                        <h5 class="form-element-title">{{ translate("Name") }}</h5>
                                    </div>
                                    <div class="col-xxl-7 col-xl-9">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-inner">
                                                    <label for="first_name" class="form-label"> {{ translate("First name") }} </label>
                                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter first name" aria-label="Enter first name" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-inner">
                                                    <label for="last_name" class="form-label"> {{ translate("Last name") }} </label>
                                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter last name" aria-label="Enter last name" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-element">
                                <div class="row gy-3">
                                    <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Contact Details") }}</h5>
                                    </div>
                                    <div class="col-xxl-7 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="whatsapp_contact" class="form-label"> {{ translate("Whatsapp Number") }} </label>
                                                <input type="number" id="whatsapp_contact" name="whatsapp_contact" class="form-control" placeholder="{{translate('Enter whatsapp contact number')}}" aria-label="Enter whatsapp contact number" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="sms_contact" class="form-label"> {{ translate("SMS Number") }} </label>
                                                <input type="number" id="sms_contact" name="sms_contact" class="form-control" placeholder="{{translate('Enter SMS contact number')}}" aria-label="Enter sms contact number" />
                                            </div>
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
                                                        data-url = "{{ route('admin.verify.email') }}">
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
                                    </div>
                                </div>
                            </div>
                            @if(count($filtered_meta_data) > 0)
                                <div class="form-element">
                                    <div class="row gy-3">
                                        <div class="col-xxl-2 col-xl-3">
                                            <h5 class="form-element-title"> {{ translate("Custom Attribute")}} <a href="{{ route("admin.contact.settings.index") }}" target="_blank"><i class="ri-external-link-line"></i></a></h5>
                                        </div>
                                        <div class="col-xxl-7 col-xl-9">
                                            <div class="row gy-4">
                                                @foreach($filtered_meta_data as $attribute_key => $attribute_value)
                                                
                                                @php 
                                                    $attributeType = \Illuminate\Support\Arr::get($attribute_value, "type");
                                                @endphp
                                                <div class="{{ $loop->last && $loop->odd ? 'col-md-12' : 'col-md-6' }}">
                                                        <label for="{{ $attribute_key }}" class="form-label"> {{ translate(textFormat(["_"], $attribute_key))}}</label>
                                                        @if($attributeType == \App\Models\GeneralSetting::DATE)
                                                            <input type="date" value="{{ old($attribute_key) }}" class="form-control mb-3 flatpicker" id="{{ $attribute_key }}" name="meta_data[{{ $attribute_key."::".$attributeType }}]" placeholder=" {{ translate('Choose Contact ').textFormat(["_"], $attribute_key)}}">
                                                        @elseif($attributeType == \App\Models\GeneralSetting::BOOLEAN)
                                                            <select class="form-select mb-3" name="meta_data[{{ $attribute_key."::".$attributeType }}]" id="{{ $attribute_key }}" required>
                                                                <option selected disabled> {{ translate('-- Select An Option --')}}</option>
                                                                <option value="true"> {{ translate('Yes')}}</option>
                                                                <option value="false"> {{ translate('No')}}</option>
                                                            </select>
                                                        @elseif($attributeType == \App\Models\GeneralSetting::NUMBER)
                                                            <input type="number" value="{{ old($attribute_key) }}" class="form-control mb-3" id="{{ $attribute_key }}" name="meta_data[{{ $attribute_key."::".$attributeType }}]" placeholder=" {{ translate('Enter Contact ').textFormat(["_"], $attribute_key)}}">
                                                        @else
                                                            <input type="text" value="{{ old($attribute_key) }}" class="form-control mb-3" id="{{ $attribute_key }}" name="meta_data[{{ $attribute_key."::".$attributeType }}]" placeholder=" {{ translate('Enter Contact ').textFormat(["_"], $attribute_key)}}">
                                                        @endif
                                                    </div>

                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="form-item my-3">
                                        <a href="{{ route("admin.contact.settings.index") }}" class="i-btn primary--btn btn--md">{{ translate('Add More Attributes')}}</a>

                                    </div>
                                @endif
                        </div>
                        <div class="tab-pane fade" id="upload-tab-pane" role="tabpanel">
                            <div class="form-element">
                                <input hidden type="text" name="import_contact">
                                <input hidden id="send_add_new_row" name="new_row">
                                <input hidden id="send_header_location" name="location[]">
                                <input hidden id="send_header_value" name="value[]">
                                <input hidden id="file__name" name="file__name">
                                <div class="row gy-3">
                                  <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Import file") }}</h5>
                                  </div>
                                  <div class="col-xxl-7 col-xl-9">
                                    <div class="form-inner">
                                        <div class="upload-filed">
                                            <input type="file" id="file_upload" name="file" id="file">

                                            <label for="file_upload" class="uplaod-file">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="upload-drop-file">
                                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#f6f0ff" d="M99.091 84.317a22.6 22.6 0 1 0-4.709-44.708 31.448 31.448 0 0 0-60.764 0 22.6 22.6 0 1 0-4.71 44.708z" opacity="1" data-original="#f6f0ff" class=""></path><circle cx="64" cy="84.317" r="27.403" fill="#6009f0" opacity="1" data-original="#6009f0" class=""></circle><g fill="#f6f0ff"><path d="M59.053 80.798v12.926h9.894V80.798h7.705L64 68.146 51.348 80.798zM68.947 102.238h-9.894a1.75 1.75 0 0 1 0-3.5h9.894a1.75 1.75 0 0 1 0 3.5z" fill="#f6f0ff" opacity="1" data-original="#f6f0ff" class=""></path></g></g></svg>
                                                    </span>
                                                    <span class="upload-browse">{{ translate("Upload CSV/Excel File Here ") }}</span>
                                                </div>
                                            </label>
                                            <div class="file__info d-none"></div>
                                        </div>
                                      <p class="form-element-note">{{ translate("Download a demo CSV file from this link: ") }}<a href="{{ route("admin.contact.demo.file", "csv" ) }}">{{translate('demo csv')}}</a></p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-9">
                            <div class="form-action justify-content-end">
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade" id="updateTableData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Data Key Mapping") }} </h5>
                <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                    <i class="ri-close-large-line"></i>
                </button>
            </div>
           <div class="modal-body">
                <input hidden type="text" name="import_contact">
                <div class="headers"> </div>
           </div>

            <div class="modal-footer">
                <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                <button type="submit" class="i-btn btn--primary saveChanges btn--md"> {{ translate("Save") }} </button>
            </div>
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
<script>
	(function($){
		"use strict";
        select2_search($('.select2-search').data('placeholder'));
        var GlobalColumnName = [];
        
        $(document).ready(function() {

            $(".flatpicker").flatpickr();

            $('#contact_store').submit(function (event) {

                var activeTab = $(this).find('.tab-pane.show.active');

                if (activeTab.attr('id') === 'single-tab-pane') {

                    $('input[name="single_contact"]', this).val('true');
                    $('input[name="import_contact"]', this).val('false');

                } else if (activeTab.attr('id') === 'upload-tab-pane') {

                    $('input[name="single_contact"]', this).val('false');
                    $('input[name="import_contact"]', this).val('true');
                }
            });

            function createFileHtmlBlock(file, uploadTime) {
                var iconClass;

                if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    iconClass = 'ri-file-excel-2-line';
                } else if (file.type === 'application/vnd.ms-excel' || file.type === 'text/csv') {
                    iconClass = 'ri-file-line';
                } else {
                    iconClass = 'ri-file-line';
                }

                return `
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="fs-1">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="d-flex flex-column align-items-start gap-1">
                            <p title="${file.name}" class="fw-normal">File Name: ${file.name}</p>
                            <small title="${file.type}">File Type: ${file.type}</small>
                            <small title="${bytesToSize(file.size)}">File Size: ${bytesToSize(file.size)}</small>
                            <small title="${uploadTime}">Upload Time: ${uploadTime}</small>
                        </div>
                        <span class="edit__file">
                            <i class="ri-pencil-line"></i>
                        </span>
                        <span class="remove__file">
                            <i class="ri-delete-bin-7-line"></i>
                        </span>
                       
                    </div>
                    <div class="mt-3 progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
                    </div>
                    `;
            }

            function deleteFile(fileName, csrfToken) {
                return $.ajax({
                    url: '{{ route("admin.contact.delete.file") }}',
                    type: 'POST',
                    data: {
                        file_name: fileName,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,

                    },
                    xhr: function () {

                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                
                                $(".progress-bar").css("width", percentComplete + "%");
                            }
                        }, false);

                        return xhr;
                    },
                });
            }

            $(document).on('click', '.removeColumn', function () {

                $(this).closest('.columnData').remove();
            });
                        
            function keyMappingModal(data) {

                var progressBar = $(".progress").addClass("d-none");
                var modal = $('#updateTableData');
                var container = $(".switch-container");

                if (container.length === 0) {
                    var html = `<div class="container">
                                    <div class="mt-3 switch-container">
                                        <label title="{{ translate("If your excel/csv file do not containe any header and the 1st row contains data \nin it then you should toggle on this option") }}" class="form-check-label" for="add_new_row">{{translate('Enable this toggle to add this header setup as a new row')}}</label>
                                        <label class="switch">
                                            <input type="checkbox" value="true" name="add_new_row" type="checkbox" id="add_new_row">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>`;
                    $(".your-container-selector").append(html);
                } else {
                    var html = `<div class="container">`;
                }

                $.each(data, function(cell, header) {
                   
                    var formattedHeader = header.toString().replace(/_/g, ' ').replace(/\w\S*/g, function(txt) {
                        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                    });
                    html += `
                        <div class="row g-3 my-3 columnData">
                            <div class="col-lg-3 location">
                                <input type="text" class="form-control text-uppercase text-center" value="${cell}" placeholder="${cell.toUpperCase()}" readonly="true">
                                <input hiddden type="text" name="header_location[]"  value="${header}" hidden>
                            </div>
                            <div class="col-lg-6 col-sm-8 value">
                                <select class="form-select select-attribute" name="header_value[]" aria-label="Large select example">
                                    <option class="custom-attribute" value="${header}::4" selected>${formattedHeader}</option> 
                                    <option ${header == "first_name" ? 'selected' : ''} value="first_name::4">First Name</option>     
                                    <option ${header == "last_name" ? 'selected' : ''} value="last_name::4">Last Name</option>  
                                    <option ${header == "whatsapp_contact" ? 'selected' : ''} value="whatsapp_contact::4">Whatsapp Contact</option>     
                                    <option ${header == "email_contact" ? 'selected' : ''} value="email_contact::4">Email Contact</option>   
                                    <option ${header == "sms_contact" ? 'selected' : ''} value="sms_contact::4">SMS Contact</option>
                                    @foreach($filtered_meta_data as $attributeName => $attribute)
                                        @php 
                                            $attributeType = \Illuminate\Support\Arr::get($attribute, "type");
                                        @endphp
                                        <option  ${header == "{{$attributeName}}" ? 'selected' : ''} value="{{ $attributeName.'::'.$attributeType }}">{{ucfirst(str_replace('_', ' ', $attributeName))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="type d-none">
                                
                            </div>
                            <div class="col-lg-3 col-sm-4 d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                <span class="input-group-btn">
                                    <button class="i-btn primary--btn btn--md text--light editColumn" data-cell="${cell}" data-header="${header}" type="button">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="i-btn danger--btn btn--md text--light removeColumn" type="button">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </span>
                            </div>
                        </div>`;
                }); 

                html += `</div>`;
                $('.headers').append(html);
                modal.modal('show');
            };

            $(document).on('click', '.editColumn', function () {

                var columnData = $(this).closest(".columnData");
               
                if (columnData.hasClass("editing")) {

                    var dataHeader = $(this).data('header');
                    var selectElement = generateSelectElement(dataHeader);
                    var currentValue = columnData.find(".value input").val();
                    var convertedValue = currentValue.toString().replace(/_/g, ' ').replace(/\w\S*/g, function(txt) {
                        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                    });

                    columnData.find(".value input").replaceWith(selectElement);
                    columnData.find(".value select").val(convertedValue);
                    var select = columnData.find(".type").removeClass("d-none").addClass("col-lg-2 col-sm-4 ").append(`
                        <select class="form-select" name="type[]" aria-label="Large select example">
                            <option selected disabled>Type</option>
                            <option value="1">Date</option>
                            <option value="3">Number</option>
                            <option value="4">Text</option>
                        </select>
                    `);
                    columnData.removeClass("editing");
                    $(this).removeClass("info--btn").addClass("primary--btn");
                    $(this).find("i").removeClass("la-undo-alt").addClass("la-pen");
                } else {
                    
                    var formattedValue = columnData.toString().replace(/_/g, ' ').replace(/\w\S*/g, function(txt) {
                        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                    });

                    columnData.find(".value select").replaceWith(function () {
                        return $("<input>").attr({
                            type: "text",
                            name: "custom_name[]",
                            class: "form-control",
                            value: "",  
                            placeholder: "Enter attribute name"
                        });
                    });

                    columnData.find(".type").addClass("d-none").removeClass("col-lg-2 col-sm-4 ").empty();
                    columnData.addClass("editing");
                    $(this).removeClass("primary--btn").addClass("info--btn");
                    $(this).find("i").removeClass("la-pen").addClass("la-undo-alt");
                    
                }

                columnData.find(".location").toggleClass('col-lg-3 col-lg-2');
                columnData.find(".value").toggleClass('col-lg-6 col-sm-8 col-lg-5 col-sm-7');

                var select = columnData.find(".type").toggleClass("d-none").addClass("col-lg-2 col-sm-4 ").append(`
                    <select class="form-select" name="type[]" aria-label="Large select example">
                        <option selected disabled>Type</option>
                        <option value="1">Date</option>
                        <option value="3">Number</option>
                        <option value="4">Text</option>
                    </select>
                `);
            });

            function generateSelectElement(header) {
                var formattedHeader = header.toString().replace(/_/g, ' ').replace(/\w\S*/g, function(txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });

                var selectOptions = `
                    <option class="custom-attribute" value="${header}::4" selected>${formattedHeader}</option>
                    <option ${header == "first_name" ? 'selected' : ''} value="first_name::4">First Name</option>
                    <option ${header == "last_name" ? 'selected' : ''} value="last_name::4">Last Name</option>
                    <option ${header == "whatsapp_contact" ? 'selected' : ''} value="whatsapp_contact::4">Whatsapp Contact</option>
                    <option ${header == "email_contact" ? 'selected' : ''} value="email_contact::4">Email Contact</option>
                    <option ${header == "sms_contact" ? 'selected' : ''} value="sms_contact::4">SMS Contact</option>
                    @foreach($filtered_meta_data as $attributeName => $attribute)
                        @php 
                            $attributeType = \Illuminate\Support\Arr::get($attribute, "type");
                        @endphp
                        <option ${header == "{{$attributeName}}" ? 'selected' : ''} value="{{ $attributeName.'::'.$attributeType }}">{{ucfirst(str_replace('_', ' ', $attributeName))}}</option>
                    @endforeach
                `;

                return `<select class="form-select select-attribute" name="header_value[]" aria-label="Large select example">${selectOptions}</select>`;
            }

            $(document).on('click', '.saveChanges', function () {
                var newRow = $('#add_new_row').is(':checked') ? 'true' : 'false';

                var headerLocation = [];
                var headerValue = [];

                $('.columnData').each(function () {
                    
                    var location = $(this).find("input[name='header_location[]']").val();
                    var value;
                    if ($(this).find("input[name='custom_name[]']").length > 0) {
                        
                        value = $(this).find("input[name='custom_name[]']").val().replace(/\s+/g, '_').toLowerCase()+'::'+$(this).find("select[name='type[]']").val();
                        
                    } else if ($(this).find("select[name='header_value[]']").length > 0) {
                        value = $(this).find("select[name='header_value[]']").val();
                    } else {
                        value = null;
                    }

                    headerLocation.push(location);
                    headerValue.push(value);
                });

                var hasInvalidValue = false;
                var seen = {};
                var duplicateValue = false;

                $.each(headerValue, function(index, value) {

                    if (value === "::null" || /^.+::null$/.test(value)  || value === "::undefined" || /^.+::undefined$/.test(value)  || value === "::" || value === "null::null" ||
                    value === "::5" || value === "::6" || value === "::7" || 
                    value === "::8" || value === "::9" || /^.+::[5-9]$/.test(value) || value.indexOf("::") === -1) {

                        hasInvalidValue = true;
                    }

                    var parts = value.split("::");
                    var key = parts[0];

                    if (seen[key]) {
                        duplicateValue = true;
                        return false; 
                    }
                    seen[key] = true;
                });

                if (hasInvalidValue) {

                    notify("error", "Please Make Sure that no field is empty of invalid.");

                } else if(duplicateValue) {
                    
                    notify("error", "Duplicate Column Name Detected, column names can not be same.");
                } else {

                    $('#send_add_new_row').val(newRow);
                    $('#send_header_location').val(headerLocation.join(','));
                    $('#send_header_value').val(headerValue.join(','));
                    $('#updateTableData').modal('hide');
                }
            });


            $("#file_upload").change(function () {

                var fileInput = $(this)[0];
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append('file', file);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route("admin.contact.upload.file") }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function (response) {
                        if (response.status) {
                            $(".upload_file_loader").removeClass("d-none");
                            var uploadTime = new Date().toLocaleString();
                            var htmlBlock = createFileHtmlBlock(file, uploadTime);
                            var fileName = response.file_name;
                            var filePath = response.file_path;
                            $("input[name='file__name']").val(fileName);
                            $(".uplaod-file").addClass("d-none");
                            $(".file__info").removeClass("d-none").html(htmlBlock);


                            var reader = new FileReader();
                            
                            reader.onload = function (e) {
                                var fileData = e.target.result;
                                var fileType = file.type;
                               
                                $.ajax({
                                    url: '{{ route("admin.contact.parse.file") }}',
                                    type: 'POST',
                                    data: {
                                        fileType: fileType,
                                        filePath: filePath
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    xhr: function () {

                                        var xhr = new window.XMLHttpRequest();

                                        xhr.upload.addEventListener("progress", function (evt) {
                                            if (evt.lengthComputable) {
                                                var percentComplete = (evt.loaded / evt.total) * 100;
                                                
                                                $(".progress-bar").css("width", percentComplete + "%");
                                            }
                                        }, false);

                                        return xhr;
                                    },
                                    success: function (response) {

                                        $("input[name='import_contact']").val('true');
                                       
                                        keyMappingModal(response.data);
                                        
                                    },
                                    error: function (error) {
                                        
                                        console.error(error);
                                    }
                                });
                            };
                            reader.readAsText(file);

                            $(".edit__file").on("click", function(){
                                keyMappingModal(response.data);
                            })
                            $(".remove__file").on("click", function () {
                                deleteFile(fileName, csrfToken)
                                    .done(function (deleteResponse) {
                                        $('input[name="import_contact"]').val('false');
                                        if (deleteResponse.status) {
                                            var progressBar = $(".progress").removeClass("d-none");
                                            $(".headers").empty()
                                            handleDeleteSuccess();
                                        } else {
                                            handleDeleteError();
                                        }
                                    })
                                    .fail(function (deleteError) {
                                        handleDeleteError();
                                    })
                                    .always(function () {
                                        $("#dynamic_table").empty();
                                    });
                            });
                        } else {
                            console.error('Error uploading file.');
                        }
                    },
                    error: function (error) {
                        console.error('Error uploading file:', error);
                    }
                });
                
            });

            function handleDeleteSuccess() {
                $(".upload_file_loader").addClass("d-none");
                $(".table-preloader").removeClass("d-none");
                $("#file_upload").val("");
                $(".file__info").addClass("d-none");
                $(".uplaod-file").removeClass("d-none");
            }

            function handleDeleteError() {
                $(".upload_file_loader").addClass("d-none");
                $("#file_upload").val("");
                $(".file__info").addClass("d-none");
                $(".uplaod-file").removeClass("d-none");
                console.error('Error deleting file.');
            }

            function bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0) return '0 Byte';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            }
        });
        
	})(jQuery);
</script>
@endpush


