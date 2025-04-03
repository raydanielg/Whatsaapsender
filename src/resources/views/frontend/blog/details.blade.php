@extends('frontend.layouts.main')
@section('content')
    @include('frontend.blog.section.breadcrumb_banner', ['title' => $title])
    <section class="blog pb-130">
        <div class="container-fluid container-wrapper">
          <div class="row g-4 gy-5">
            <div class="col-xl-8">
              <div class="blog-detail">
                <div class="detail-img">
                    <img src="{{showImage(config("setting.file_path.blog_images.path").'/'.$blog->image,config("setting.file_path.blog_images.size"))}}" alt="{{ $blog->image }}" />
                </div>
                <h3 class="blog-detail-title">
                  {{$blog->title}}
                </h3>
                <div class="blog-description">
                  <p> @php echo $blog->description @endphp </p>
                  
                </div>
              </div>
            </div>
            <div class="col-xl-4">
              <div class="blog-right ms-xl-5">
                <div class="blog-right-item">
                  <h3>{{ translate("Recent Blogs") }}</h3>
                  <div class="recent-news">
                    <div class="row gx-lg-5 gy-5">
                        @foreach($blogs as $blog)
                        <div class="col-xl-12 col-md-6">
                            <div class="blog-card">
                              <div class="blog-img">
                                <img src="{{showImage(config("setting.file_path.blog_images.path").'/'.@$blog->image,config("setting.file_path.blog_images.size"))}}" alt="blog" />
                                <span>{{ $blog->created_at->toDayDateTimeString() }}</span>
                              </div>
                              <a href="{{ route('blog', ['uid' => $blog->uid]) }}" class="blog-title">
                                <p> {{ $blog->title }} </p>
                                <span class="bg--dark">
                                  <i class="bi bi-arrow-up-right"></i>
                                </span>
                              </a>
                            </div>
                          </div>
                        @endforeach
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection
