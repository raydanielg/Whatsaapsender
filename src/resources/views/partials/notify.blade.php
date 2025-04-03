@if(session()->has('notify'))
    <script>
        "use strict";
        @foreach(session('notify') as $message)
            toastr["{{$message[0]}}"]("{{$message[1]}}")
            toastr.options = {
              "closeButton": true,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-bottom-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
        @endforeach
    </script>
@endif

@if($errors->any())
    <script>
        "use strict";
        @foreach($errors->all() as $message)
            toastr["error"]("{{$message}}")
            toastr.options = {
              "closeButton": true,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-bottom-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
        @endforeach
    </script>
@endif
<script>
    "use strict";
    function notify(status,message) {
        let words = message.trim().split(/\s+/).length;
        
        let timeOut = 5000; 

        if (words > 0) {
            timeOut = words * 500; 
        }

        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-bottom-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: 300,
            hideDuration: 1000,
            timeOut: timeOut, 
            extendedTimeOut: 1000,
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        toastr[status](message);
    }
</script>