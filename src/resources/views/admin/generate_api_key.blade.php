@extends('admin.layouts.app')
@section('panel')
<div class="page-title-wrapper">
    <div class="page-title-left">
        <h2 class="page-title ">Documention</h2>
    </div>

    <div class="page-title-right">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ translate($title) }}</li>
            </ol>
        </nav>
    </div>
</div>
    <section>
        <div class="container-fluid p-0 mb-3 pb-2">
            <div class="row d-flex align--center rounded">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row pb-3 g-3">
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="apikey" value="{{$admin->api_key}}" placeholder="Click on the button to generate a new API key ..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text bg--info pointer" onclick="myFunction()" id="basic-addon2"><i class="me-1 las la-copy fs-5"></i> {{translate('Copy')}}</span>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <a href="javascript:void(0)" class="i-btn primary--btn btn--md w-100 border-0 rounded text-white p-2" id="keygen"><i class="me-1 las la-key fs-5"></i> {{translate('Generate API Key')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('script-push')
    <script>
        function generateUUID(){
            var d = new Date().getTime();
            if( window.performance && typeof window.performance.now === "function" ){
                d += performance.now();
            }
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c){
                var r = (d + Math.random()*16)%16 | 0;
                d = Math.floor(d/16);
                return (c=='x' ? r : (r&0x3|0x8)).toString(16);
            });
            return uuid;
        }

        $( '#keygen' ).on('click',function() {

            var api_key_value = generateUUID();
            $('#apikey').val(api_key_value);

            $.ajax({
                type : "POST",
                url  : "{{route('admin.save.generate.api.key')}}",
                data : {_token : "{{ csrf_token() }}", api_key : api_key_value},
                success:function(response){
                    if(response.error){
                        notify('error', response.error)
                    }else{
                        notify('success',response.message);
                    }
                }
            });
        });

        function myFunction() {
            var copyText = document.getElementById("apikey");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            notify('success', 'Copied the text : ' + copyText.value);
        }
    </script>
@endpush

