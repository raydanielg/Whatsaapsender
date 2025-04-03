<section class="blog pt-130 pb-130">
    <div class="container-fluid container-wrapper">
      <div class="row align-items-center mb-60">
        <div class="col-8">
          <div class="section-title mb-0">
            <h3>{{getTranslatedArrayValue(@$blog_content->section_value, 'heading') }}<span>
                <img src="{{showImage('assets/file/default/frontend'."/"."star.svg","45x45")}}" alt="long-arrow"/>
              </span>
            </h3>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="{{ route('blog') }}" class="i-btn btn--dark outline btn--md pill"> {{ translate("More") }} <i class="bi bi-arrow-right fs-20"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="row gx-xxl-5 g-4 gy-5">
        @foreach($blogs->take(3) as $blog)
        <div class="col-xl-4 col-md-6">
          <div class="blog-card">
            <div class="blog-img">
              <img src="{{showImage(config("setting.file_path.blog_images.path").'/'.@$blog->image,config("setting.file_path.blog_images.size"))}}" alt="blog" />
              <span>{{ $blog->created_at->toDayDateTimeString() }}</span>
            </div>
            <a href="{{ route('blog', ['uid' => $blog->uid]) }}" class="blog-title">
              <p>{{ $blog->title }}</p>
              <span>
                <i class="bi bi-arrow-up-right"></i>
              </span>
            </a>
          </div>
        </div>
        @endforeach
       
      </div>
    </div>
  </section>