@if ($paginator->hasPages())
    @php
        $start = max(1, $paginator->currentPage() - 2);
        $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
    @endphp
    <nav class="pagination" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pagination__button is-disabled" aria-disabled="true">
                <x-icon name="arrow-left" />
                <span>Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination__button" rel="prev">
                <x-icon name="arrow-left" />
                <span>Sebelumnya</span>
            </a>
        @endif

        <div class="pagination__pages">
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="pagination__page">1</a>
                @if ($start > 2)
                    <span class="pagination__ellipsis" aria-hidden="true">...</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page === $paginator->currentPage())
                    <span class="pagination__page is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="pagination__page">{{ $page }}</a>
                @endif
            @endfor

            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="pagination__ellipsis" aria-hidden="true">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination__page">{{ $paginator->lastPage() }}</a>
            @endif
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination__button" rel="next">
                <span>Berikutnya</span>
                <x-icon name="arrow-right" />
            </a>
        @else
            <span class="pagination__button is-disabled" aria-disabled="true">
                <span>Berikutnya</span>
                <x-icon name="arrow-right" />
            </span>
        @endif
    </nav>
@endif
