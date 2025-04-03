<!DOCTYPE html>
<html lang="{{App::getLocale()}}" dir="{{ site_settings('theme_dir') == App\Enums\StatusEnum::FALSE->status() ? 'ltr' : 'rtl' }}" class="{{ session()->get('menu_active') ? 'menu-active' : '' }}" data-bs-theme={{ site_settings("theme_mode") == App\Enums\StatusEnum::FALSE->status() ? 'dark' : 'light' }}>
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
        <link rel="stylesheet" id="bootstrap-css" href="{{asset('assets/theme/global/css/bootstrap.min.css')}}">
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
</head>
<body>
    <div class="update-wrapper">
        <div class="container">
            @yield('content')
        </div>
        <div class="update-bg">
            <img class="" src="{{ asset('assets/file/default/update.jpg') }}" alt="">
        </div>
    </div>
    
   
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
</body>
</html>
