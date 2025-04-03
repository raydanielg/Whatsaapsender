@extends('user.layouts.app')
@section('panel')
<section>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">{{translate('Edit SMS Campaign')}}</h4>
			<div  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Suggestions Note">
				<button class="i-btn info--btn btn--sm d-xl-none info-note-btn"><i class="las la-info-circle"></i></button>
			</div>
		</div>

		<div class="card-body position-relative">
			<form action="{{route('user.campaign.update')}}" method="POST" enctype="multipart/form-data">
			   @csrf
			   <div class="row g-4">
					 <div class="col-xl-9 order-xl-1 order-2">
							<div class="form-wrapper">
								<h6 class="form-wrapper-title">{{$channel}}{{translate('Set Target Audience')}}</h6>

								<input type="hidden" name="channel" value="{{@$channel}}">
								<input type="hidden" name="id" value="{{@$campaign->id}}">

								<div class="row g-4">
									<div class="col-md-6">
										<div class="form-item">
											<label class="form-label" for="name">{{ translate('Name')}}  <sup class="text-danger">*</sup></label>
											<input class="form-control campaign-name" type="text" name="name" id="name" placeholder="{{translate('Enter Name')}}" value="{{$campaign->name}}">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-item">
											<label class="form-label" for="status">{{ translate('Status')}}</label>
											<select class="form-select campaign-status" name="status" id="status">
												<option value="" disabled="">{{ translate('Select One')}}</option>
												<option {{$campaign->status == "Active" ? "selected" :""}} value="Active">{{translate("Active")}}</option>
												<option {{$campaign->status == "DeActive" ? "selected" :""}} value="DeActive">{{translate("DeActive")}}</option>
											</select>

											<div class="form-text">
												{{ translate('Can be select single or multiple group')}}
											</div>
										</div>
									</div>
								</div>

								<div class="file-tab mt-4">
									<ul class="nav nav-tabs mb-3 gap-2" id="myTabContent" role="tablist">
										<li class="nav-item group-audience" role="presentation">
											<button class="nav-link active" id="group-tab" data-bs-toggle="tab" data-bs-target="#group-tab-pane" type="button" role="tab" aria-controls="group-tab-pane" aria-selected="false"><i class="las la-users"></i> {{ translate('Group Audience') }}</button>
										</li>
										<li class="nav-item import-file" role="presentation">
											<button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-tab-pane" type="button" role="tab" aria-controls="file-tab-pane" aria-selected="false"><i class="las la-file-import"></i> {{ translate('Import File') }}</button>
										</li>
									</ul>

									<div class="tab-content" id="myTabContent">
										<div class="tab-pane fade show active" id="group-tab-pane" role="tabpanel" aria-labelledby="group-tab" tabindex="0">
											<div class="form-item">
                                                <label class="form-label" for="group_id">{{ translate('From Group')}}</label>
                                                <select class="form-control keywords" name="group_id[]" id="group_id" multiple="multiple">
                                                    <option value="" disabled="">{{ translate('Select One')}}</option>
                                                    @foreach($groups as $group)
                                                        <option {{ $group->id == $campaign->group_id ? "selected":""  }} value="{{$group->id}}">{{$group->name}}</option>
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
														<span class="upload-browse">{{ translate('Upload File Here ') }}</span>
														</div>
													</label>
												</div>

												<div class="form-text mt-3">
													{{ translate('Download Sample: ')}}
													@if($channel == \App\Models\Campaign::EMAIL)
														<a href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('csv')}}</a>
														{{-- <a href="{{route('demo.file.download', ['extension' => 'xlsx' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('xlsx')}}</a> --}}
													@elseif($channel == \App\Models\Campaign::SMS)
														<a href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('csv')}}</a>
														{{-- <a href="{{route('demo.file.download', ['extension' => 'xlsx' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('xlsx')}}</a> --}}
													@else
														<a href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('csv')}}</a>
														{{-- <a href="{{route('demo.file.download', ['extension' => 'xlsx' , 'type' => $channel])}}" class="badge badge--primary"><i class="fa fa-download" aria-hidden="true"></i> {{ translate('xlsx')}}</a> --}}
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							@php 
                            	$plan_access = $allowed_access->type == App\Enums\StatusEnum::FALSE->status();   
							@endphp
							@if($channel == \App\Models\Campaign::SMS)
								
								@if(auth()->user()->sms_gateway == 1)
									<div class="form-wrapper select-sms-gateway">
										<h6 class="form-wrapper-title" title="{{ translate('If left unselected then the default gateway will be selected') }}">{{ $allowed_access->type == App\Enums\StatusEnum::FALSE->status() ? translate("Select User's SMS Gateway") : translate("Select Admin's SMS Gateway")}}</h6>
										<div class="sms-gateway">
											<label for="repeat-time" class="form-label">{{translate('Sms Gateway Type')}}</label>
											@if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
												<select class="form-select repeat-scale sms_gateway_type" name="gateway_type" id="gateway_type">
													<option selected value="">{{ translate('-- Choose One --') }}</option>
													@foreach($credentials as $credential)
													@foreach($user->runningSubscription()->currentPlan()->sms->allowed_gateways as $key => $value)
															@if(preg_replace('/_/','',$key) == preg_replace('/ /','',strtoupper($credential->name)))
																<option value="{{$credential->gateway_code}}">{{strtoupper($credential->name)}}</option>
															@endif
														@endforeach
													@endforeach
												</select>
											@else
												<select class="form-select repeat-scale sms_gateway_type" name="gateway_type" id="gateway_type">
													<option selected value="">{{ translate('-- Choose One --') }}</option>
													@foreach($credentials as $credential)
														<option value="{{$credential->gateway_code}}">{{strtoupper($credential->name)}}</option>
													@endforeach
												</select>
											@endif
										</div>
										<div class="sms-gateway sms-gateways mt-4 d-none">
											<label for="gatewwayId" class="form-label">{{translate('Sms Gateway')}} <sup class="text-danger">*</sup></label>
											<select class="form-control gateway-collect" name="gateway_id" id="gatewwayId"></select>
										</div>
									</div>
								@else
									
									<div class="form-wrapper select-android-gateway">
										<h6 class="form-wrapper-title" title="{{ translate('If left unselected then a random sim will be choosen') }}">{{ translate('Select Android Gateway')}}</h6>
										<div class="android-gateway">
											<label for="android_gateways_id" class="form-label">{{translate('Android Gateway')}} </label>
											<select class="form-select repeat-scale android_gateways" name="android_gateways_id" id="android_gateways_id">
												<option selected value="-1">{{ translate('Automatic') }}</option>
												@foreach($android_gateways as $gateway)
													<option value="{{$gateway->id}}">{{strtoupper($gateway->name)}}</option>
												@endforeach
											</select>
										</div>
										@if($plan_access) 
											<div class="android-sim mt-4 d-none">
												<label for="sim_id" class="form-label">{{translate('Choose A Phone Number')}} <sup class="text-danger">*</sup></label>
												<select class="form-select sim-list" name="sim_id" id="sim_id"></select>
											</div>
										@endif
									</div>
									
								@endif
							@elseif($channel == \App\Models\Campaign::EMAIL)    
								@php
									$jsonArray = json_encode($credentials);
								@endphp
								<div class="form-wrapper select-mail-gateway mt-3">
									<h6 class="form-wrapper-title" title="{{ translate('If left unselected then the default gateway will be selected') }}">{{ $plan_access ? translate("Select User's Email Gateway") : translate("Select Admin's Email Gateway")}}</h6>
									<div class="mail-gateway">
										<label for="gateway_type" class="form-label">{{translate('Mail Gateway Type')}}</label>
										@if($plan_access)
											<select class="form-select mail_gateway_type" name="gateway_type" id="gateway_type">
												<option selected value="">{{ translate('-- Choose One --') }}</option>
												@foreach($credentials as $credential_key=>$credential)
												@foreach($user->runningSubscription()->currentPlan()->email->allowed_gateways as $key => $value)
														@if(preg_replace('/_/','',$key) == preg_replace('/ /','',($credential_key)))
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
							@elseif($channel == \App\Models\Campaign::WHATSAPP)
								<div class="form-wrapper select-whatsapp_device mt-3">
									<h6 class="form-wrapper-title">{{ translate('Select Sending method')}}</h6>
									<div class="whatsapp_device">
										<div class="row">
											<div class="col-6">
												<label for="whatsapp_sending_mode" class="form-label">{{translate('Choose Sending Method')}}</label>
												<select class="form-select repeat-scale" name="whatsapp_sending_mode" id="whatsapp_sending_mode">
													<option {{ $allowed_access->type == \App\Models\StatusEnum::FALSE->status() ? "selected" : "disabled"}}  value="cloud_api">{{translate('Cloud API')}} {{ $allowed_access->type == \App\Models\StatusEnum::TRUE->status() ? translate("(Access Denied)") : " "}}</option>
													<option {{ $allowed_access->type == \App\Models\StatusEnum::TRUE->status() ? "selected" : " "}} value="without_cloud_api">{{ translate('Without Cloud API') }}</option>
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

												<select class="form-select repeat-scale whatsapp_business_api d-none" name="whatsapp_device_id" id="whatsapp_device_id">
													<option disabled selected>{{ translate('Select A Business Account') }}</option>
													@foreach($whatsapp_bussiness_api as $gateway)
														<option value="{{$gateway->id}}">{{($gateway->name)}}</option>
													@endforeach
												</select>
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
							@endif
							@if($channel == \App\Models\Campaign::EMAIL)
								<div class="form-wrapper">
									<h6 class="form-wrapper-title"> {{ucfirst($channel)}} {{ translate(' Header Information')}}</h6>

									<div class="row g-4">
										<div class="col-xl-4 col-md-6">
											<div class="form-item">
												<label class="form-label" for="subject">{{ translate('Subject')}} <sup class="text-danger">*</sup></label>
												<input type="text"  value="{{$campaign->subject}}" name="subject" id="subject" class="form-control" placeholder="{{ translate('Write email subject here')}}">
											</div>
										</div>

										<div class="col-xl-4 col-md-6">
											<div class="form-item">
												<label class="form-label" for="from_name">{{ translate('Send From')}}</label>
												<input class="form-control" value="{{$campaign->from_name}}" placeholder="{{ translate('Sender Name (Optional)')}}" type="text" name="from_name" id="from_name">
											</div>
										</div>

										<div class="col-xl-4 col-md-6">
											<div class="form-item">
												<label class="form-label" for="reply_to_email">{{ translate('Reply To Email')}}</label>
												<input class="form-control" value="{{$campaign->reply_to_email}}" type="Email" placeholder="{{ translate('Reply To Email (Optional)')}}" name="reply_to_email" id="reply_to_email">
											</div>
										</div>
									</div>
								</div>
							@endif


							<div class="form-wrapper">
								<h6 class="form-wrapper-title"> {{ucfirst($channel)}} {{ translate(' Body')}}</h6>
								@if($channel == \App\Models\Campaign::EMAIL)
									<div class="form-item">
										<label class="form-label" for="message">{{ translate('Message Body')}} <sup class="text-danger">*</sup></label>
										<textarea class="form-control message" name="message" id="message">{{$campaign->body}}</textarea>
										<div class="d-flex align-items-center justify-content-end mt-4">
											<a href="javascript:void(0);" id="selectEmailTemplate"
												class="i-btn info--btn btn--sm">
											{{translate('Use Email Template')}}
											</a>
										</div>
									</div>
								@else

								<div>
									@if($channel == \App\Models\Campaign::SMS)
										<div class="form-item mb-4">
											<label class="form-label">
												{{ translate('Select SMS Type')}} <sup class="text-danger">*</sup>
											</label>
											<div class="radio-buttons-container message-type">
												<div class="radio-button">
													<input class="radio-button-input" {{$campaign->sms_type == "plain" ?'checked' :"" }}  type="radio" name="smsType" id="smsTypeText" value="plain" checked="">
													<label class="radio-button-label" for="smsTypeText"><span class="radio-button-custom"></span>{{ translate('Text')}}</label>
												</div>

												<div class="form-check form-check-inline">
													<input {{$campaign->sms_type == "unicode" ?'checked' :"" }} class="radio-button-input" type="radio" name="smsType" id="smsTypeUnicode" value="unicode">
													<label class="radio-button-label" for="smsTypeUnicode"><span class="radio-button-custom"></span>{{ translate('Unicode')}}</label>
												</div>
											</div>
										</div>
									@endif

									<div class="form-item">
										<label class="form-label" for="sms-message">{{ translate('Write Message')}} <sup class="text-danger">*</sup></label>
										@if($channel == \App\Models\Campaign::WHATSAPP)

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
															
														
															<div class="custom--editor">
																<div class="speech-to-text" id="messageBox">
																	<textarea readonly class="form-control message" name="message" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}"@endphp" aria-describedby="text-to-speech-icon">{{session()->get('old_sms_message')}}</textarea>
																	<span class="voice-icon" id="text-to-speech-icon">
																		<i class='fa fa-microphone text-to-speech-toggle'></i>
																	</span>
																</div>
																
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-12 without-cloud-message d-none">
													<input type="text" name="without_cloud_api" hidden>
													<div class="form-item">
														
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
																<textarea class="form-control message" name="message" id="message" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nTo initiate text spinner type {Hello|Hi} to you, {Mr.|Mrs.|Ms.} {{Lucia|Sara}|Williams|David}"@endphp" aria-describedby="text-to-speech-icon">{{$campaign->body}}</textarea>
																<span class="voice-icon" id="text-to-speech-icon">
																	<i class='fa fa-microphone text-to-speech-toggle'></i>
																</span>
															</div>
															<div id="add_media" class="test"></div>
														</div>
													</div>
												</div>
												
												
			
												<div class="col-md-6 schedule"></div>
											</div>
                                           
                                        @elseif($channel == \App\Models\Campaign::SMS)
                                            <div class="speech-to-text" id="messageBox">
                                                <textarea class="form-control message" name="message" id="sms-message" placeholder="{{ translate('Enter SMS Content &  For Mention Name Use ')}}@php echo "{{". 'name' ."}}"  @endphp" aria-describedby="text-to-speech-icon">{{$campaign->body}}</textarea>
                                                <span class="voice-icon" id="text-to-speech-icon">
                                                    <i class='fa fa-microphone pointer text-to-speech-toggle'></i>
                                                </span>
                                            </div>
                                        @endif
										

										<div class="mt-4 d-flex align-items-center justify-content-md-between justify-content-start flex-wrap gap-3">
											<div class="text-end message--word-count"></div>
											@if($channel == \App\Models\Campaign::SMS)
												<a href="javascript:void(0)" class="i-btn info--btn btn--sm" data-bs-toggle="modal" data-bs-target="#templatedata">{{ translate('Use Template')}}</a>
											@endif
										</div>
									</div>
								</div>

								@endif
							</div>

							<div class="form-wrapper">
								<h6 class="form-wrapper-title">{{ translate('Sending Options')}}</h6>
								<div class="row g-4">
									<div class="col-xl-4 col-md-6 ">
										<label for="schedule_date" class="form-label">
											{{translate("Schedule Date & Time")}}
											<sup class="text-danger">*</sup></label>
										<input type="datetime-local" value= "{{$campaign->schedule_time}}" name="schedule_date" id="schedule_date" class="form-control schedule-date" required="">
									</div>

									<div class="col-xl-4 col-md-6">
										<label for="repeat" class="form-label">
											{{translate('Repeat Every')}}   <sup class="text-danger">*</sup>
										</label>
										<input type="number" required id="repeatNumber" class="form-control repeat-unit" value="{{$campaign->schedule?->repeat_number}}" name="repeat_number"
											id="repeat">
									</div>

									<div class="col-xl-4 col-md-6">
										<label for="repeat-time" class="form-label">
											{{translate('Repeat in')}}   <sup class="text-danger">*</sup>
										</label>
										<select class="form-select repeat-scale" required name="repeat_format" id="repeat-time">
											<option {{$campaign->schedule?->repeat_format == 'day' ? 'selected' :""}}  value="day">{{ translate('Day') }}</option>
											<option  {{$campaign->schedule?->repeat_format == 'month' ? 'selected' :""}}    value="month">{{ translate('Month') }}</option>
											<option  {{$campaign->schedule?->repeat_format == 'year' ? 'selected' :""}}   value="year">{{ translate('Year') }}</option>
										</select>

									</div>
								</div>
							</div>

							<button type="submit" class="i-btn primary--btn btn--lg">
								{{translate("Update")}}
							</button>
					 </div>


					<div class="note-container col-xl-3 order-xl-2 order-1 d-xl-block d-none">
						<div class="position-relative h-100">
							<div class="note">
								<h6>{{translate('Suggestions Note')}}</h6>
	
								<p class="d-none group-audience-note note-message">{{translate("By selecting the 'Group Audience' input field, You can choose your personal Text Phonebook group to send or schedule messages to all of the group's contacts.")}}</p>
								<p class="d-none import-file-note note-message">{{translate("By selecting the 'Import File' input field, You can upload your local .csv or .xlsv files from your machine and send or schedule messages to those contacts")}}</p>
								<p class="d-none schedule-date-note note-message">{{translate("By selecting the 'Schedule Date' input field, You can pick date and type to send a message according to that schedule")}}</p>
								@if($channel == \App\Models\Campaign::EMAIL)
									<p class="d-none message-note note-message">{{translate("You can type your mail body here. You can use our customized text editor to make sure your mail catches the attention of your clients. Or you can bring your own custom message and paste it right here with all the designs and custom data.")}}</p>
								@else
									<p class="d-none message-note note-message">{{translate("You can either type your message or click the 'mic' icon to use the text to speech feature. By using the ")}}@php echo "{{". 'name' ."}}"  @endphp {{ translate(" variable you can mention the name for that contact. But with 'Single Audience' selected only their number will pass by that variable.") }}</p>
								@endif
								<p class="d-none message-type-note note-message">{{translate("If you select the 'Text' option $general->sms_word_text_count characters will be allocated for a single SMS. And if you select 'unicode' then $general->sms_word_unicode_count characters will be allocated for each SMS.")}}</p>
								<p class="d-none campaign-name-note note-message">{{translate("You can Edit the campaign name with this 'Campaign Name' input field.")}}</p>
								<p class="d-none repeat-unit-note note-message">{{translate("This field allows you to specify the amount of times you want this campaign message to occur in the given duration. In order to run the campaign only ones enter 0.")}}</p>
								<p class="d-none repeat-scale-note note-message">{{translate("This field allows you to specify the duration in days, months or year.")}}</p>
								<p class="d-none mail-subject-note note-message">{{translate("In this field you can add your desired E-mail subject")}}</p>
								<p class="d-none mail-send-from-note note-message">{{translate("You can add a customized name which will show up as 'send from name' in the receivers inbox")}}</p>
								<p class="d-none mail-send-email-note note-message">{{translate("You can add a customized Email which will show up as 'reply to' in the receivers inbox")}}</p>
								<p class="d-none campaign-status-note note-message">{{translate("By changing the status of this campaign, you can either active or deative it.")}}</p>
							</div>
							@if($channel == \App\Models\Campaign::WHATSAPP)
								<div class="whatsapp-message-preview">
									<div class="form-wrapper mb-0">
										<h6 class="message-header"></h6>
										<div>
											<p class="message-body"></p>
											<p class="message-footer fw-light"></p>
										</div>
									</div>
								</div>
							@endif
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

		const modal = $('#globalModal');
		$('.keywords').select2({
			tags: true,
			tokenSeparators: [',']
		});

		$(document).on('click','#use-template',function(e){
			var html  = $(this).attr('data-html')
			const domElement = document.querySelector( '.ck-editor__editable' );
			const emailEditorInstance = domElement.ckeditorInstance;
			emailEditorInstance.setData( html );
			modal.modal('hide');
        })

		//Contact File Input Details
		$("#file").change(function() {

			var contact_file = this.files[0];
			var file_name = "{{ translate('Selected: ') }}<p class='badge badge--primary'>"+ contact_file.name +"</p>";
			$("#contact_file_name").html(file_name);
		})

		$('.mail_gateway_type').change(function () {
	
			var selectedType = $(this).val();
			$('.mail-gateway').removeClass('d-none');
			$('.mail-gateway').addClass('d-block');

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

		$('.sms_gateway_type').change(function () {
			
			var selectedType = $(this).val();
			$('.sms-gateways').removeClass('d-none');
			$('.sms-gateways').addClass('d-block');

			if(selectedType == ''){
				$('.sms-gateways').addClass('d-none');
			}
			$.ajax({
				type: 'GET',
				url: "{{route('user.gateway.select2', 'sms')}}",
				data:{
					'type' : selectedType,
				},
				dataType: 'json',
				success: function (data) {  
					
				
					$('.gateway-collect').empty();

					$.each(data, function (key, value) {
						console.log(value);
					
						var select   = $('<option value="' + value.id + '">' + value.name + '</option>');
						$('.gateway-collect').append(select);
					});
				},
				error: function (xhr, status, error) {
					console.log(error);
				}
			});
		});
        if("{{$channel}}" == "{{\App\Models\Campaign::EMAIL}}"){
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
		}


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

		if("{{$channel}}" == "{{\App\Models\Campaign::SMS}}"){
			var wordLength = {{$general->sms_word_text_count}};
			$('input[type=radio][name=smsType]').on('change', function(){
				if(this.value == "unicode"){
					wordLength = {{$general->sms_word_unicode_count}};
				}else{
					wordLength = {{$general->sms_word_text_count}};
				}
			});

			$(`textarea[name=message]`).on('keyup', function(event) {
				var character = $(this).val();
				var word = character.split(" ");
				var sms = 1;
				if (character.length > wordLength) {
					sms = Math.ceil(character.length / wordLength);
				}
				if (character.length > 0) {
					$(".message--word-count").html(`
						<span class="text--success character">${character.length}</span> {{ translate('Character')}} |
						<span class="text--success word">${word.length}</span> {{ translate('Words')}} |
						<span class="text--success word">${sms}</span> {{ translate('SMS')}} (${wordLength} Char./SMS)`);
				}else{
					$(".message--word-count").empty()
				}
			});
		}


	    $('select[name=template]').on('change', function(){
	    	var character = $(this).val();
	    	$('textarea[name=message]').val(character);
		    $('#templatedata').modal('toggle');
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
                    messageBox.querySelector(".form-control").value = e.results[0][0].transcript
                }, n.onspeechend = function() {
                    e = !1, n.stop()
                }
			});
	    }

	    const inputNumber = document.getElementById('number');
		if(inputNumber){
			inputNumber.addEventListener('keyup', function() {
			const cleanedValue = this.value.replace(/[^\d.-]/g, '');
			this.value = cleanedValue;
		  });
		}




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
			
			url: '{{ route("admin.contact.group.fetch", ["type" => "contact_attributes"]) }}',
			type: 'POST',
			data: {
				group_ids: selectedValues,
				channel: channelValue
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
$('.android_gateways').change(function () {

var selectedType = $(this).val();

if(selectedType != "-1") {
	$('.android-sim').find('.sim-list').prop("selectedIndex", -1);
	$('.android-sim').removeClass('d-none');
	$('.android-sim').addClass('d-block');

	if(selectedType == ''){
		$('.android-sim').addClass('d-none');
	}
	$.ajax({
		type: 'GET',
		url: "{{route('user.gateway.select2', 'android')}}",
		data:{
			'type' : selectedType,
		},
		dataType: 'json',
		success: function (data) {

			$('.sim-list').empty();

			$.each(data, function (key, value) {
				var select   = $('<option value="' + value.id + '">' + value.sim_number + '</option>');
				$('.sim-list').append(select);
			});
		},
		error: function (xhr, status, error) {

			console.log(error);
		}
	});

} else {

	$('.android-sim').find('sim-list').prop("selectedIndex", -1);
	$('.android-sim').addClass('d-none');
}

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
				url : "{{route('admin.template.fetch', ['type' => 'whatsapp'])}}", 
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

