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
                    <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                        @csrf
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Configuration") }}</h5>
                                    </div>
                                    <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="callback_url" class="form-label"> {{ translate("Callback URL") }} <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                    <input disabled type="text" id="callback_url" class="form-control" value="{{route('webhook')}}"/>
                                                    <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-inner">
                                                <label for="webhook_verify_token" class="form-label "> {{ translate("Verify Token") }} <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                <input type="text" id="webhook_verify_token" class="form-control verify_token" value="{{ site_settings("webhook_verify_token") }}" name="site_settings[webhook_verify_token]"/>
                                                <span id="reset-primary-color" class="input-group-text generate-token pointer"> <i class="ri-restart-line"></i> </span>
                                                <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xxl-10">
                                <div class="form-action justify-content-end">
                                    <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
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

@push("script-push")
  <script>
    "use strict";
    $(document).ready(function() {

        $('.copy-text').click(function() {

            var message = "Text copied!";
            copy_text($(this), message);
        });
    });
  </script>
@endpush