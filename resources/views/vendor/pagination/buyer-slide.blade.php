@if ($paginator->hasPages())
    <nav class="slide-pagination-wrap" role="navigation" aria-label="Navigasi Halaman">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="slide-nav-btn disabled" aria-disabled="true" aria-label="Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="slide-nav-btn" aria-label="Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        <div class="d-inline-flex align-items-center gap-2">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="slide-nav-dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="slide-nav-page active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="slide-nav-page">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="slide-nav-btn" aria-label="Selanjutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="slide-nav-btn disabled" aria-disabled="true" aria-label="Selanjutnya">
                <i class="bi bi-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
