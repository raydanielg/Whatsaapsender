@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="row gy-4">
                <div class="col-md-5">
                    <div class="page-header-left">
                        <h2>{{ $title }}</h2>
                        <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ translate("Create Campaign") }} </li>
                            </ol>
                        </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="step-wrapper mb-0 justify-content-lg-start justify-content-md-end">
                      <ul class="progress-steps">
                        <li class="step-item activated active">
                          <span>{{ translate("01") }}</span> {{ translate("Setup") }}
                        </li>
                        <li class="step-item">
                          <span>{{ translate("02") }}</span> {{ translate("Schedule") }}
                        </li>
                        <li class="step-item">
                          <span>{{ translate("03") }}</span> {{ translate("Message") }}
                        </li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
      
        <form action="{{route('admin.communication.whatsapp.campaign.save', ['type' => 'whatsapp'])}}" class="step-content" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="step-content-item active">
            <div class="card">
                <div class="form-header">
                <div class="row g-3 align-items-center">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                    <h4 class="card-title">{{ translate("Campaign Create") }}</h4>
                    </div>
                </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate('From Group')}}</h5>
                            </div>
                                <div class="col-xxl-8 col-lg-9">
                                <div class="row gy-3 align-items-end">
                                    <div class="col-12">
                                    <div class="form-inner">
                                        <label for="contacts" class="form-label">{{ translate("Choose Group") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Only the contact groups with whatsapp contact are available") }}">
                                            <i class="ri-question-line"></i>
                                            </span>
                                        </label>
                                        <select class="form-select select2-search" id="contacts" data-placeholder="{{ translate("Choose groups") }}" aria-label="contacts" name="contacts[]" multiple>
                                        <option value=""></option>
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <div class="col-12 group-logic d-none">
                                        <div class="form-inner">
                                            <label class="form-label"> {{ translate("Add Logic") }} </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" for="group_logic" >{{translate('Add Logic to Groups to select specific contacts based on attrbitues')}}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input type="checkbox" value="true" name="group_logic" id="group_logic" class="switch-input">
                                                    <label for="group_logic" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-item group-logic-items mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Campaign Name") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-xl-9">
                            <div class="form-inner">
                            <label for="name" class="form-label">{{ translate("Name") }}
                            </label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{ translate("Enter your campaign name") }}" aria-label="name" />
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Sending Method") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-lg-9 whatsapp_device">
                            <div class="row gy-3 align-items-end">
                              <div class="col-12 whatsapp_sending_mode">
                                <div class="form-inner">
                                  <label for="whatsapp_sending_mode" class="form-label">{{ translate("Choose Method") }}</label>
                                  <select class="form-select select2-search" id="whatsapp_sending_mode" data-placeholder="{{ translate("Choose a sending method") }}" aria-label="whatsapp_sending_mode" name="method">
                                    <option value=""></option>
                                    <option selected value="without_cloud_api">{{ translate('Without Cloud API') }}</option>
                                    <option value="cloud_api">{{ translate('Cloud API') }}</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-12 whatsapp_device_option d-none">
                                <div class="form-inner">
                                  <label for="whatsapp_device_id" class="form-label">{{ translate("Choose A Node Device") }}</label>
                                  <select class="form-select select2-search" id="whatsapp_device_id" data-placeholder="{{ translate("Choose a node device") }}" data-show="5" aria-label="whatsapp_device_id">
                                    <option value=""></option>
                                    <option selected value="-1">{{ translate("Automatic") }}</option>
                                    @foreach($devices as $device_key => $device_name)
                                      <option value="{{$device_key}}">{{($device_name)}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="col-12 whatsapp_cloud_api_option d-none">
                                <div class="form-inner">
                                  <label for="whatsapp_cloud_api_id" class="whatsapp_device_select_label form-label">{{ translate("Choose Cloud API Account") }}</label>
                                  <select class="form-select select2-search repeat-scale whatsapp_business_api" data-placeholder="{{ translate("Choose a cloud api") }}" data-show="5" id="whatsapp_cloud_api_id">
                                    <option value=""></option>
                                    @foreach($cloud_api_accounts as $cloud_api_account_key => $cloud_api_account_name)
                                      <option value="{{$cloud_api_account_key}}">{{($cloud_api_account_name)}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-between">
                                <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                                <button type="button" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="step-content-item">
            <div class="card">
                <div class="form-header">
                <div class="row g-3 align-items-center">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                    <h4 class="card-title">{{ translate("Campaign Create") }}</h4>
                    </div>
                </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Schedule At") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                            <div class="form-inner">
                            <label for="ChooseGateway" class="form-label">{{ translate("Choose Date & Time") }}
                            </label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control singleDateTimePicker" placeholder="{{ translate("Select schedule time") }}" name="schedule_at"/>
                                <span class="input-group-text calendar-icon" id="filterByDate">
                                <i class="ri-calendar-2-line"></i>
                                </span>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Delivery Logic") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-xl-9">
                            <div class="form-inner">
                            <label for="ChooseGateway" class="form-label">{{ translate("Repetition") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Configure the campaign logic, if you dont want this campaign to repeat again then simply keep '0' in the input field") }}">
                                <i class="ri-question-line"></i>
                                </span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">{{ translate("Deliver campaigns in every ") }}</span>
                                <input type="number" name="repeat_time" id="repeat_time" aria-label="{{ translate("Repeat Times") }}" class="form-control" placeholder="{{ translate("Amount of times you want this campaign to repeat") }}" min="0" value="0"/>
                                
                                <select id="repeat_format" class="form-select" aria-label="{{ translate("Repeat Format") }}" name="repeat_format" disabled>
                                <option value="-1" disabled selected>{{ translate("Select a repeat format") }}</option>
                                @foreach(\App\Enums\CampaignRepeatEnum::toArray() as $key => $value)
                                    <option value="{{ $value }}">{{ ucfirst(transformToCamelCase($key)) }}</option>
                                @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-xxl-10">
                    <div class="form-action justify-content-between">
                        <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                        <button type="button" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="step-content-item">
            <div class="card">
                <div class="form-header">
                <h4 class="card-title">{{ translate("Write Campaign Message") }}</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element select-cloud-templates d-none">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Select WhatsApp Templates") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-lg-9 whatsapp_templates">
                            <div class="row gy-3 align-items-end">
                              <div class="col-12">
                                <div class="form-inner">
                                  <label for="whatsapp_template_id" class="form-label">{{ translate("Choose Template") }}</label>
                                  <select class="form-select select2-search repeat-scale whatsapp-template-id" data-placeholder="{{ translate("Choose a template for api") }}" data-show="5" name="whatsapp_template_id" id="whatsapp_template_id"></select>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                     
                    <div class="form-element whatsapp_device_option d-none">
                      <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                          <h5 class="form-element-title">{{ translate("Message body") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                          <div class="form-inner">
                            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 mb-2">
                              <label for="message" class="form-label mb-0">{{ translate("Write message") }} </label>
                              
                            </div>
                            <div class="ig-text-editor speech-to-text without-cloud-message d-none" id="messageBox">
                              <input type="text" name="without_cloud_api" hidden>
                              <textarea class="form-control" name="message[message_body]" id="message" rows="5"></textarea>
                              <div class="text-editor">
                                <ul class="editor-actions">
                                  <li class="action-item">
                                    <button class="style-link" data-style="bold" type="button">
                                      <i class="ri-bold"></i>
                                      <span class="tooltiptext">{{ translate("Bold") }} </span>
                                    </button>
                                  </li>
                                  <li class="action-item">
                                    <button class="style-link" data-style="italic" type="button">
                                      <i class="ri-italic"></i>
                                      <span class="tooltiptext">{{ translate("Italic") }} </span>
                                    </button>
                                  </li>
                                  <li class="action-item">
                                    <button class="style-link" data-style="mono" type="button">
                                      <i class="ri-space"></i>
                                      <span class="tooltiptext">{{ translate("Monospace") }} </span>
                                    </button>
                                  </li>
                                  <li class="action-item">
                                    <button class="style-link" data-style="strike" type="button">
                                      <i class="ri-format-clear"></i>
                                      <span class="tooltiptext">{{ translate("Strikethrough") }} </span>
                                    </button>
                                  </li>
                                </ul>
                                <ul class="editor-actions">
                                  <li class="action-item">
                                    <button type="button">
                                      <label for="media_upload" class="media_upload_label cursor-pointer">
                                        <i class="ri-image-add-fill"></i>
                                        <div id="uploadfile">
                                            <input type="file" id="media_upload" hidden>
                                        </div>
                                      </label>
                                      <span class="tooltiptext">{{ translate("Media") }} </span>
                                      
                                    </button>
                                  </li>
                                  
                                  <li class="action-item">
                                    <button id="text-to-speech-icon" type="button">
                                      <i class="ri-mic-line"></i>
                                      <span class="tooltiptext">{{ translate("Speech-to-text") }} </span>
                                    </button>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="file-preview mt-4 d-none" id="add_media"></div>
                        </div>
                      </div>
                    </div>
        
                    <div class="form-element whatsapp-cloud-steps d-none">
                      <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                          <h5 class="form-element-title">{{ translate("Cloud API Template Data") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                          <div class="form-inner">
                            <input type="text" name="cloud_api" hidden>
                            <input type="text" name="cloud_api_data" hidden>
                
                            <div class="form-tab">
                                <ul class="nav whatsapp-tabs mb-3 gap-2" id="whatsapp_fields" role="tablist"></ul>
                            </div>
                            <div class="tab-content whatsapp-tab-content" id="whatsapp_fields"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-action justify-content-between">
                        <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                        <button type="submit" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                        <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                    </div>
                </div>
            </div>
            </div>
      </form>
    </div>
</main>
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
  <script src="{{asset('assets/theme/global/js/whatsapp/whatsapp.js')}}"></script> 
  <script src="{{asset('assets/theme/global/js/whatsapp/stage-step.js')}}"></script> 

@endpush
@push('script-push')
<script>
	"use strict";
    select2_search($('.select2-search').data('placeholder'));

    $(document).ready(function() {
      
        function enableSelect() {
            $('#repeat_format').prop('disabled', false);
            $('#repeat_format').attr('name', 'repeat_format'); 
        }

        function disableSelect() {
            $('#repeat_format').prop('disabled', true);
            $('#repeat_format').removeAttr('name');
            $('#repeat_format').val('-1');
        }

        function validateInput() {
            let repeatTime = parseInt($('#repeat_time').val(), 10);
            if (repeatTime < 0 || isNaN(repeatTime)) {
                $('#repeat_time').val(0);
                repeatTime = 0;
            }
            if (repeatTime > 0) {
                enableSelect();
            } else {
                disableSelect();
            }
        }

        $('#repeat_time').on('input', function() {
            validateInput();
        });
        validateInput();

        function handleMergedAttributes(attributes) {

            var initialAttributeOptions = $(".group-logic-items").html();
            $('#group_id').on('select2:unselect', function (e) {
                $('.group-logic').addClass('d-none');
                resetAttributeOptions();

                function resetAttributeOptions() {

                    $(".group-logic-items").html(initialAttributeOptions);
                }
            });
            $(".group-logic").removeClass("d-none");
            $('#group_logic').change(function() {
                var selectedAttributeValue;
                if ($(this).is(':checked')) {

                    $(".group-logic-items").html(`
                        <div class="row">
                            <div class="col-md-6">
                                <label for="attribute_name" class="form-label">Attributes<sup class="text-danger">*</sup></label>
                                <select class="form-select repeat-scale" required name="attribute_name" id="attribute_name">
                                    <option selected disabled>-- Select an Attribute --</option>
                                    <option value="sms_contact">SMS Contact Number</option>
                                    <option value="whatsapp_contact">WhatsApp Contact Number</option>
                                    <option value="email_contact">Email Address</option>
                                    <option value="first_name">First Name</option>
                                    <option value="last_name">Last Name</option>
                                    ${getAttributesOptionsHTML(attributes)}
                                </select>
                            </div>
                            <div class="col-md-6" id="logic-input-container"></div>
                        </div>
                    `);

                    $('#attribute_name').change(function() {
                        selectedAttributeValue = $(this).val();

                        $('#logic-input-container').html(`
                            ${getLogicInputHTML(selectedAttributeValue)}
                        `);
                    });
                } else {
                    $(".group-logic-items").html('');
                }
            });
            function getAttributesOptionsHTML(attributes) {
                return Object.keys(attributes)
                    .map(attribute => `<option value="${attribute}::${attributes[attribute]}">${formatAttributeName(attribute)}</option>`)
                    .join('');
            }
            function formatAttributeName(attribute) {

                return attribute.replace(/_/g, ' ').replace(/\b\w/g, firstLetter => firstLetter.toUpperCase());
            }

            function getLogicInputHTML(attribute) {

            var value = attributes[attribute.split("::")[0]];

            if (value && value != undefined) {

                if (value == {{\App\Models\GeneralSetting::DATE}}) {

                    return `<div class="row"><div title="Only the contacts with this date in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Select a Date") }}<sup class="text-danger">*</sup></label>
                                <input type="datetime-local" class="date-picker form-control" name="logic" id="logic"></div>
                                <div title="Only the contacts within this range in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Select The Range") }}<sup class="text-danger">*</sup></label>
                                <input type="datetime-local" class="date-picker form-control" name="logic_range" id="logic_range"></div></div>`;

                } else if (value == {{\App\Models\GeneralSetting::BOOLEAN}}) {

                    return `<label for="attribute_name" class="form-label">{{ translate("Conditions") }}<sup class="text-danger">*</sup></label>
                            <select class="form-select repeat-scale" required name="logic" id="logic">
                                <option selected disabled>${('-- Select a Logic --')}</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>`;
                } else if (value == {{\App\Models\GeneralSetting::NUMBER}}) {

                    return `<div class="row"><div title="Only the contacts with this number in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" name="logic" id="logic" placeholder="Enter a number"></div
                            <div class="row"><div title="Only the contacts within this range in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Range") }}<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" name="logic_range" id="logic_range" placeholder="Enter a number"></div`;
                } else if (value == {{\App\Models\GeneralSetting::TEXT}}) {

                    return `<label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" name="logic" id="logic" placeholder="Enter text">`;
                }
            } else {

                return `<label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" name="logic" id="logic" placeholder="Enter text">`;
            }
            }
        }

        function handleEmptyContacts(message) {

            $('.group-logic').addClass("d-none");
            $('.group-logic-items').addClass("d-none");
            $('input[name=logic]').removeAttr('name');
            $('input[name=group_logic]').removeAttr('name');
            $('select[name=attribute_name]').removeAttr('name');
            $('select[name=attribute_name]').prop('disabled', true);
            notify('info', message);
        }

        $('#contacts').change(function() {

            var selectedValues = $(this).val();
            var channelValue = '{{ $type }}';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (selectedValues) {
                $.ajax({
                    url: '{{ route("admin.contact.group.fetch", ["type" => "meta_data"]) }}',
                    type: 'POST',
                    data: {
                        group_ids: selectedValues,
                        channel: "whatsapp",
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {

                        if (response.status == true) {

                            $('.group-logic').removeClass("d-none");
                            $('.group-logic-items').removeClass("d-none");
                            $('input[id=logic]').attr('name', 'logic');
                            $('input[id=group_logic]').attr('name', 'group_logic');
                            $('select[id=attribute_name]').attr('name', 'attribute_name');
                            $('select[name=attribute_name]').prop('disabled', false);
                            handleMergedAttributes(response.merged_attributes);
                        } else {

                            handleEmptyContacts(response.message);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        });

        $('.available-template').on('click', function() {

            const modal = $('#availableTemplate');
            modal.modal('show');

            $('#chooseTemplate').change(function() {

                var selectedTemplate = $(this).val();
                
                if (selectedTemplate) {

                    var templateData = JSON.parse(selectedTemplate);
                    var templateMessage = templateData.message;
                    $('.template-data').empty();
                    var templateTextArea = $('<textarea>', {

                        rows: '5',
                        readonly: true,
                        class: 'form-control',
                        id: 'sms_template_message',
                        required: ''
                    }).val(templateMessage);

                    $('.template-data').append('<div class="col-lg-12"><div class="form-inner"><label for="sms_template_add_message" class="form-label">{{translate('Template Body')}}<span class="text-danger">*</span></label></div></div>').find('.form-inner').append(templateTextArea);
                } else {

                    $('.template-data').empty();
                }
            });
        });

        var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        var speechElements = document.querySelectorAll(".speech-to-text");
        var selectedTemplateMessage = '';
        $('#chooseTemplate').change(function() {

        var selectedTemplate = $(this).val();
        if (selectedTemplate) {

            var templateData = JSON.parse(selectedTemplate);
            $('#sms_template_message').val(templateData.message);
            selectedTemplateMessage = templateData.message;
        } else {

            $('#sms_template_message').val('');
            selectedTemplateMessage = '';
        }
        });
        $('#saveTemplateButton').click(function() {

        var mainTextArea = $('#message');
        insertAtCursor(mainTextArea[0], selectedTemplateMessage);
        $('#availableTemplate').modal('hide');
        });
        function insertAtCursor(textArea, text) {

        if (document.selection) {
        
            textArea.focus();
            var sel = document.selection.createRange();
            sel.text = text;
        } else if (textArea.selectionStart || textArea.selectionStart === 0) {
        
            var startPos = textArea.selectionStart;
            var endPos = textArea.selectionEnd;
            var scrollTop = textArea.scrollTop;
            textArea.value = textArea.value.substring(0, startPos) + text + textArea.value.substring(endPos, textArea.value.length);
            textArea.focus();
            textArea.selectionStart = startPos + text.length;
            textArea.selectionEnd = startPos + text.length;
            textArea.scrollTop = scrollTop;
        } else {

            textArea.value += text;
            textArea.focus();
        }
        }
        if (SpeechRecognition && speechElements.length > 0) {

        var recognition = new SpeechRecognition();
        var isListening = false;

        $('#text-to-speech-icon').on('click', function() {

            var messageBox = document.getElementById('messageBox');
            var textArea = messageBox.querySelector(".form-control");
            textArea.focus();

            recognition.onspeechstart = function() {

                isListening = true;
            };

            if (!isListening) {

                recognition.start();
            }

            recognition.onerror = function() {

                isListening = false;
            };

            recognition.onresult = function(event) {

                var currentText = textArea.value;
                var newText = event.results[0][0].transcript;
                textArea.value = currentText + " " + newText;
            };

            recognition.onspeechend = function() {
                isListening = false;
                recognition.stop();
            };
        });
        }

        
    });

    $(document).ready(function() {
      var $templateSelect = $('#whatsapp_template_id');
      var $cloudTemplatesDiv = $('.select-cloud-templates');
      var $whatsappCloudApiOption = $('.whatsapp_cloud_api_option');

      function updateTemplateOptions(cloudId) {
        $templateSelect.empty();
        $cloudTemplatesDiv.addClass('d-none');

        if (cloudId !== '-1') {
          $.ajax({
            url: "{{ route('admin.template.fetch', ['type' => 'whatsapp']) }}",
            type: 'GET',
            data: { cloud_id: cloudId },
            success: function(response) {
              if (response.templates.length > 0) {
                $templateSelect.append('<option value="" selected disabled>Select A Template</option>');
                $.each(response.templates, function(index, template) {
                  $templateSelect.append('<option value="' + template.id + '">' + template.name + '(' + template.template_data.language + ')' + '</option>');
                });
                $cloudTemplatesDiv.removeClass('d-none');
                allTemplates = response.templates;
              } else {
                $templateSelect.append('<option value="" selected disabled>No templates available</option>');
                allTemplates = [];
              }
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        }
      }

      $('#whatsapp_sending_mode').on('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === 'without_cloud_api') {
          $whatsappCloudApiOption.addClass('d-none');
        } else if (selectedValue === 'cloud_api') {
          $whatsappCloudApiOption.removeClass('d-none');
        }
      });

      $('#whatsapp_cloud_api_id').on('change', function() {
        var cloudId = $(this).val();
        updateTemplateOptions(cloudId);
      });
    });

    $(document).ready(function() {
      function appendGatewayIdInput(value) {
          $('input[name="gateway_id"]').remove();
          var $gatewayIdInput = $('<input>').attr({
              type: 'hidden',
              name: 'gateway_id',
              value: value || '-1'
          });
          $('form').append($gatewayIdInput);
      }

      function showHideOptions(selectedValue) {
          $('.whatsapp_device_option, .whatsapp_cloud_api_option').addClass('d-none');

          if (selectedValue === 'without_cloud_api') {
              $('.whatsapp_device_option').removeClass('d-none');
              appendGatewayIdInput($('#whatsapp_device_id').val());
          } else if (selectedValue === 'cloud_api') {
              $('.whatsapp_cloud_api_option').removeClass('d-none');
              appendGatewayIdInput($('#whatsapp_cloud_api_id').val());
          } else {
              appendGatewayIdInput('-1');
          }
      }

      function handleDeviceIdChange() {
          appendGatewayIdInput($('#whatsapp_device_id').val());
      }

      function handleCloudApiIdChange() {
          appendGatewayIdInput($('#whatsapp_cloud_api_id').val());
      }

      $('#whatsapp_sending_mode').on('change', function() {
          var selectedValue = $(this).val();
          showHideOptions(selectedValue);
      }).trigger('change');

      $('#whatsapp_device_id').on('change', handleDeviceIdChange);

      $('#whatsapp_cloud_api_id').on('change', handleCloudApiIdChange);

      $('form').on('submit', function(event) {
          var currentGatewayId = $('input[name="gateway_id"]').val();
      });
    });
</script>
@endpush
