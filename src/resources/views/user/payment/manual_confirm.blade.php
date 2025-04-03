@extends('user.layouts.app')
@section('panel')
<section>
    <div class="container-fluid p-0">
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title">{{ translate('Payment with ') }} {{$paymentLog->paymentGateway->name}} -- {{shortAmount($paymentLog->final_amount)}} {{$paymentLog->paymentGateway->currency->name}}</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{{route('user.manual.payment.update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @if($paymentLog->paymentGateway->payment_parameter != null)
                            @foreach($paymentLog->paymentGateway->payment_parameter as $key => $value)
                                @if($key=="0")
                                <p class="card-title fs-6 mb-4">{{ $value->payment_gw_info}}</p>
                                @endif
                            @endforeach
                        @endif
                        @if($paymentLog->paymentGateway->payment_parameter != null)
                            @foreach($paymentLog->paymentGateway->payment_parameter as $key => $value)
                                @if($key!="0")
                                <div class="row">
                                    @if($value->field_type == "text")
                                        <div class="mb-3 col-lg-12 col-md-12">
                                            <label for="{{$value->field_name}}" class="form-label">{{ucfirst($value->field_label)}} <sup class="text--danger">*</sup></label>
                                            <input type="text" name="{{$value->field_name}}" id="{{$value->field_name}}" class="form-control" placeholder="Enter {{ucfirst($value->field_label)}}">
                                        </div>
                                    @elseif($value->field_type == "file")
                                        <div class="mb-3 col-lg-12 col-md-12">
                                            <label for="{{$value->field_name}}" class="form-label">{{ucfirst($value->field_label)}} <sup class="text--danger">*</sup></label>
                                            <input type="file" name="{{$value->field_name}}" id="{{$value->field_name}}" class="form-control" placeholder="Enter {{ucfirst($value->field_label)}}">
                                        </div>

                                    @elseif($value->field_type == "textarea")
                                        <div class="mb-3 col-lg-12 col-md-12">
                                            <label for="{{$value->field_name}}" class="form-label">{{ucfirst($value->field_label)}} <sup class="text--danger">*</sup></label>
                                            <textarea type="text" name="{{$value->field_name}}" id="{{$value->field_name}}" class="form-control" placeholder="Enter {{ucfirst($value->field_label)}}"></textarea>
                                        </div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <button type="submit" class="i-btn primary--btn btn--md">{{ translate('Confirm')}}</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
