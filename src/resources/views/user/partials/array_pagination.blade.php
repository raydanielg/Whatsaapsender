{{-- resources/views/admin/partials/array_pagination.blade.php --}}
@if(isset($meta_data) && $meta_data->hasPages())
<div class="pagination-wrapper px-4 pt-3">
    <p class="pagination-summary">
        @if ($meta_data->count())
            {{ translate('Showing') }} {{ $meta_data->firstItem() }}-{{ $meta_data->lastItem() }} {{ translate('from') }} {{ $meta_data->total() }}
        @endif
    </p>
    <nav aria-label="...">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($meta_data->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ route(Route::currentRouteName(), array_merge(request()->all(), ['page' => $meta_data->currentPage() - 1])) }}" rel="prev">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($meta_data->links()->elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $meta_data->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ route(Route::currentRouteName()).$url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($meta_data->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ route(Route::currentRouteName(), array_merge(request()->all(), ['page' => $meta_data->currentPage() + 1])) }}" rel="next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif