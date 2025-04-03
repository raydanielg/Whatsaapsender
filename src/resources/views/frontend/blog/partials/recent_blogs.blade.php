@forelse($blogs as $blog)
    <a href="{{ route('blog', ['uid' => $blog->uid]) }}" class="recent-post-link">
        <h6>{{ $blog->title }}</h6>
        <div class="category-date">
            <span class="dot"></span>
            <span class="date">{{ $blog->created_at->format('M d, Y') }}</span>
        </div>
    </a>
@empty
    <p>{{ translate("No blogs found.") }}</p>
@endforelse