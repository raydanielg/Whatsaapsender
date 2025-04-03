@extends('frontend.layouts.main')
@section('content')
    @include('frontend.policy.section.breadcrumb_banner', ['title' => $title])
    <section class="blog pb-130">
        <div class="container-fluid container-wrapper">
          <div class="row justify-content-center">
            <div class="col-xl-8">
              
              @php echo $description; @endphp
            </div>
          </div>
        </div>
      </section>
@endsection