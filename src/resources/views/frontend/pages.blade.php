@extends('frontend.layouts.main')
@section('content')
     <section class="policy-pages pt-100 pb-100" id="policy">
        <div class="container">
            <div class="section-header">
                <div class="section-header-left">
                    <span class="sub-title">
                        {{translate("Policy Page")}}
                    </span>
                    <h3 class="section-title">{{strtoupper(str_replace('-'," ",$key))}}</h3>
                </div>
            </div>

            <div>
                @php
                    echo $description;
                @endphp
            </div>
        </div>
    </section>
@endsection
