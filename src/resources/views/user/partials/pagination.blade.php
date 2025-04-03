@if(isset($paginator) && $paginator->hasPages())
<div class="pagination-wrapper px-4 pt-3">
    <p class="pagination-summary">
        @if ($paginator->appends(request()->all()))
            {{ translate("Showing") }} {{ $paginator->appends(request()->all())->firstItem() }}-{{ $paginator->appends(request()->all())->lastItem() }} {{ translate("from") }} {{ $paginator->appends(request()->all())->total() }}
        @endif
    </p>
    <nav aria-label="...">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            @foreach ($paginator->links()->elements as $element)
                @if(is_array($element))
                    @php $i = 1; @endphp
                    @foreach ($element as $url)
                        @php
                            if(request()->input("date")) {
                                $query_step = 4;
                            }
                            elseif(request()->input("search") || request()->input("status")) {
                                $query_step = 2;
                            } elseif(request()->_token) {
                                $query_step = 2;
                            } else {
                                $query_step = 1;
                            }
                            
                            $query_params = parse_url($url, PHP_URL_QUERY);
                            
                            $query_array  = $query_params ? explode('=', $query_params) : [];
                                
                            $page = isset($query_array[$query_step]) ? $query_array[$query_step] : (string)$i;
                            $i++;
                        @endphp
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="page-item" aria-current="page">
                        <span class="page-link">{{ $element}}</span>
                    </li>
                @endif
            @endforeach
            
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
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