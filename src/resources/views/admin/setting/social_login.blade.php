@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ textFormat(['_'], $title, ' ') }}</h2>
                <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ textFormat(['_'], $title, ' ') }} </li>
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
                                <h5 class="form-element-title">{{ translate("Social Login") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-inner parent">
                                            <label class="form-label"> {{ translate("Allow Social Login") }} </label>
                                            <div class="form-inner-switch">
                                                <label class="pointer" for="social_login">{{ translate("Turn on/off social login for member") }}</label>
                                                <div class="switch-wrapper mb-1 checkbox-data">
                                                    <input {{ site_settings("social_login") == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="social_login" name="site_settings[social_login]"/>
                                                    <label for="social_login" class="toggle">
                                                    <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="form-element-note text-danger">{{ translate("Enables/disables social login for members") }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach( json_decode(site_settings("social_login_with"), true) as $oauth => $oauth_credentials) 
                        <div class="form-element child d-none">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate(textFormat(['_'], $oauth, ' ')) }}</h5>
                                    </div>
                                    <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        @foreach( $oauth_credentials as $oauth_key => $oauth_value) 
                                            <div class=" {{ $loop->first ? 'col-med-12' : 'col-md-6' }}">
                                                @if($oauth_key == 'status')

                                                    <div class="form-inner child">
                                                        <label class="form-label"> {{ translate(textFormat(['_'], $oauth, ' ')) }} </label>
                                                        <div class="form-inner-switch">
                                                            <label class="pointer" for="{{ $oauth.'_'.$oauth_key }}">{{ translate("Turn on/off ").translate(textFormat(['_'], $oauth, ' ')) }}</label>
                                                            
                                                            <div class="switch-wrapper mb-1 checkbox-data">
                                                                <input {{ $oauth_value == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }} type="checkbox" class="switch-input" id="{{ $oauth.'_'.$oauth_key }}" name="site_settings[social_login_with][{{ $oauth }}][{{ $oauth_key }}]"/>
                                                                <label for="{{$oauth.'_'.$oauth_key }}" class="toggle">
                                                                <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @else
                                                    <div class="form-inner child">
                                                        <label for="{{ $oauth.'_'.$oauth_key }}" class="form-label"> {{ translate(textFormat(['_'], $oauth_key, ' ')) }} </label>
                                                        <input type="text" id="{{ $oauth.'_'.$oauth_key }}" name="site_settings[social_login_with][{{ $oauth }}][{{$oauth_key}}]" class="form-control" placeholder="{{ translate('Enter the ').translate(textFormat(['_'], $oauth, ' ').textFormat(['_'], $oauth_key, ' '))}}" aria-label="{{ translate('Enter the ').translate(textFormat(['_'], $oauth, ' ').textFormat(['_'], $oauth_key, ' '))}}" value="{{ $oauth_value }}"/>
                                                    </div>
                                                    
                                                @endif

                                            </div>
                                            @endforeach
                                            <div class="form-inner child">
                                                <label for="ouath_callback" class="form-label"> {{ translate("Callback Url") }} </label>
                                                <div class="input-group">
                                                <input type="text" id="ouath_callback" class="form-control" value="{{ url('auth/google/callback') }}" readonly disabled/>
                                                <span id="reset-primary-color" class="input-group-text copy-text pointer"> <i class="ri-file-copy-line"></i> </span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

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

@push('script-push')
    <script>
        "use strict";
        $(document).ready(function() {
            setInitialVisibility();

            $('.copy-text').click(function() {

                var message = "Text copied!";
                copy_text($(this), message);
            });
            $('.parent input[type="checkbox"]').change(function() {

                toggleChildren();
            });
            $('.switch-input').on('change', function() {

                updateBackgroundClass();
            });

            $('form').on('submit', function(e) {
                $('.checkbox-data').each(function() {
                    var $checkbox = $(this).find('.switch-input');
                    var $hiddenInput = $(this).find('input[type="hidden"]');

                    if ($checkbox.is(':checked')) {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::TRUE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::TRUE->status() }}');
                        }
                    } else {
                        if ($hiddenInput.length === 0) {
                            $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::FALSE->status() }}">');
                        } else {
                            $hiddenInput.val('{{ \App\Enums\StatusEnum::FALSE->status() }}');
                        }
                    }
                });
            });
        });
    </script>
@endpush
