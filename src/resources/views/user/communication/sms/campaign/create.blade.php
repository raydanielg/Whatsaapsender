@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('user.layouts.app')
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
                                <a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
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
      
        <form action="{{route('user.communication.sms.campaign.save', ['type' => 'sms'])}}" class="step-content" method="POST" enctype="multipart/form-data">
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
                                        <label for="contacts" class="form-label">{{ translate("Choose Group") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Only the contact groups with sms contact are available") }}">
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
                    @if($plan_access->type == App\Enums\StatusEnum::FALSE->status())
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Sending Method") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                            <div class="row gy-3 align-items-end">
                            <div class="col-12">
                                <div class="form-inner">
                                <label for="chooseMethod" class="form-label">{{ translate("Choose Method") }}</label>
                                <select class="form-select select2-search" id="chooseMethod" data-placeholder="{{ translate("Choose a sending method") }}" aria-label="chooseMethod" name="method">
                                    <option value=""></option>
                                    <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
                                    <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("API Gateway") }}</option>
                                </select>
                                </div>
                            </div>
                            <div id="selectAndroidGateway" class="row mt-3"></div>
                            <div id="selectApiGateway" class="row mt-3"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif
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
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("SMS type") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                            <div class="radio-buttons-container message-type">
                            <div class="radio-button">
                                <input class="radio-button-input" type="radio" name="sms_type" id="smsTypeText" value="plain" checked="" />
                                <label for="smsTypeText" class="radio-button-label">
                                <span class="radio-button-custom"></span> {{ translate("Plain") }} </label>
                            </div>
                            <div class="radio-button">
                                <input class="radio-button-input" type="radio" name="sms_type" id="smsTypeUnicode" value="unicode" />
                                <label for="smsTypeUnicode" class="radio-button-label">
                                <span class="radio-button-custom"></span> {{ translate("Unicode") }} </label>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Message body") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                            <div class="form-inner position-relative speech-to-text" id="messageBox">
                            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 mb-2">
                                <label for="message" class="form-label mb-0">{{ translate("Write message") }}</label>
                                <button class="i-btn btn--sm p-0 bg-transparent text-primary available-template" type="button">
                                <i class="ri-layout-fill fs-5"></i>{{ translate("Use Template") }}</button>
                            </div>
                            <textarea class="form-control" name="message[message_body]" id="message" rows="5" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nIf Contact is being selected from a group then to mention First Name Use {{". 'first_name' ."}} \nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}"@endphp"></textarea>
                            <p class="form-element-note message_body_counter"></p>
                            <div class="voice-icon">
                                <button type="button" class="icon-btn btn-sm primary-soft circle hover" id="text-to-speech-icon">
                                <i class="ri-mic-fill"></i>
                                <span class="tooltiptext"> {{ translate("Voice") }} </span>
                                </button>
                            </div>
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
@section("modal")
<div class="modal fade" id="availableTemplate" tabindex="-1" aria-labelledby="availableTemplate" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"> {{ translate("SMS Templates") }} </h5>
              <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                  <i class="ri-close-large-line"></i>
              </button>
          </div>
          <div class="modal-body modal-md-custom-height">
              <div class="row g-4">
                <div class="col-12">
                  <div class="form-inner">
                    <label for="chooseTemplate" class="form-label">{{ translate("Available Templates") }}</label>
                    <select class="form-select select2-search" id="chooseTemplate" data-placeholder="{{ translate("Choose an SMS template") }}" aria-label="chooseTemplate">
                      <option value=""></option>
                      @foreach($templates as $template)
                        <option value="{{ json_encode($template->template_data) }}">{{ $template->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
  
                <div class="template-data"></div>
              </div>
          </div>
          
          <div class="modal-footer">
              <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
              <button type="submit" id="saveTemplateButton" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
          </div>
        </div>
    </div>
  </div>
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
  <script src="{{asset('assets/theme/global/js/campaign/sms/stage-step.js')}}"></script>
@endpush
@push('script-push')
<script>
	"use strict";
    select2_search($('.select2-search').data('placeholder'));

    $(document).ready(function() {

        const remainingCredit = {{ $remaining_credit }};
        if(remainingCredit != '-1') {
            
            const smsWordCount = {{ site_settings('sms_word_count') }};
            const smsWordUnicodeCount = {{ site_settings('sms_word_unicode_count') }};
            function updateMessageBodyCounter() {
                const message = $('#message').val();
                const charCount = message.length;
                const wordCount = message.trim().split(/\s+/).length;
                const smsType = $('input[name="sms_type"]:checked').val();
                const charLimit = smsType === 'unicode' ? smsWordUnicodeCount : smsWordCount;
                const charsLeft = charLimit * remainingCredit - charCount;

                $('.message_body_counter').text(`${charCount} Character | ${wordCount} Words | ${charsLeft} Characters Left | 1 SMS (${charLimit} Characters/SMS)`);
                
                if (charsLeft < 0) {
                    $('#message').val(message.substring(0, charLimit * remainingCredit));
                    updateMessageBodyCounter();
                }
            }
            $('#message').on('input', updateMessageBodyCounter);
            $('input[name="sms_type"]').on('change', updateMessageBodyCounter);
            updateMessageBodyCounter();
        }
    });
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
                    url: '{{ route("user.contact.group.fetch", ["type" => "meta_data"]) }}',
                    type: 'POST',
                    data: {
                        group_ids: selectedValues,
                        channel: "sms",
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

        var androidGateways = @json($android_gateways);

        function createSelectField(options, placeholder, name) {

            var select = $('<select>', {

                class: 'form-select select2-search',
                name: name,
                'data-placeholder': placeholder
            });
            $.each(options, function(index, option) {

                select.append($('<option>', {
                    value: option.value,
                    text: option.text,
                    'data-sims': option.sims || ''
                }));
            });
            return select;
        }

        function appendField(container, label, field, id, colClass = 'col-6') {

            var fieldContainer = $('<div>', { class: colClass, id: id }).append(
                $('<div>', { class: 'form-inner' }).append(
                    $('<label>', { class: 'form-label', text: label }),
                    field
                )
            );
            container.append(fieldContainer);
            fieldContainer.hide().fadeIn();
        }

        function removeField(fieldId) {
            $('#' + fieldId).fadeOut(function() {
                $(this).remove();
            });
        }

        $('#chooseMethod').change(function() {
        var apiGateways = @json($api_gateways);
        var method = $(this).val();
        var selectAndroidGateway = $('#selectAndroidGateway');
        var selectApiGateway = $('#selectApiGateway');
        selectAndroidGateway.empty();
        selectApiGateway.empty();
            if (method == "{{ \App\Enums\StatusEnum::FALSE->status() }}") {

                var planaccess_type = "{{ $plan_access->type == \App\Enums\StatusEnum::FALSE->status() ? 'true' : 'false' }}"

                var androidGatewayOptions = [
                    { value: '-1', text: 'Automatic' }
                ];

                $.each(androidGateways, function(index, gateway) {
                    androidGatewayOptions.push({
                        value: gateway.id,
                        text: gateway.name,
                        sims: JSON.stringify(gateway.sim_info.map(function(sim) {
                            return {
                                id: sim.id,
                                sim_number: sim.sim_number
                            };
                        }))
                    });
                });

                var androidGatewaySelect = createSelectField(androidGatewayOptions, '{{ translate("Choose an Android Gateway") }}', 'androidGatewaySelect');
                appendField(selectAndroidGateway, '{{ translate("Choose Android Gateway") }}', androidGatewaySelect, 'androidGatewaySelectField', 'col-12');

                androidGatewaySelect.change(function() {
                    var selectedValue = $(this).val();

                    removeField('simSelectField');
                    if (selectedValue != '-1') {
                        
                        removeField('simSelectField');
                        
                        $('#androidGatewaySelectField').removeClass('col-12').addClass('col-6');
                        var sims = JSON.parse($(this).find('option:selected').attr('data-sims') || '[]');
                        var simOptions = sims.map(function(sim) {
                            return {
                                value: sim.id,
                                 text: planaccess_type != 'false' ? sim.sim_number : '**********'
                            };
                        });
                        var simSelect = createSelectField(simOptions, '{{ translate("Choose a SIM") }}', 'android_gateway_sim_id');
                        appendField(selectAndroidGateway, '{{ translate("Choose SIM") }}', simSelect, 'simSelectField', 'col-6');
                    } else {
                        removeField('simSelectField');
                        $('#androidGatewaySelectField').removeClass('col-6').addClass('col-12');
                    }
                });
            } else if (method == "{{ \App\Enums\StatusEnum::TRUE->status() }}") {
                var apiGatewayTypeOptions = [
                    { value: '-1', text: 'Automatic', selected: 'selected' }
                ];

                $.each(apiGateways, function(type, gateways) {
                    apiGatewayTypeOptions.push({
                        value: type,
                        text: textFormat(['_'], type.replace(/^\d+/, ''), ' ')
                    });
                });

                var apiGatewayTypeSelect = createSelectField(apiGatewayTypeOptions, '{{ translate("Select API Gateway Type") }}', 'apiGatewayTypeSelect');
                appendField(selectApiGateway, '{{ translate("Select API Gateway Type") }}', apiGatewayTypeSelect, 'apiGatewayTypeSelectField', 'col-12');

                apiGatewayTypeSelect.change(function() {
                    var selectedType = $(this).val();

                    if (selectedType == '-1') {
                    
                        removeField('apiGatewaySelectField');
                        $('#apiGatewayTypeSelectField').removeClass('col-6').addClass('col-12');
                    } else {
                        removeField('apiGatewaySelectField');
                        $('#apiGatewayTypeSelectField').removeClass('col-12').addClass('col-6');
                        var apiGatewayOptions = Object.entries(apiGateways[selectedType]).map(function([id, name]) {
                            return {
                                value: id,
                                text: name
                            };
                        });
                        var apiGatewaySelect = createSelectField(apiGatewayOptions, '{{ translate("Select API Gateway") }}', 'apiGatewaySelect');
                        appendField(selectApiGateway, '{{ translate("Select API Gateway") }}', apiGatewaySelect, 'apiGatewaySelectField', 'col-6');
                    }
                });
            }
        });
        $('form').submit(function() {

            if($('#chooseMethod').val() == {{ \App\Enums\StatusEnum::FALSE->status() }}) {

            var androidGatewaySelect  = $('#androidGatewaySelect').val();
            var android_gateway_sim_id  = $('#android_gateway_sim_id').val();
            $('#gateway_id_manual').remove(); 
            $('#gateway_id_automatic').remove(); 

            if (androidGatewaySelect != '-1') { 

                $('<input>').attr({
                    type: 'hidden',
                    name: 'gateway_id',
                    id: 'gateway_id_manual',
                    value: android_gateway_sim_id
                }).appendTo('form');
            } else { 

                $('<input>').attr({
                    type: 'hidden',
                    name: 'gateway_id',
                    id: 'gateway_id_automatic',
                    value: '-1'
                }).appendTo('form');
            }
            } else {

            var apiGatewayTypeSelectField  = $('#apiGatewayTypeSelectField select').val();
            var selectApiGateway  = $('#apiGatewaySelectField select').val();
            $('#gateway_id_manual').remove(); 
            $('#gateway_id_automatic').remove(); 

            if (apiGatewayTypeSelectField  != '-1') { 
                
                $('<input>').attr({
                    type: 'hidden',
                    id: 'gateway_id_manual',
                    name: 'gateway_id',
                    value: selectApiGateway
                }).appendTo('form');
            } else { 

                $('<input>').attr({
                    type: 'hidden',
                    id: 'gateway_id_automatic',
                    name: 'gateway_id',
                    value: '-1'
                }).appendTo('form');
            }

            }
        
        
        });

        function createSelectField(options, placeholder, id) {
            var select = $('<select></select>').addClass('form-select').attr('id', id);
            $.each(options, function(index, option) {
                var opt = $('<option></option>').attr({
                    value: option.value,
                    selected: option.selected,
                    disabled: option.disabled
                }).text(option.text);
                if (option.sims) {
                    opt.attr('data-sims', option.sims);
                }
                select.append(opt);
            });
            return select;
        }
        function appendField(container, labelText, field, fieldId, colClass) {
            var fieldContainer = $('<div></div>').addClass(colClass).attr('id', fieldId);
            var label = $('<label></label>').addClass('form-label').text(labelText);
            fieldContainer.append(label).append(field);
            container.append(fieldContainer);
        }
    });

    $(document).ready(function() {

        $('#form_review').on('click', function() {

            function getCampaignData() {

                const campaignName = document.querySelector('input[name="name"]').value;

                const selectedGroupsElement = document.getElementById('contacts');
                const selectedGroups = Array.from(selectedGroupsElement.selectedOptions).map(option => option.text).join(', ');

                const sendingMethodElement = document.getElementById('chooseMethod');
                const sendingMethod = sendingMethodElement.options[sendingMethodElement.selectedIndex].text;

                const scheduleAt = document.querySelector('input[name="schedule_at"]').value;
                const scheduleAtFormatted = new Date(scheduleAt).toLocaleString('en-US', {
                    month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true
                });

                const repeatTime = document.getElementById('repeat_time').value;
                const repeatFormatElement = document.getElementById('repeat_format');
                const repeatFormat = repeatFormatElement.options[repeatFormatElement.selectedIndex].text.toLowerCase();

                // Determine repetition text
                const deliveryRepetition = repeatTime == 0 ? "No repetition" : `${repeatTime} time(s) in every ${repeatFormat}`;

                // Return the formatted data
                return {
                    "Campaign Name": campaignName,
                    "Selected groups": selectedGroups,
                    "Sending Method": sendingMethod,
                    "Schedule At": scheduleAtFormatted,
                    "Delivery Repetition": deliveryRepetition
                };
            }

            function updateCampaignInfo() {
                var campaignData = getCampaignData();
                var campaignInfo = $('.campaign-info');
                campaignInfo.empty();

                var ul = $('<ul></ul>');

                $.each(campaignData, function(key, value) {
                    var listItem = '<li><span>' + key + '</span><span>: ' + value + '</span></li>';
                    ul.append(listItem);
                });

                campaignInfo.append(ul);
                $('#preview-text').text("Hi! I went shopping today and found the earrings you’ve been looking for. I got them for you. My treat! Let’s go together.");
            }

            updateCampaignInfo();
        });
    });
</script>
@endpush
