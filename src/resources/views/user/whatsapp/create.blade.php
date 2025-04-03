@extends('user.layouts.app')
@section('panel')
<section>
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">{{translate('Send A Whatsapp Message')}}</h6>
            <div  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Suggestions Note">
                <button class="i-btn info--btn btn--sm d-xl-none info-note-btn"><i class="las la-info-circle"></i></button>
            </div>
        </div>

        <div class="card-body position-relative">
            <form action="{{route('user.whatsapp.store')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="text" name="channel" value="whatsapp" hidden>
                <div class="row g-4">
                    <div class="col-xl-9 order-xl-1 order-2">
                        <div class="form-wrapper">
                            <h6 class="form-wrapper-title">{{translate('Choose audience')}}</h6>
                            <div class="file-tab">
                                <ul class="nav nav-tabs mb-3 gap-2" id="myTabContent" role="tablist">
                                    <li class="nav-item single-audience" role="presentation">
                                        <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single-tab-pane" type="button" role="tab" aria-controls="single-tab-pane" aria-selected="true"><i class="las la-user"></i> {{ translate('Single Audience') }}</button>
                                    </li>
                                    <li class="nav-item group-audience" role="presentation">
                                        <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group-tab-pane" type="button" role="tab" aria-controls="group-tab-pane" aria-selected="false"><i class="las la-users"></i> {{ translate('Group Audience') }}</button>
                                    </li>
                                    <li class="nav-item import-file" role="presentation">
                                        <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-tab-pane" type="button" role="tab" aria-controls="file-tab-pane" aria-selected="false"><i class="las la-file-import"></i> {{ translate('Import File') }}</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="single-tab-pane" role="tabpanel" aria-labelledby="single-tab" tabindex="0">

                                        <div class="form-item">
                                            <label class="form-label">{{ translate('Single Input') }}</label>
                                            <input type="number"  class="form-control" value="{{old("number")}}" name="number" id="number" placeholder="{{ translate('Enter with country code ')}}{{$general->country_code}}{{ translate('XXXXXXXXX')}}" aria-label="number" aria-describedby="basic-addon11">

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="group-tab-pane" role="tabpanel" aria-labelledby="group-tab" tabindex="0">
                                        <div class="form-item">
                                            <label class="form-label" for="group">{{ translate('From Group')}}</label>
                                            <select class="form-control keywords" name="group_id[]" id="group" multiple="multiple">
                                                <option value="" disabled="">{{ translate('Select One')}}</option>
                                                @foreach($groups as $group)
                                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                                @endforeach
                                            </select>

                                            <div class="form-text">{{ translate('You can select single or multiple groups')}}</div>
                                            <div class="my-3 group-logic d-none">
                                                <div class="switch-container bg---light">
                                                    <label class="form-check-label text-capitalize" for="group_logic">{{translate('Add Logic to Groups to select specific contacts based on attrbitues')}}</label>
                                                    <label class="switch">
                                                        <input type="checkbox" value="true" name="group_logic" type="checkbox" id="group_logic">
                                                        <span class="slider"></span>
                                                    </label>
                                                </div>

                                                <div class="form-item group-logic-items mt-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="file-tab-pane" role="tabpanel" aria-labelledby="file-tab" tabindex="0">
                                        <div class="form-item">
                                            <label class="form-label" for="file">{{ translate('Import File')}} <span id="contact_file_name"></span></label>

                                            <div class="upload-filed">
                                                <input type="file" name="file" id="file" />
                                                <label for="file">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="upload-drop-file">
                                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#f6f0ff" d="M99.091 84.317a22.6 22.6 0 1 0-4.709-44.708 31.448 31.448 0 0 0-60.764 0 22.6 22.6 0 1 0-4.71 44.708z" opacity="1" data-original="#f6f0ff" class=""></path><circle cx="64" cy="84.317" r="27.403" fill="#6009f0" opacity="1" data-original="#6009f0" class=""></circle><g fill="#f6f0ff"><path d="M59.053 80.798v12.926h9.894V80.798h7.705L64 68.146 51.348 80.798zM68.947 102.238h-9.894a1.75 1.75 0 0 1 0-3.5h9.894a1.75 1.75 0 0 1 0 3.5z" fill="#f6f0ff" opacity="1" data-original="#f6f0ff" class=""></path></g></g></svg>
                                                        </span>
                                                        <span class="upload-browse">{{ translate("Upload File Here ") }}</span>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="form-text mt-3">
                                                {{ translate('Download Sample: ')}}
                                                <a href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{translate('csv')}}</a>
                                                {{-- <a href="{{route('demo.file.download', ['extension' => 'xlsx' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('xlsx')}}</a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-wrapper select-whatsapp_device mt-3">
                            <h6 class="form-wrapper-title">{{ translate('Select Sending method')}}</h6>
                            <div class="whatsapp_device">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="whatsapp_sending_mode" class="form-label">{{translate('Choose Sending Method')}}</label>
                                        <select class="form-select repeat-scale" name="whatsapp_sending_mode" id="whatsapp_sending_mode">
                                            <option {{ $allowed_access->type == \App\Enums\StatusEnum::FALSE->status() ? "selected" : "disabled"}}  value="cloud_api">{{translate('Cloud API')}} {{ $allowed_access->type == \App\Enums\StatusEnum::TRUE->status() ? translate("(Access Denied)") : " "}}</option>
                                            <option {{ $allowed_access->type == \App\Enums\StatusEnum::TRUE->status() ? "selected" : " "}} value="without_cloud_api">{{ translate('Without Cloud API') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="whatsapp_device_id" class="whatsapp_device_select_label form-label"></label>
                                        <select class="form-select repeat-scale whatsapp_node_devices d-none" name="whatsapp_device_id" id="whatsapp_device_id">
                                            <option selected value="-1">{{ translate('Automatic') }}</option>
                                            @foreach($whatsapp_node_devices as $gateway)
                                                <option value="{{$gateway->id}}">{{($gateway->name)}}</option>
                                            @endforeach
                                        </select>
                                        @if($allowed_access->type == \App\Enums\StatusEnum::FALSE->status())
                                            <select class="form-select repeat-scale whatsapp_business_api d-none" name="whatsapp_device_id" id="whatsapp_device_id">
                                                <option disabled selected>{{ translate('Select A Business Account') }}</option>
                                                @foreach($whatsapp_bussiness_api as $gateway)
                                                    <option value="{{$gateway->id}}">{{($gateway->name)}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-wrapper select-cloud-templates d-none mt-3">
                            <h6 class="form-wrapper-title">{{ translate('Select WhatsApp Templates')}}</h6>
                            <div class="whatsapp_templates">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="whatsapp_template_id" class="form-label">{{translate('Choose a Template')}}</label>
                                        <select class="form-select repeat-scale whatsapp-template-id" name="whatsapp_template_id" id="whatsapp_template_id"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-wrapper">
                            <h6 class="form-wrapper-title">{{translate('Message and Schedule')}}</h6>

                            <input type="text" name="cloud_api" hidden>
                            <input type="text" name="cloud_api_data" hidden>

                            <div class="file-tab whatsapp-cloud-steps d-none">
                                <ul class="nav nav-tabs whatsapp-tabs mb-3 gap-2" id="whatsapp_fields" role="tablist"></ul>
                                <div class="tab-content whatsapp-tab-content" id="whatsapp_fields"></div>
                            </div>

                            <div class="row g-4">
                                <div class="col-12 with-cloud-message d-none">
                                    <div class="form-item">
                                        <div class="template-fields"></div>
                                        
                                        <div class="old-template-fields">
                                            <label class="form-label" for="message">{{ translate('Write Message')}} <sup class="text-danger">*</sup></label>
                                        
                                            <div class="custom--editor">
                                                <div class="speech-to-text" id="messageBox">
                                                    <textarea readonly class="form-control message" name="message" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}"@endphp" aria-describedby="text-to-speech-icon">{{session()->get('old_sms_message')}}</textarea>
                                                    <span class="voice-icon" id="text-to-speech-icon">
                                                        <i class='fa fa-microphone text-to-speech-toggle'></i>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 d-flex align-items-center justify-content-md-between justify-content-start flex-wrap gap-3">
                                            <div class="text-end message--word-count"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 without-cloud-message d-none">
                                    <input type="text" name="without_cloud_api" hidden>
                                    <div class="form-item">
                                        <label class="form-label" for="message">{{ translate('Write Message')}} <sup class="text-danger">*</sup></label>
                                        <div class="my-2 d-flex flex-wrap align-items-center gap-2">
                                            <label for="media_upload" class="media_upload_label">
                                                <div id="uploadfile">
                                                    <input type="file" id="media_upload" hidden>
                                                </div>
                                                <div class="i-btn light--btn btn--sm">
                                                    {{ translate("Add Media") }}</span><i class="fa-solid fa-paperclip"></i>
                                                </div>
                                            </label>

                                            <a title="{{ translate("Bold") }}" href="#" class="style-link i-btn light--btn btn--sm " data-style="bold"><span class="fw-bold p-0 i-btn light--btn btn--sm me-2">{{ translate("Bold") }}</span><i class="fa-solid fa-bold"></i></a>
                                            <a title="{{ translate("Italic") }}" href="#" class="style-link i-btn light--btn btn--sm" data-style="italic"><span class="fst-italic p-0 i-btn light--btn btn--sm me-2">{{ translate("Italic") }}</span><i class="fa-solid fa-italic"></i></a>
                                            <a title="{{ translate("Mono Space") }}" href="#" class="style-link i-btn light--btn btn--sm" data-style="mono"><span class="font-monospace p-0 i-btn light--btn btn--sm me-2">{{ translate("Mono Space") }}</span><i class="fa-solid fa-arrows-left-right-to-line"></i></a>
                                            <a title="{{ translate("Strike") }}" href="#" class="style-link i-btn light--btn btn--sm" data-style="strike"><span class="text-decoration-line-through p-0 i-btn light--btn btn--sm me-2">{{ translate("Strike") }}</span><i class="fa-solid fa-strikethrough"></i></a>
                                            <a href="javascript:void(0)" class="i-btn info--btn btn--sm ms-auto" data-bs-toggle="modal" data-bs-target="#templatedata">{{ translate('Use Template')}} </a>
                                        </div>
                                        <div class="custom--editor">
                                            <div class="speech-to-text" id="messageBox">
                                                <textarea class="form-control message" name="message" id="message" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nTo initiate text spinner type {Hello|Hi} to you, {Mr.|Mrs.|Ms.} {{Lucia|Sara}|Williams|David}"@endphp" aria-describedby="text-to-speech-icon">{{session()->get('old_sms_message')}}</textarea>
                                                <span class="voice-icon" id="text-to-speech-icon">
                                                    <i class='fa fa-microphone text-to-speech-toggle'></i>
                                                </span>
                                            </div>
                                            <div id="add_media" class="test"></div>
                                        </div>
                                        
                                        <div class="mt-4 d-flex align-items-center justify-content-md-between justify-content-start flex-wrap gap-3">
                                            <div class="text-end message--word-count"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <label for="schedule_date" class="form-label">{{ translate('Schedule Date')}}</label>
                                        <input type="datetime-local" name="schedule_date" id="schedule_date" class="form-control schedule-date">
                                    </div>
                                </div>

                                <div class="col-md-6 schedule"></div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="i-btn primary--btn btn--lg whatsapp-submit">
                                {{translate("Submit")}}
                            </button>
                        </div>
                    </div>

                    <div class="note-container col-xl-3 order-xl-2 order-1 d-xl-block d-none">
                        <div class="position-relative h-100">
                            <div class="note">
                                <h6>{{translate('Suggestions Note')}}</h6>
                                <div class="note-body">
                                    <p class="single-audience-note note-message">{{translate("By selecting the 'Single Audience' input field, you can enter a valid phone number with a country code (For Example: $general->country_code xxxxxxxxxx). In order to send or schedule an SMS, continue filling up the rest of the form. ")}}</p>
                                    <p class="d-none group-audience-note note-message">{{translate("By selecting the 'Group Audience' input field, You can choose your personal Text Phonebook group to send or schedule messages to all of the group's contacts.")}}</p>
                                    <p class="d-none import-file-note note-message">{{translate("By selecting the 'Import File' input field, You can upload your local .csv or .xlsv files from your machine and send or schedule messages to those contacts")}}</p>
                                    <p class="d-none schedule-date-note note-message">{{translate("By selecting the 'Schedule Date' input field, You can pick date and type to send a message according to that schedule")}}</p>
                                    <p class="d-none message-note note-message">{{translate("You can either type your message or click the 'mic' icon to use the text to speech feature. By using the ")}}@php echo "{{". 'name' ."}}"  @endphp {{ translate(" variable you can mention the name for that contact. But with 'Single Audience' selected only their number will pass by that variable.") }}</p>
                                    <p class="d-none message-type-note note-message">{{translate("If you select the 'Text' option $general->sms_word_text_count characters will be allocated for a single SMS. And if you select 'unicode' then $general->sms_word_unicode_count characters will be allocated for each SMS.")}}</p>
                                    <p class="d-none message-media-note note-message">{{translate("You can select from four different media types (Document, Image, Audio, Video) and attach them with your whatsapp messsage. Press the Choose File Button under 'Upload File' to upload your file.")}}</p>
                                </div>
                            </div>
                            <div class="whatsapp-message-preview">
                                <div class="form-wrapper mb-0">
                                    <h6 class="message-header mb-2"></h6>
                                    <div>
                                        <p class="message-body mb-2"></p>
                                        <p class="message-footer fw-light"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<div class="modal fade" id="templatedata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header bg--lite--violet">
                        <div class="card-title text-center text--light">{{ translate('SMS Template')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="template" class="form-label">{{ translate('Select Template')}} <sup class="text--danger">*</sup></label>
                            <select class="form-control" name="template" id="template" required>
                                <option value="" disabled="" selected="">{{ translate('Select One')}}</option>
                                @foreach($templates as $template)
                                    <option value="{{$template->message}}">{{$template->name}}</option>
                                @endforeach
                            </select>
                        </div>
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
		$('.keywords').select2({
			tags: true,
			tokenSeparators: [',']
		});

        //Contact File Input Details
        $("#file").change(function() {

            var contact_file = this.files[0];
            var file_name = "{{ translate('Selected: ') }}<p class='badge badge--primary'>"+ contact_file.name +"</p>";
            $("#contact_file_name").html(file_name);
        })

		$('.whatsapp-submit').on('click', function(){

                 if($('input[type=datetime-local][name=schedule_date]').val()){
                     const html = `
                     <input hidden type="number" value ="2" name="schedule" id="schedule" class="form-control">`;
                     $('.schedule').append(html);
                 }else{
                     const html = `
                     <input hidden type="number" value ="1" name="schedule" id="schedule" class="form-control">`;
                     $('.schedule').append(html);
                 }


             });

        var wordLength = {{$general->whatsapp_word_count}};

	    $('select[name=template]').on('change', function(){
	    	var character = $(this).val();
	    	$('textarea[name=message]').val(character);
	    	var credit = {{charactersLeft()}};
            var character = $(this).val();
            var characterleft = credit - character.length;
            var word = character.split(" ");
            var sms = 1;
			if (character.length > wordLength) {
    			sms = Math.ceil(character.length / wordLength);
    		}
            if (character.length > 0) {
                $(".message--word-count").html(`
                	<span class="text--success character">${character.length}</span> {{ translate('Character')}} |
					<span class="text--success word">${word.length}</span> {{ translate('Words')}} |
					<span class="text--danger word">${characterleft}</span>{{ translate('Characters Left')}} |
					<span class="text--success word">${sms}</span> {{ translate('SMS')}} (${wordLength} Char./SMS)`);
            }else{
                $(".message--word-count").empty()
            }
		    $('#templatedata').modal('toggle');
		});

		$(`textarea[name=message]`).on('keyup', function(event) {
		 	var credit = {{charactersLeft()}};
            var character = $(this).val();
            var characterleft = credit - character.length;
            var word = character.split(" ");
            var sms = 1;
			if (character.length > wordLength) {
    			sms = Math.ceil(character.length / wordLength);
    		}
            if (character.length > 0) {
                $(".message--word-count").html(`
                	<span class="text--success character">${character.length}</span> {{ translate('Character')}} |
					<span class="text--success word">${word.length}</span> {{ translate('Words')}} |
					<span class="text--danger word">${characterleft}</span> {{ translate('Characters Left')}} |
					<span class="text--success word">${sms}</span> {{ translate('SMS')}} (${wordLength} Char./SMS)`);
            }else{
                $(".message--word-count").empty()
            }
        });

        var t = window.SpeechRecognition || window.webkitSpeechRecognition,
            e = document.querySelectorAll(".speech-to-text");
	    if (null != t && null != e) {
	        var n = new t;
            var e = !1;
        	$('#text-to-speech-icon').on('click',function () {
				var messageBox = document.getElementById('messageBox');
				messageBox.querySelector(".form-control").focus(), n.onspeechstart = function() {
                    e = !0
                }, !1 === e && n.start(), n.onerror = function() {
                    e = !1
                }, n.onresult = function(e) {
                    messageBox.querySelector(".form-control").value = e.results[0][0].transcript;
                }, n.onspeechend = function() {
                    e = !1, n.stop()
                }
			});
	    }

        function handleMergedAttributes(attributes) {

            var initialAttributeOptions = $(".group-logic-items").html();

            $('#group').on('select2:unselect', function (e) {
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
            notify('info', message);
        }

        $(document).ready(function() {

            $('#group').change(function() {

                var selectedValues = $(this).val();
                var channelValue = $('input[name="channel"]').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (selectedValues) {
                    $.ajax({
                        url: '{{ route("user.contact.group.fetch", ["type" => "contact_attributes"]) }}',
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
        });


        $('.whatsapp_business_api').change(function () {

            $('.whatsapp-cloud-steps .whatsapp-tabs').empty();
            $('.whatsapp-cloud-steps .whatsapp-tab-content').empty();
            $('.whatsapp-cloud-steps').addClass('d-none');
            $(".with-cloud-message").find(".old-template-fields").removeClass('d-none');
            $('.template-fields').empty();

            var cloud_id = $(this).val();

            if(cloud_id == -1) {

                $('.select-cloud-templates').addClass('d-none');
            } else {

                $('.select-cloud-templates').removeClass('d-none');
            }
            $.ajax({
                url : "{{route('user.gateway.whatsapp.cloud.fetch', ['type' => 'whatsapp'])}}", 
                type: 'GET',
                data: {cloud_id: cloud_id},
                success: function (response) {

                    $('#whatsapp_template_id').empty(); 
                    
                    if (response.templates.length > 0) {

                        $('#whatsapp_template_id').append('<option value="" selected disabled>Select A Template</option>');
                        $.each(response.templates, function (index, template) {
                            
                            $('#whatsapp_template_id').append('<option value="' + template.id + '">' + template.name + '('+ template.template_data.language+')'+ '</option>');
                            allTemplates = response.templates;
                        });
                    } else {

                        $('#whatsapp_template_id').append('<option value="" selected disabled>No templates available</option>');
                        allTemplates = [];
                    }
                },
                error: function (xhr, status, error) {

                    console.error(xhr.responseText);
                }
            });
        });


	})(jQuery);
</script>
@endpush

