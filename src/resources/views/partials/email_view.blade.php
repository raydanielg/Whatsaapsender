<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{asset('assets/theme/global/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/theme/global/css/all.min.css')}}">
</head>
<body>
    {!!$emailLogs->message["message_body"]!!}  <span class="text-danger" >{{$emailLogs->response_message ? ":".$emailLogs->response_message :"" }}</span>
    <script src="{{asset('assets/theme/global/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/theme/global/js/all.min.js')}}"></script>
</body>
</html>
