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
            <div class="card-body pt-0">
                <form action="{{ route("admin.template.save") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="id" value="{{ $template->id }}" hidden>
                    <input type="text" name="type" value="{{ \App\Enums\ServiceType::EMAIL->value }}" hidden>
                    <div class="form-element">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Template Basic Information") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="admin_email_template_name" class="form-label"> {{ translate("Template Name") }} <small class="text-danger">*</small></label>
                                            <div class="input-group">
                                                <input type="text" required name="name" id="admin_email_template_name" class="form-control" placeholder="{{ translate('Write Template Name')}}" value="{{ $template->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="admin_email_template_provider" class="form-label">{{ translate("Select Provider") }}</label>
                                            <select data-placeholder="{{ translate('Select A Provider') }}" class="form-select select2-search" data-show="5" id="admin_email_template_provider" name="provider">
                                                <option value=""></option>
                                                @php $available_providers = \App\Enums\TemplateProvider::toArray() @endphp
                                                @foreach(array_slice($available_providers, 1, null, true) as $provider_key => $provider_value)
                                                    @if($template->provider == \App\Enums\TemplateProvider::CK_EDITOR->value && $provider_value != \App\Enums\TemplateProvider::BEE_FREE->value)
                                                        <option {{ $template->provider == $provider_value ? 'selected' : ''}} value="{{ $provider_value }}">{{ textFormat(['_'], $provider_key, ' ') }}</option>
                                                    @endif
                                                    @if($template->provider == \App\Enums\TemplateProvider::BEE_FREE->value)
                                                        <option {{ $template->provider == $provider_value ? 'selected' : ''}} value="{{ $provider_value }}">{{ textFormat(['_'], $provider_key, ' ') }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-none bee-free">
                                        <div class="form-inner">
                                            <label for="choose-template" class="form-label">{{ translate("Select A Different Template") }}</label>
                                            <select aria-label="{{translate('Select Template')}}" class="form-control"  id="choose-template">
                                                <option value="">-- {{ translate('Select Template') }}</option>
                                                @foreach($plugin_templates as $plugin_template)
                                                    <option value="{{ $plugin_template->id }}">
                                                       {{ $plugin_template->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="bee_template_json" name="template_json">
                    <input type="hidden" id="bee_template_html" name="template_html">
                    <div class="form-element d-none" id="text-editor">
                        <div class="row gy-4">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Template Body") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-inner">
                                          <label for="admin_email_template_body" class="form-label"> {{ translate("Mail Body") }} </label>
                                          <textarea class="form-control" name="template_data[mail_body]" id="admin_email_template_body" rows="2" placeholder="{{ translate('Enter mail body') }}" aria-label="{{ translate('Enter mail body') }}">{{ $template->template_data["mail_body"] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($template->provider == \App\Enums\TemplateProvider::BEE_FREE->value)
                        <div class="bee-plugin-preview mt-5">
                            <div id="bee-plugin-container" class="h-100">
                            </div>

                            <div class="mt-3 d-flex align-items-center">
                                <div id="html-image-data">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-xxl-10">
                            {{-- @if($template->provider == \App\Enums\TemplateProvider::BEE_FREE->value )
                                <div class="form-action justify-content-end">
                                    <span class="" id="preview">
                                        <button type="button" id="edit-template" class="i-btn btn--info btn--md">{{ translate('Edit template') }}</button>
                                    </span>
                                </div>
                            @endif --}}
                            <div class="form-action justify-content-end">
                                <button type="submit" id="save-button" class="i-btn btn--primary btn--md {{ $template->provider == \App\Enums\TemplateProvider::BEE_FREE->value ? 'd-none' : '' }} "> {{ translate("Save") }} </button>
                            </div>
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script-include')
    <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/template.js') }}"></script>
    <script src="{{asset('assets/theme/global/js/BeePlugin.js') }}"></script>
@endpush


@push('script-push')
<script>
	(function($){
		"use strict";

        select2_search($('.select2-search').data('placeholder'));
        ck_editor("#admin_email_template_body");

        if("{{ $template->provider }}" == "{{ \App\Enums\TemplateProvider::BEE_FREE->value }}") {

            $('.bee-free').removeClass('d-none')
            $('#preview').removeClass('d-none')
            $('#text-editor').addClass('d-none')
            loadTemplate({{ $template->id }})
        }

        if("{{ $template->provider }}" == "{{ \App\Enums\TemplateProvider::CK_EDITOR->value }}") {

            $('.bee-free').addClass('d-none')
            $('#preview').addClass('d-none')
            $('.save-button').removeClass('d-none')
            $('#text-editor').removeClass('d-none')
        }
        $(document).on('change','#admin_email_template_provider',function(e){
            if($(this).val() == 1){
                $('.bee-free').removeClass('d-none')
                $('#preview').removeClass('d-none')
                $('#text-editor').addClass('d-none')
                $('.bee-plugin-preview').removeClass('d-none')
                loadTemplate({{ $template->id }})
            }
            else if($(this).val() == 2){
                $('.bee-free').addClass('d-none')
                $('#preview').addClass('d-none')
                $('.save-button').removeClass('d-none')
                $('#text-editor').removeClass('d-none')
                $('#save-button').removeClass('d-none')
                $('.bee-plugin-preview').addClass('d-none')
            }
        });

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
                    $(".bee-plugin-preview").addClass('d-none');
                    $("#save-button").removeClass('d-none');
                },
                onAutoSave: function (jsonFile, htmlFile) {
                    $("#bee_template_json").val(jsonFile);
                    $("#bee_template_html").val(htmlFile);
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
                        `${baseUrl}/admin/template/email/edit/json/${templateId}`,
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
	})(jQuery);
</script>
@endpush

