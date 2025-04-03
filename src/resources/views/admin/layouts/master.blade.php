<!DOCTYPE html>

<html lang="{{ App::getLocale() }}" dir="{{ site_settings('theme_dir') == \App\Enums\StatusEnum::FALSE->status() ? 'ltr' : 'rtl' }}" class="{{ session()->get('menu_active') ? 'menu-active' : '' }}" data-bs-theme="{{ site_settings('theme_mode') == \App\Enums\StatusEnum::FALSE->status() ? 'dark' : 'light' }}">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="color-scheme" content="light dark" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('') }}">
        <meta name="bee-endpoint" content="https://auth.getbee.io/apiauth">
        <meta name="bee-client-id" content="{{ json_decode(site_settings("available_plugins"), true)['beefree']['client_id'] }}">
        <meta name="bee-client-secret" content="{{ json_decode(site_settings("available_plugins"), true)['beefree']['client_secret'] }}">

        <meta name="description" content="{{ site_settings("meta_description") }}"> 
        <meta name="keywords" content="{{ implode(',', json_decode(site_settings('meta_keywords'), true)) }}">
        <meta property="og:title" content="{{ site_settings("meta_title") }}">
        <meta property="og:description" content="{{ site_settings("meta_description") }}">
        <meta property="og:image" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta name="twitter:card" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">
        <meta name="twitter:title" content="{{ site_settings('meta_title') }}">
        <meta name="twitter:description" content="{{ site_settings("meta_description") }}">
        <meta name="twitter:image" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">

        <title>{{site_settings('site_name')}} - {{@$title}}</title>
        <link rel="shortcut icon" href="{{showImage(config('setting.file_path.favicon.path').'/'.site_settings('favicon'),config('setting.file_path.favicon.size'))}}" type="image/x-icon">
        @stack('meta-include')
        
        @if(site_settings('theme_dir') == App\Enums\StatusEnum::TRUE->status()) 
            <link rel="stylesheet" href="{{asset('assets/theme/global/css/bootstrap.rtl.min.css')}}">
            
        @else
            <link rel="stylesheet" id="bootstrap-css" href="{{asset('assets/theme/global/css/bootstrap.min.css')}}">
        @endif
        
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/toastr.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/bootstrap-icons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/remixicon.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/simplebar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/flatpickr.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/custom.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/main.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/admin/css/all.min.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/theme/admin/css/spectrum.css') }}">
        
        @stack('style-include')
        @stack('style-push')
        @include('partials.theme')

    </head>
    <body>

        @yield('content')
        
        <script src="{{asset('assets/theme/global/js/jquery-3.7.1.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/app.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/apexcharts.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/toastr.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/flatpickr.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/helper.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/initialized.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/script.js')}}"></script>
        <script src="{{ asset('assets/theme/admin/js/spectrum.js') }}"></script>
        <script src="{{asset('assets/theme/global/ckeditor5-build-classic/ckd.js')}}"></script>
      
        @include('partials.notify')
        @stack('script-include')
        @stack('script-push')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const bodyElement = document.body;
                const themeToggleButton = document.querySelector('.theme-toggler');
                
                // Check local storage for the user's theme preference
                const currentTheme = localStorage.getItem('theme_mode');
                
                // Apply theme based on local storage or fallback to admin's setting
                if (currentTheme) {
                    bodyElement.setAttribute('data-bs-theme', currentTheme);
                } else {
                    const adminTheme = '{{ site_settings("theme_mode") == \App\Enums\StatusEnum::FALSE->status() ? "dark" : "light" }}';
                    bodyElement.setAttribute('data-bs-theme', adminTheme);
                }
        
                // Toggle theme on button click
                themeToggleButton.addEventListener('click', function() {
                    let newTheme = bodyElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                    bodyElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme_mode', newTheme);
                    document.getElementById('theme_mode').value = newTheme;
                });
            });
        </script>
        <script>
            function deviceStatusUpdate(id,status,className='',beforeSend='',afterSend='') {
       
               if (id=='') {
       
                   id = $("#scan_id").val();
               }
               $('.qrQuote').modal('hide');
               $.ajax({
       
                   headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                   url:"{{route('admin.gateway.whatsapp.device.status.update')}}",
                   data: {id:id,status:status},
                   dataType: 'json',
                   method: 'post',
                   beforeSend: function() {
       
                       if (beforeSend!='') {
       
                           $('.'+className+id).html(`<i class="ri-loader-2-line"></i>
                                                   <span class="tooltiptext"> {{ translate("Loading") }} </span>`);
                       }
                   },
                   success: function(res) {
       
                       sleep(1000).then(() => {
       
                           location.reload();
                       })
                   },
                   complete: function() {
       
                       if (afterSend!='') {
       
                           $('.'+className+id).html(`<i class="ri-qr-code-fill"></i>
                                                   <span class="tooltiptext"> {{ translate("scan") }} </span>`);
                       }
                   }
               })
           }
       </script>
        <script>
            'use strict';
            function changeLang(val, code) {

                window.location.href = "{{ route('language.change', ['lang' => '_languageId_']) }}".replace('_languageId_', val);
            }
            $(document).ready(function() {

                $(document).on('click', '.statusUpdateByUID', function (e) {
                    
                    const uid = $(this).attr('data-uid')
                    var column = ($(this).attr('data-column'))
                    var route  = ($(this).attr('data-route'))
                    var value  = ($(this).attr('data-value'))
                    const data = {
                        'uid': uid,
                        'column': column,
                        'value': value,
                        "_token" :"{{csrf_token()}}",
                    }
                    updateStatusByUID(route, data, $(this))
                })

                $(document).on('click', '.statusUpdate', function (e) {
                    
                    const id = $(this).attr('data-id')
                    var column = ($(this).attr('data-column'))
                    var route  = ($(this).attr('data-route'))
                    var value  = ($(this).attr('data-value'))
                    const data = {
                        'id': id,
                        'column': column,
                        'value': value,
                        "_token" :"{{csrf_token()}}",
                    }
                    updateStatus(route, data, $(this))
                })

                // update status method
                function updateStatusByUID(route, data, html_object) {
                    var responseStatus;
                    $.ajax({
                        method: 'POST',
                        url: route,
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response) {
                            responseStatus = response.status ? "success" : "error";

                            if (typeof response.message === 'object' && response.message !== null) {
                                for (let key in response.message) {
                                    if (response.message.hasOwnProperty(key)) {
                                        notify('error', response.message[key][0] || response.message[key]);
                                    }
                                }
                            } else {
                                notify(responseStatus, response.message);
                            }
                            if (response.reload) {
                                location.reload();
                            }
                        }
                        },
                        error: function (error) {
                            if(error && error.responseJSON){
                                if(error.responseJSON.errors){
                                    for (let i in error.responseJSON.errors) {
                                        notify('error', error.responseJSON.errors[i][0])
                                    }
                                }
                                else{
                                    notify('error', error.responseJSON.error);
                                }
                            }
                            else{
                                notify('error', error.message);
                            }
                        }
                    })
                }

                function updateStatus(route, data, html_object) {
                    var responseStatus;
                    $.ajax({
                        method: 'POST',
                        url: route,
                        data: data,
                        dataType: 'json',
                        success: function (response) {

                            if (response) {
                                responseStatus = response.status? "success" :"error"
                                notify(responseStatus, response.message)
                                if(response.reload) {
                                    location.reload();
                                }
                            }
                        },
                        error: function (error) {
                            if(error && error.responseJSON){
                                if(error.responseJSON.errors){
                                    for (let i in error.responseJSON.errors) {
                                        notify('error', error.responseJSON.errors[i][0])
                                    }
                                }
                                else{
                                    notify('error', error.responseJSON.error);
                                }
                            }
                            else{
                                notify('error', error.message);
                            }
                        }
                    })
                }

                $('.theme-toggler').on('click', function() {

                    var currentTheme = "{{ site_settings('theme_mode') }}";
                    var newTheme = currentTheme == "{{ \App\Enums\StatusEnum::FALSE->status() }}" ? "{{ \App\Enums\StatusEnum::TRUE->status() }}" : "{{ \App\Enums\StatusEnum::FALSE->status() }}";
                    $('#theme_mode').val(newTheme);
                    $('.themeForm').submit();
                });

                $(document).on('submit','.settingsForm',function(e){

                    var data =   new FormData(this)
                    var route = "{{route('admin.system.setting.store')}}"

                    if($(this).attr('data-route')) {

                        route = $(this).attr('data-route')
                    }
                    $.ajax({
                        method:'post',
                        url: route,
                        beforeSend: function() {

                        
                        },
                        dataType: 'json',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: data,
                        success: function(response) {

                            var response_status = 'success';
                            if(response.reload) {

                                window.location.reload()
                            }
                            if(!response.status) {

                                response_status = 'error';
                            }
                            notify(response_status, response.message);
                        },
                        error: function (error) {

                            if(error && error.responseJSON) {

                                if(error.responseJSON.errors) {

                                    for (let i in error.responseJSON.errors) {
                                        notify('error', error.responseJSON.errors[i][0]);
                                    }
                                }
                                else{
                                    if((error.responseJSON.message)) {

                                        notify('error', error.responseJSON.message);
                                    }
                                    else{
                                        notify('error', error.responseJSON.error);
                                    }
                                }
                            }
                            else{
                                notify('error', error.message);
                            }
                        },
                        complete: function() {
                        
                        },

                    });

                    e.preventDefault();
                });

                document.addEventListener('DOMContentLoaded', function() {

                    if (window.innerWidth < 1200) {
                        
                        document.documentElement.classList.remove('menu-active');
                    }
                });

                $('.menu-link').on('click', function() {
                    
                    if ("{{ session()->get('menu_active') }}" == "{{ \App\Enums\StatusEnum::TRUE->status() }}" && window.innerWidth >= 1199) {
                        
                        if ($(this).is('a') && $(this).attr('href') !== 'javascript:void(0)') {

                            $('html').removeClass('menu-active');

                        }  else {
                            $('html').addClass('menu-active');
                        }
                    } 
                });
            });

        </script>
    </body>
</html>
