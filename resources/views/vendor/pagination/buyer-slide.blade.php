@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center align-items-center gap-1.5 slide-pagination-wrap" role="navigation" aria-label="Navigasi Halaman">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="slide-nav-btn disabled" aria-disabled="true">
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="slide-nav-btn">
                Sebelumnya
            </a>
        @endif

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

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="slide-nav-btn">
                Selanjutnya
            </a>
        @else
            <span class="slide-nav-btn disabled" aria-disabled="true">
                Selanjutnya
            </span>
        @endif
    </nav>
@endif
