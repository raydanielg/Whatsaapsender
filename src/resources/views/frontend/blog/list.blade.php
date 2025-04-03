@extends('frontend.layouts.main')
@section('content')
    @include('frontend.blog.section.breadcrumb_banner', ['title' => $title])
    <section class="blog pb-130">
        <div class="container-fluid container-wrapper">
          <div class="row g-4 gy-5">
            <div class="col-xl-8">
                <div class="row g-lg-5 gx-4 gy-5">
                    @forelse($blogs as $blog)
                        <div class="col-md-6">
                            <div class="blog-card">
                                <div class="blog-img">
                                    <img src="{{ showImage(config("setting.file_path.blog_images.path").'/'.@$blog->image, config("setting.file_path.blog_images.size")) }}" alt="blog" />
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
                    @empty
                        <div class="col-12">
                            <p>{{translate("No blogs found.")}}</p>
                        </div>
                    @endforelse
                </div>
                
                @if($blogs->hasPages())
                    <div class="pagination-wrapper">
                        <nav aria-label="Blog pagination">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if($blogs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-double-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->url(1) }}" rel="prev">
                                            <i class="bi bi-chevron-double-left"></i>
                                        </a>
                                    </li>
                                @endif
            
                                {{-- Pagination Elements --}}
                                @foreach ($blogs->onEachSide(1)->links()->elements as $element)
                                    {{-- "Three Dots" Separator --}}
                                    @if (is_string($element))
                                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                    @endif
            
                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $blogs->currentPage())
                                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
            
                                {{-- Next Page Link --}}
                                @if ($blogs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->nextPageUrl() }}" rel="next">
                                            <i class="bi bi-chevron-double-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-double-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
            <div class="col-xl-4">
                <div class="blog-right ms-xl-5">
                  <div class="blog-right-item">
                    <form id="blogSearchForm">
                      <input class="search-input" type="search" name="search" id="blogSearchInput" placeholder="Search" />
                    </form>
                  </div>
                  <div class="blog-right-item">
                    <h3>{{ translate("Recent Blogs") }}</h3>
                    <div class="recent-post" id="recentBlogs">
                        
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
    $(document).ready(function() {
        function loadRecentBlogs(search = '') {
            $.ajax({
                url: '{{ route("blog.search") }}',
                method: 'GET',
                data: { search: search },
                success: function(response) {
                    $('#recentBlogs').html(response);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }
    
        // Initial load
        loadRecentBlogs();
    
        // Real-time search
        var searchTimer;
        $('#blogSearchInput').on('input', function() {
            clearTimeout(searchTimer);
            var search = $(this).val();
            searchTimer = setTimeout(function() {
                loadRecentBlogs(search);
            }, 300); // Debounce for 300ms
        });
    
        // Prevent form submission
        $('#blogSearchForm').on('submit', function(e) {
            e.preventDefault();
        });
    });
    </script>
@endpush