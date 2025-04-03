@extends('user.layouts.app')
@section('panel')
<section>
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">{{translate('Send Email')}}</h6>
			<div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Suggestions Note">
				<button class="i-btn info--btn btn--sm d-xl-none info-note-btn"><i class="las la-info-circle"></i></button>
			</div>
		</div>

		<div class="card-body position-relative">
			<form action="{{route('user.manage.email.store')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="text" name="channel" value="email" hidden>
				<div class="row g-4">
					<div class="col-xl-9 order-xl-1 order-2">
						<div class="form-wrapper">
							<h6 class="form-wrapper-title">{{translate('Set Target Audience')}}</h6>

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
											<label for="email" class="form-label">{{ translate('Single Input') }}</label>
											<select class="form-control email-collect" name="email[]" id="email" multiple></select>
											<div class="form-text">{{ translate('Put single or search from save contact')}}</div>
										</div>
									</div>

									<div class="tab-pane fade" id="group-tab-pane" role="tabpanel" aria-labelledby="group-tab" tabindex="0">
										<div class="form-item">
											<label for="group_id" class="form-label">{{ translate('From Group')}}</label>

											<select class="form-select keywords" name="group_id[]" id="group_id" multiple="multiple">
												<option value="" disabled="">{{ translate('Select One')}}</option>
												@foreach($emailGroups as $group)
													<option @if (old("group_id")){{ (in_array($group->id, old("group_id")) ? "selected":"") }}@endif value="{{$group->id}}">{{$group->name}}</option>
												@endforeach
											</select>

											<div class="form-text">
												{{ translate('You can select single or multiple groups')}}
											</div>
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
												<a class="badge badge--primary" href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $channel])}}"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('csv')}}</a>
												{{-- <a class="badge badge--primary" href="{{route('demo.file.download', ['extension' => 'xlsx' , 'type' => $channel])}}"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('xlsx')}}</a> --}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-wrapper">
							<h6 class="form-wrapper-title">{{translate('Message and Schedule')}}</h6>
							<div class="row g-4">
								<div class="col-12">
									<div class="form-item">
										<label class="form-label" for="subject">{{ translate('Subject')}} <sup class="text-danger">*</sup></label>
										<div class="input-group input-group-merge mail-subject">
											<input type="text" value="{{old("subject")}}" name="subject" id="subject" class="form-control" placeholder="{{ translate('Write email subject here')}}">
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-item">
										<label class="form-label" for="from_name">{{ translate('Send From')}}</label>
										<div class="input-group input-group-merge mail-send-from">
											<input class="form-control" value="{{old("from_name")}}" placeholder="{{ translate('Sender Name (Optional)')}}" type="text" name="from_name" id="from_name">
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-item">
										<label class="form-label" for="reply_to_email">{{ translate('Reply To Email')}}</label>
										<div class="input-group input-group-merge mail-send-email">
											<input class="form-control" value="{{old("reply_to_email")}}" type="email" placeholder="{{ translate('Reply To Email (Optional)')}}" name="reply_to_email" id="reply_to_email">
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-item message">
										<label class="form-label" for="message">{{ translate('Message Body')}} <sup class="text-danger">*</sup></label>
										<div class="input-group">
											<textarea class="form-control message" name="message" id="message" rows="2">{{old("message")}}</textarea>
										</div>

										<div class="d-flex justify-content-end text-end mt-4">
											<a href="javascript:void(0);" id="selectEmailTemplate" class="i-btn info--btn btn--sm">{{translate('Use Email Template')}}</a>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-item">
										<label for="schedule_date" class="form-label">{{ translate("Schedule Date") }}</label>
										<input type="datetime-local" value= "{{old("schedule_date")}}" name="schedule_date" id="schedule_date" class="form-control schedule-date">

									</div>
								</div>

								<div class="col schedule"></div>
							</div>
						</div>

						<button type="submit" class="btn btn-primary me-sm-3 me-1 mail-submit">
							{{translate("Submit")}}
						</button>
					</div>

					<div class="note-container col-xl-3 order-xl-2 order-1 d-xl-block d-none">
						<div class="note">
							<h6>{{translate('Suggestions Note')}}</h6>
							<div class="note-body">
								<p class="single-audience-note note-message">{{translate("By selecting the 'Single Audience' input field, you can enter a valid phone number with a country code (For Example: $general->country_code xxxxxxxxxx). In order to send or schedule an SMS, continue filling up the rest of the form. ")}}</p>
								<p class="d-none group-audience-note note-message">{{translate("By selecting the 'Group Audience' input field, You can choose your personal Text Phonebook group to send or schedule messages to all of the group's contacts.")}}</p>
								<p class="d-none import-file-note note-message">{{translate("By selecting the 'Import File' input field, You can upload your local .csv or .xlsv files from your machine and send or schedule messages to those contacts")}}</p>
								<p class="d-none schedule-date-note note-message">{{translate("By selecting the 'Schedule Date' input field, You can pick date and type to send a message according to that schedule")}}</p>
								<p class="d-none message-note note-message">{{translate("You can type your mail body here. You can use our customized text editor to make sure your mail catches the attention of your clients. Or you can bring your own custom message and paste it right here with all the designs and custom data.")}}</p>
								<p class="d-none mail-subject-note note-message">{{translate("In this field you can add your desired E-mail subject")}}</p>
								<p class="d-none mail-send-from-note note-message">{{translate("You can add a customized name which will show up as 'send from name' in the receivers inbox")}}</p>
								<p class="d-none mail-send-email-note note-message">{{translate("You can add a customized Email which will show up as 'reply to' in the receivers inbox")}}</p>
								<p class="d-none select-mail-gateway-note note-message">{{translate("By this option you'll be able to select a specific gateway by which this Email will be sent. If none are selected then the default gateway which you have selected within your gateway list will be used to send this Email.")}}</p>
							</div>
						</div>
						@php
							$jsonArray = json_encode($credentials);
							$plan_access = $allowed_access->type == App\Enums\StatusEnum::FALSE->status();   
						@endphp
						<div class="form-wrapper select-mail-gateway mt-3">
							<h6 class="form-wrapper-title" title="{{ translate('If left unselected then the default gateway will be selected') }}">{{ $plan_access ? translate("Select User's Email Gateway") : translate("Select Admin's Email Gateway")}}</h6>
							<div class="mail-gateway">
								<label for="repeat-time" class="form-label">{{translate('Mail Gateway Type')}}</label>
								@if($plan_access)
									<select class="form-select repeat-scale mail_gateway_type" name="gateway_type" id="gateway_type">
										<option selected value="">{{ translate('-- Choose One --') }}</option>
										@foreach($credentials as $credential_key => $credential)
											@foreach($user->runningSubscription()->currentPlan()->email->allowed_gateways as $key => $value)
												@if(preg_replace('/_/','',$key) == strtolower($credential_key))
													<option value="{{strToLower($key)}}">{{strtoupper($key)}}</option>
												@endif
											@endforeach
										@endforeach
									</select>
								@else 
									<select class="form-select mail_gateway_type" name="gateway_type" id="gateway_type">
										<option selected value="">{{ translate('-- Choose One --') }}</option>
										@foreach($credentials as $key=>$credential)
											<option value="{{strToLower($key)}}">{{strtoupper($key)}}</option>
										@endforeach
									</select>
								@endif
							</div>
							<div class="mail-gateway mail-gateways mt-4 d-none">
								<label for="gatewwayId" class="form-label">{{translate('Mail Gateway')}} <sup class="text-danger">*</sup></label>
								<select class="form-control gateway-collect" name="gateway_id" id="gatewwayId"></select>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>


<div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal-body" class="modal-body">

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

		const modal = $('#globalModal');

		selectSearch("{{route('email.select2')}}")
		function selectSearch(route){
			$(`.email-collect`).select2({
            allowClear: false,
            tags: true,
            tokenSeparators: [' '],
            placeholder: '',
            ajax: {
                url: route,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
          });
		}

		//Contact File Input Details
		$("#file").change(function() {

			var contact_file = this.files[0];
			var file_name = "{{ translate('Selected: ') }}<p class='badge badge--primary'>"+ contact_file.name +"</p>";
			$("#contact_file_name").html(file_name);
		})
		
		$('.mail_gateway_type').change(function () {

			var selectedType = $(this).val();
			$('.mail-gateways').removeClass('d-none');
			$('.mail-gateways').addClass('d-block');

			if(selectedType == ''){
				$('.mail-gateways').addClass('d-none');
			}
			$.ajax({
				type: 'GET',
				url: "{{route('user.gateway.select2', 'email')}}",
				data:{
					'type' : selectedType,
				},
				dataType: 'json',
				success: function (data) {
					
				
					$('.gateway-collect').empty();

					$.each(data, function (key, value) {
						var select   = $('<option value="' + value.id + '">' + value.name + ' ('+value.address+')</option>');
						$('.gateway-collect').append(select);
					});
				},
				error: function (xhr, status, error) {
					console.log(error);
				}
			});
		});

		$(document).on('click','#use-template',function(e){
			var html  = $(this).attr('data-html')
			const domElement = document.querySelector( '.ck-editor__editable' );
			const emailEditorInstance = domElement.ckeditorInstance;
			emailEditorInstance.setData( html );
			modal.modal('hide');
        })



		$('.mail-submit').on('click', function(){

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



		$(document).on('click','#selectEmailTemplate',function(e){
			$("#selectEmailTemplate").html('{{translate("Template Loading...")}}');
			appendTemplate()
			e.preventDefault()
        })

		//load pre-build template method start
		function  appendTemplate(){
			$.ajax({
				method:"GET",
				url:"{{ route('user.template.email.list') }}",
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





	    $(document).ready(function() {
		    CKEDITOR.ClassicEditor.create(document.getElementById("message"), {
		        placeholder: document.getElementById("message").getAttribute("placeholder"),
		        toolbar: {
		          items: [
		            'heading',
		            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
		            'alignment', '|',
		            'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', 'removeFormat', 'findAndReplace', '-',
		            'bulletedList', 'numberedList', '|',
		            'outdent', 'indent', '|',
		            'undo', 'redo',
		            'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
		            'horizontalLine', 'pageBreak', '|',
		            'sourceEditing'
		          ],
		          shouldNotGroupWhenFull: true
		        },
		        list: {
		          properties: {
		            styles: true,
		            startIndex: true,
		            reversed: true
		          }
		        },
		        heading: {
		          options: [
		            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
		            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
		            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
		            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
		            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
		            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
		            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
		          ]
		        },
		        fontFamily: {
		          options: [
		            'default',
		            'Arial, Helvetica, sans-serif',
		            'Courier New, Courier, monospace',
		            'Georgia, serif',
		            'Lucida Sans Unicode, Lucida Grande, sans-serif',
		            'Tahoma, Geneva, sans-serif',
		            'Times New Roman, Times, serif',
		            'Trebuchet MS, Helvetica, sans-serif',
		            'Verdana, Geneva, sans-serif'
		          ],
		          supportAllValues: true
		        },
		        fontSize: {
		          options: [10, 12, 14, 'default', 18, 20, 22],
		          supportAllValues: true
		        },
		        htmlSupport: {
		          allow: [
		            {
		              name: /.*/,
		              attributes: true,
		              classes: true,
		              styles: true
		            }
		          ]
		        },
		        htmlEmbed: {
		          showPreviews: true
		        },
		        link: {
		          decorators: {
		            addTargetToExternalLinks: true,
		            defaultProtocol: 'https://',
		            toggleDownloadable: {
		              mode: 'manual',
		              label: 'Downloadable',
		              attributes: {
		                download: 'file'
		              }
		            }
		          }
		        },
		        mention: {
		          feeds: [
		            {
		              marker: '@',
		              feed: [
		                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
		                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
		                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
		                '@sugar', '@sweet', '@topping', '@wafer'
		              ],
		              minimumCharacters: 1
		            }
		          ]
		        },
		        removePlugins: [
		          'CKBox',
		          'CKFinder',
		          'EasyImage',
		          'RealTimeCollaborativeComments',
		          'RealTimeCollaborativeTrackChanges',
		          'RealTimeCollaborativeRevisionHistory',
		          'PresenceList',
		          'Comments',
		          'TrackChanges',
		          'TrackChangesData',
		          'RevisionHistory',
		          'Pagination',
		          'WProofreader',
		          'MathType'
		        ]
		      });
	    });


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
			notify('info', message);
		}

		$(document).ready(function() {

			$('#group_id').change(function() {

				var selectedValues = $(this).val();
				var channelValue = $('input[name="channel"]').val();
				var csrfToken = $('meta[name="csrf-token"]').attr('content');

				if (selectedValues) {
					$.ajax({
						url: '{{ route("user.contact.group.fetch", ["type" => "contact_attributes"]) }}',
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
		
	})(jQuery);
</script>
@endpush

