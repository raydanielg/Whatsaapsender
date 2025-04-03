@extends('admin.layouts.master')
@section('content')
<div class="error">
    <div class="container-fluid px-5 h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-8 h-100">
                <div class="error_background">
                    <div class="error_container">
                            <div class="error_text">
                                {{ translate('404') }}
                            </div>
                            <h2>{{ translate('Oops!')}}</h2>
                            <p>{{ translate('The page you have requested is unavailable')}}
                            </p>

                            <a class="i-btn btn--primary btn--lg add-user mt-5" href="{{url('/')}}">
                                <i class="bi bi-arrow-left fs-28"></i>
                                {{ translate('Back to home')}}
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
