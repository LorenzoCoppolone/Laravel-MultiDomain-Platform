@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Navigazione pagine">
        {{-- Freccia precedente --}}
        @if ($paginator->onFirstPage())
            <span class="pagination__btn pagination__btn--prev pagination__btn--disabled" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination__btn pagination__btn--prev" aria-label="Pagina precedente">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        @endif

        {{-- NOTA: Con simplePaginate non esistono i numeri di pagina ($elements). 
             Mostriamo solo i controlli di scorrimento sequenziale. --}}

        {{-- Freccia successiva --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination__btn pagination__btn--next" aria-label="Pagina successiva">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span class="pagination__btn pagination__btn--next pagination__btn--disabled" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif
    </nav>
@endif