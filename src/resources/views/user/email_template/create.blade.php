@extends('user.layouts.app')
@section('panel')
<section class="mt-3 rounded_box">
    @php
        $beePlugIn = json_decode($general->bee_plugin,true);
    @endphp
	<div class="container-fluid p-0 pb-2">
		<div class="row d-flex align--center rounded">
			<div class="col-xl-12">
				<div class="col-xl">
					<form action="{{route('user.template.email.store')}}" method="POST" enctype="multipart/form-data">
						@csrf
					    <div class="card mb-3">
						    <h6 class="card-header">{{ translate('Template Basic Information')}}</h6>
						    <div class="card-body">
						    	<div class="row">
						    		<div class="col-md-6 mb-2">
					            		<label class="form-label" for="name">{{ translate('Name')}} <sup class="text-danger">*</sup></label>
					            		<div class="input-group input-group-merge">
					              			<input type="text" required  value="{{old("name")}}" name="name" id="name" class="form-control" placeholder="{{ translate('Write Template Name')}}">
					            		</div>
					          		</div>

									<div class="col-md-6 mb-2">
										<label class="form-label" for="provider">{{ translate('Select Provider')}} <sup class="text-danger">*</sup></label>
										<div class="input-group input-group-merge">
                                          <select class="form-control" name="provider" id="provider">
                                              <option value="">{{translate('Select A Provider')}}</option>
                                              @if($beePlugIn['status'] == '1')
                                                  <option value="1">{{translate('Bee Pro')}}</option>
                                              @endif
                                              <option value="2">{{translate('Texteditor')}}</option>
                                          </select>
										</div>
									</div>

                                    <div class="col-md-12 mt-2 d-none bee-free">
                                        <select aria-label="{{translate('Select Template')}}"  name="template" class="form-control"  id="choose-template">
                                            <option value="">-- {{ translate('Select Template') }}</option>
                                            @foreach($beeTemplates as $template)
                                                <option value="{{ $template->id }}">
                                                    {{ translate('Template') }}-{{ $template->id }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
						    	</div>
						    </div>
						</div>

					    <div class="card mb-3 d-none" id="text-editor">
						    <h6 class="card-header">{{ translate('Template Body')}}</h6>
						    <div class="card-body">
				          		<div class="row">
					          		<div class="col-12">
					            		<label class="form-label" for="body">
					            			{{ translate('Message Body')}} <sup class="text-danger">*</sup>
					            		</label>
					            		<div class="input-group">
					            			<textarea  class="form-control" name="body" id="body" rows="2"> {{old("body")}}</textarea>
					            		</div>
					          		</div>
				          		</div>
					      	</div>
					    </div>

                        <input type="hidden" id="bee_template_json" name="bee_template_json">
                        <input type="hidden" id="bee_template_html" name="template_html">

                        <div class="row g-2 mt-2">
                            <div class="col-md">
                                <button type="submit" id="save-button"  class=" btn btn-primary">
                                    {{translate('Save')}}
                                </button>
                                <span class="d-none" id="preview">
                                    <button type="button" id="edit-template" class="btn btn-secondary ">{{ translate('Edit template') }}</button>
                                </span>
                            </div>
                        </div>
				    </form>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="modal-size" class="modal-dialog modal-md">
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
        const modal = $('#globalModal');
        $(document).on('change','#provider',function(e){
            if($(this).val() == 1){
                $('.bee-free').removeClass('d-none')
                $('#preview').removeClass('d-none')
                $('#text-editor').addClass('d-none')
            }
            else if($(this).val() == 2){
                $('.bee-free').addClass('d-none')
                $('#preview').addClass('d-none')
                $('#save-button').removeClass('d-none')
                $('#text-editor').removeClass('d-none')
            }
        });

        $(document).on('change','#choose-template',function(e){
                appendModalData(`<div class="bee mt-2  text-center">
                    <div class="test">
                        <div id="bee-plugin-container">

                        </div>
                    </div>
                </div>`);
            });

            // append modal data method start
            function appendModalData(view){

                $('#modal-title').html(`{{translate('Create New Template')}}`)

                $('#modal-size').addClass('modal-fullscreen')


                var html = `
                    <div class="modal-body">
                        ${view}
                    </div>
                `
                $('#modal-body').html(html)
                $('#globalModal').modal('show');
            }




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


	})(jQuery);
</script>
@endpush

