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
      
        <form action="{{route('admin.communication.email.campaign.save', ['type' => 'email'])}}" class="step-content" method="POST" enctype="multipart/form-data">
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
                                        <label for="contacts" class="form-label">{{ translate("Choose Group") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Only the contact groups with email contact are available") }}">
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
                            <h5 class="form-element-title">{{ translate("Choose Gateway") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-lg-9">
                            <div class="row gy-3 align-items-end">
                              <div class="col-12">
                                <div class="form-inner">
                                  <label for="gateways" class="form-label">{{ translate("Choose From an available gateway") }}</label>
                                  <select class="form-select select2-search" id="gateway_id" data-placeholder="{{ translate("Select a gateway") }}" data-show="5" aria-label="gateway_id" name="gateway_id">
                                    <option value=""></option>
                                    <option value="0">{{ translate("Random Rotation") }}</option>
                                    <option value="-1">{{ translate("Automatic") }}</option>
                                    @foreach($gateways as $type => $gateway)
                                        <optgroup label="{{ ucfirst($type) }}">
                                            @foreach($gateway as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </optgroup>
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
                    <div class="form-element">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Sender Information") }}</h5>
                          </div>
                          <div class="col-xxl-7 col-lg-9">
                           
                            <div class="form-inner mb-3">
                              <label for="email_from_name" class="form-label">{{ translate("Email From Name") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Provide a from name which will displayed as the nameof the source') }}">
                                  <i class="ri-question-line"></i>
                                  </span>
                              </label>
                              <input type="text" class="form-control" name="email_from_name" id="email_from_name" placeholder="{{ translate("Enter email from name") }}" aria-label="email_from_name" autocomplete=""/>
                            </div>
                            
                            <div class="form-inner mb-3">
                              <label for="reply_to_address" class="form-label">{{ translate("Reply To Address") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Reply to email address helps recipient to communcate with you') }}">
                                  <i class="ri-question-line"></i>
                                  </span>
                              </label>
                              <input type="email" class="form-control" name="reply_to_address" id="reply_to_address" placeholder="{{ translate("Enter reply to email address") }}" aria-label="reply_to_address" autocomplete=""/>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Message body") }}</h5>
                            </div>
                            <div class="col-xxl-7 col-lg-9">
                            <div class="form-inner mb-3">
                                <label for="subject" class="form-label">{{ translate("Subject") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Provide a subject for the mail so it doesn\'t go into the spam') }}">
                                    <i class="ri-question-line"></i>
                                    </span>
                                </label>
                                <input type="text" class="form-control" name="message[subject]" id="email_contact" placeholder="{{ translate("Enter email subject") }}" aria-label="subject" autocomplete=""/>
                            </div>
                            <div class="form-inner position-relative speech-to-text" id="messageBox">
                            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 mb-2">
                                <label for="message" class="form-label mb-0">{{ translate("Write message") }}</label>
                                <button class="i-btn btn--sm p-0 bg-transparent text-primary available-template" id="selectEmailTemplate" type="button">
                                <i class="ri-layout-fill fs-5"></i>{{ translate("Use Template") }}</button>
                            </div>
                            <textarea class="form-control" name="message[message_body]" id="message" rows="5" placeholder="@php echo "Enter SMS Content. \nIf Contact is being selected from a group then to mention First Name Use {{". 'first_name' ."}} \nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}\nAttach {{unsubscribe_link}} within your campaign to generate a link for each contacts. This will allow them to unsubscribe from this campaign"@endphp"></textarea>
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
@section('modal')
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
  
<div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal-body">

            </div>
        </div>
    </div>
</div>
@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
  <script src="{{asset('assets/theme/global/js/campaign/sms/stage-step.js')}}"></script>
  <script src="{{asset('assets/theme/global/js/template.js') }}"></script>
  <script src="{{asset('assets/theme/global/js/BeePlugin.js') }}"></script>
@endpush
@push('script-push')
<script>
	"use strict";
    select2_search($('.select2-search').data('placeholder'));
    ck_editor("#message");

    $(document).ready(function() {
        
        const modal = $('#globalModal');
        $(document).on('click','#use-template',function(e){
            var html  = $(this).attr('data-html')
            const domElement = document.querySelector( '.ck-editor__editable' );
            const emailEditorInstance = domElement.ckeditorInstance;
            emailEditorInstance.setData( html );
            modal.modal('hide');
        })

        $(document).on('click','#selectEmailTemplate',function(e){
            
            $("#selectEmailTemplate").html('{{translate("Template Loading...")}}');
            appendTemplate()
            e.preventDefault()
        })

        function  appendTemplate(){
			$.ajax({
				method:"GET",
				url:"{{ route('admin.template.email.fetch') }}",
				dataType:'json'
			}).then(response=>{
				$("#selectEmailTemplate").html('{{translate("Use Email Template")}}');
				appendModalData(response.view)
			})
        }

		   // append modal data method start
		function appendModalData(view){
			$('#modal-title').html(`{{translate('Pre Build Template')}}`)
			var html = `
				<div class="modal-body">
				   ${view}
				</div>
			`
			$('#modal-body').html(html)
			modal.modal('show');
		}
      
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
                        channel: "email",
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
    });
</script>
@endpush
