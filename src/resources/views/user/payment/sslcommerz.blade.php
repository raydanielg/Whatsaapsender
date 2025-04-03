@extends('user.layouts.app')
@section('panel')
<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="row gy-4">
          <div class="col-md-12">
            <div class="page-header-left">
              <h2>{{ $title }}</h2>
              <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.dashboard') }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.plan.create') }}">{{ translate("Buy Or Renew Plan") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.plan.make.payment', $id) }}">{{ translate("Make Payment") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- SSL Commerz") }} </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header pt-4 justify-content-center">
              <h4 class="card-title">{{translate($title)}}</h4>
            </div>
            <div class="card-body p-4">
              
                <form class="form-horizontal justify-content-center" method="POST" id="payment-form" role="form" action="{{route('user.payment.with.ssl')}}" >
                    @csrf
                    <div class="row justify-content-center">
                        <button type="submit" class="i-btn btn--primary outline btn--md w-25 border-0 rounded p-2" id="sslczPayBtn">{{ translate('Pay Now')}}</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
@endsection
