@props(['action' => '#', 'query' => ''])

<section class="hero-search">
    <form class="hero-search__form" action="{{ $action }}" method="GET">
        <input
            type="text"
            name="titolo"
            class="hero-search__input"
            placeholder="Cerca un materiale per titolo…"
            value="{{ $query }}"
            maxlength="100"
        >
        <button type="submit" class="hero-search__btn" aria-label="Avvia ricerca">
            <i class="fa fa-magnifying-glass"></i>
        </button>
    </form>
</section>