<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('') }}">
    <meta name="bee-endpoint" content="https://auth.getbee.io/apiauth">
    <meta name="bee-client-id" content="{{ json_decode(site_settings("available_plugins"), true)['beefree']['client_id'] }}">
    <meta name="bee-client-secret" content="{{ json_decode(site_settings("available_plugins"), true)['beefree']['client_secret'] }}">
    <meta name="description" content="{{ site_settings("meta_description") }}"> 
    <meta name="keywords" content="{{ implode(',', json_decode(site_settings('meta_keywords'), true)) }}">
    <meta property="og:title" content="{{ site_settings('site_name') }}">
    <meta property="og:description" content="{{ site_settings("meta_description") }}">
    <meta property="og:image" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">
    <meta name="twitter:title" content="{{ site_settings('site_name') }}">
    <meta name="twitter:description" content="{{ site_settings("meta_description") }}">
    <meta name="twitter:image" content="{{showImage(config('setting.file_path.meta_image.path').'/'.site_settings('meta_image'),config('setting.file_path.meta_image.size'))}}">
    <title>{{site_settings('site_name')}} - {{@$title}}</title>
    <link rel="shortcut icon" href="{{showImage(config('setting.file_path.favicon.path').'/'.site_settings('favicon'),config('setting.file_path.favicon.size'))}}" type="image/x-icon">
    
    
    <link rel="stylesheet" href="{{asset('assets/theme/frontend/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/theme/frontend/css/bootstrap-icons.min.css')}}" />
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/theme/frontend/css/dimbox.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/theme/frontend/css/main.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/theme/frontend/css/font-awesome.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/theme/admin/css/iconpicker/fontawesome-iconpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/theme/global/css/font_bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/theme/admin/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/theme/global/css/toastr.css')}}">

    @include('partials.theme')
</head>
<body>

    @include('frontend.sections.topbar')
    <main>
        @yield('content')
    </main>
    @include('frontend.sections.footer')
    
    <script src="{{asset('assets/theme/global/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/toastr.js')}}"></script>
    <script src="{{asset('assets/theme/frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{asset('assets/theme/frontend/js/dimbox.min.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/helper.js')}}"></script>
    <script src="{{ asset('assets/theme/admin/js/iconpicker/fontawesome-iconpicker.js') }}"></script>
    <script src="{{asset('assets/theme/frontend/js/app.js')}}"></script>
    
    @include('partials.notify')
    @stack('script-push')
</body>
</html>
