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
                                                <input type="text" required  value="{{old("name")}}" name="name" id="admin_email_template_name" class="form-control" placeholder="{{ translate('Write Template Name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="admin_email_template_provider" class="form-label">{{ translate("Select Provider") }}</label>
                                            @php $available_providers = \App\Enums\TemplateProvider::toArray() @endphp
                                            <select data-placeholder="{{ translate('Select A Provider') }}" class="form-select select2-search" data-show="5" id="admin_email_template_provider" name="provider">
                                                <option value=""></option>
                                                
                                                @foreach(array_slice($available_providers, 1, null, true) as $provider_key => $provider_value)
                                                    <option value="{{ $provider_value }}">{{ textFormat(['_'], $provider_key, ' ') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 d-none bee-free">
                                        <div class="form-inner">
                                            <label for="choose-template" class="form-label">{{ translate("Select Template") }}</label>
                                            <select aria-label="{{translate('Select Template')}}" class="form-control"  id="choose-template">
                                                <option value="">-- {{ translate('Select Template') }}</option>
                                                @foreach($plugin_templates as $template)
                                                    <option value="{{ $template->id }}">
                                                       {{ $template->name }}
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
                                          <textarea class="form-control" name="template_data[mail_body]" id="admin_email_template_body" rows="2" placeholder="{{ translate('Enter mail body') }}" aria-label="{{ translate('Enter mail body') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                                <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@section('modal')
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
        const modal = $('#globalModal');

        $(document).on('change','#admin_email_template_provider',function(e){
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
            appendModalData(`<div id="bee-plugin-container" class="h-100">

                    </div>`);
        });

        // append modal data method start
        function appendModalData(view){

            $('#modal-title').html(`{{translate('Create New Template')}}`)
            $('#modal-size').addClass('modal-fullscreen')

            var html = `${view}`
            $('#modal-body').html(html)
            $('#globalModal').modal('show');
        }
	})(jQuery);
</script>
@endpush

