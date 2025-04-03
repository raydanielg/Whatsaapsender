<!DOCTYPE html>

<html lang="us"
dir="ltr"
data-sidebar-mode="light"
data-topbar-mode="light"
data-bs-theme="light">
    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="color-scheme" content="light dark" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">
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
        
        <link rel="stylesheet" id="bootstrap-css" href="{{asset('assets/theme/global/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/toastr.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/bootstrap-icons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/remixicon.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/remixicon.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/custom.css')}}">
        <link rel="stylesheet" href="{{asset('assets/theme/global/css/main.css')}}">
        
        @stack('style-include')
        @stack('style-push')
        @include('partials.theme')

    </head>
    <body>

        @yield('content')
        
        
        <script src="{{asset('assets/theme/global/js/jquery-3.7.1.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/toastr.js')}}"></script>
        <script src="{{asset('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js')}}"></script>
        <script src="{{asset('assets/theme/global/js/initialized.js')}}"></script>
        @include('partials.notify')
        @stack('script-push')
    </body>
</html>
