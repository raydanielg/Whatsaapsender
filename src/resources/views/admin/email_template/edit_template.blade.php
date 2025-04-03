@extends('admin.layouts.app')
@section('panel')
<section class="mt-3 rounded_box">
    @php
        $beePlugIn = json_decode($general->bee_plugin,true);
    @endphp
	<div class="container-fluid p-0 pb-2">
		<div class="row d-flex align--center rounded">
			<div class="col-xl-12">
				<div class="col-xl">
					<form action="{{route('admin.template.email.update')}}" method="POST" enctype="multipart/form-data">
						@csrf

                        <input type="hidden" value="{{$template->id}}" name="id" id="">
                        <input type="hidden" value="{{$template->provider}}" name="provider" id="">

					    <div class="card mb-3">
						    <h6 class="card-header">{{ translate('Template Basic Information')}}</h6>
						    <div class="card-body">
						    	<div class="row">
						    		<div class="col-md-6 mb-2">
					            		<label class="form-label">
					            			{{ translate('Name')}} <sup class="text-danger">*</sup>
					            		</label>
					            		<div class="input-group input-group-merge">
					              			<input type="text" required  value="{{$template->name}}"  name="name" id="name" class="form-control" placeholder="{{ translate('Write Template Name')}}">
					            		</div>
					          		</div>
						    		<div class="col-md-6 mb-2">
					            		<label class="form-label">
					            			{{ translate('Status')}} <sup class="text-danger">*</sup>
					            		</label>
					            		<div class="input-group input-group-merge">

                                            <select name="status" class="form-control" id="">

                                                <option {{$template->status == '1' ? "selected" :"" }} value="1">
                                                    {{translate('Active')}}
                                                </option>
                                                <option  {{$template->status == '2' ? "selected" :"" }} value="2">
                                                    {{translate('Inactive')}}
                                                </option>
                                            </select>
					            		</div>
					          		</div>

						    	</div>
						    </div>
						</div>

                        @if($template->provider == '2' || $template->provider == null)
                            <div class="card mb-3 " id="text-editor">
                                <h6 class="card-header">{{ translate('Template Body')}}</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="form-label">
                                                {{ translate('Message Body')}} <sup class="text-danger">*</sup>
                                            </label>
                                            <div class="input-group">
                                                <textarea  class="form-control" name="body" id="body" rows="2">{{$template->body}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <input type="hidden" id="bee_template_json" name="bee_template_json">
                        <input type="hidden" id="bee_template_html" name="template_html">

                        <div class="row g-2 mt-2">
                            <div class="col-md">
                                <button type="submit" id="save-button"  class=" @if($template->provider == 1) d-none @endif btn btn-primary">
                                    {{translate('Save')}}
                                </button>
                                @if($template->provider == '1' )
                                    <span class="" id="preview">
                                        <button type="button" id="edit-template" class="btn btn-secondary ">{{ translate('Edit template') }}</button>
                                    </span>
                                @endif
                            </div>
                        </div>
				    </form>
				</div>
			</div>
		</div>
	</div>

    @if($template->provider == '1')
        <section class="section bee-plugin">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                    <div class="bee-plugin-preview">
                                        <div class="col-lg-12" id="template-editor">
                                            <div class="users-link-container py-2">
                                                <div class="bee mt-2  text-center">
                                                    <div class="test">
                                                        <div id="bee-plugin-container">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-3 d-flex align-items-center">
                                            <div id="html-image-data">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif
</section>
@endsection

@push('script-include')
    <script src="{{asset('assets/theme/global/js/template.js') }}"></script>
    <script src="{{asset('assets/theme/global/js/BeePlugin.js') }}"></script>
@endpush

@push('script-push')
<script>
	(function($){
		"use strict";

        if("{{ $template->provider }}" ==  1){
            loadTemplate({{ $template->id }})
        }

        function loadTemplate(templateId = null) {
            let baseUrl = $("meta[name=base-url]").attr("content");
            $("#bee-plugin-container").html("");
            $("#preview").hide(200);
            var bee;
            var endpoint = $("meta[name=bee-endpoint]").attr("content");
            var config = {
                uid: "demo_id_1",
                container: "bee-plugin-container",
                onSave: function (jsonFile, htmlFile) {
                    $("#bee_template_json").val(jsonFile);
                    $("#bee_template_html").val(htmlFile);

                    $(".bee-plugin").hide();
                    $("#template-editor").hide();
                    $("#preview").show(200);
                    $("#save-button").removeClass('d-none');
                },
                onAutoSave: function (jsonFile, htmlFile) {

                },
                onSaveAsTemplate: function (jsonFile) {
                    saveAs(
                        new Blob([jsonFile], {
                            type: "text/plain;charset=utf-8",
                        }),
                        "test.json"
                    );
                },
                onSend: function (htmlFile) {

                },
            };
            var payload = {
                client_id:  $("meta[name=bee-client-id]").attr("content"),
                client_secret: $("meta[name=bee-client-secret]").attr("content"),
                grant_type: "password",
            };

            $.post(endpoint, payload).done(function (data) {
                var token = data;
                window.BeePlugin.create(token, config, function (instance) {
                    bee = instance;
                    $.get(
                        `${baseUrl}/admin/email/templates/edit/json/${templateId}`,
                        function (template) {
                            bee.start(template);
                        }
                    );
                });
            });
        }
        //edit a  template
        $(document).on("click", "#edit-template", function () {


            $(".bee-plugin").show(200);
            $("#template-editor").show(200);

            $("#preview-title").hide();
            $("#html-image-data").html("");
        });

        if("{{ $template->provider }}" ==  2){
            $(document).ready(function() {

            CKEDITOR.ClassicEditor.create(document.getElementById("body"), {
                placeholder: document.getElementById("body").getAttribute("placeholder"),
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
	})(jQuery);
</script>
@endpush

