@props([
    'mat',
    'detailRoute' => null,  // Es: 'studyroom.ricerca.dettagli' o 'tutoraggio.dettagli'
    'reviewsRoute' => null, // Es: 'studyroom.recensioni.index' o 'bacheca.recensioni'
    'idParam' => 'idMateriale' // Nome del parametro ID richiesto dalla route (es. 'id' o 'idMateriale')
])

@php
    $stelle = round($mat->mediaValutazione ?? 0);
    // Recuperiamo l'ID dinamico usando la proprietà passata
    $id = $mat->$idParam ?? $mat[$idParam] ?? $mat->id ?? $mat['id'] ?? null;
 
@endphp

<div class="card">
    {{-- Link principale (stretched-link) --}}
    @if($detailRoute)
        <a class="card__link"
           href="{{ route($detailRoute, $id) }}"
           aria-label="Apri il dettaglio di {{ $mat->titoloMateriale }}"></a>
    @else
        {{-- Fallback se non c'è route, da gestire meglio nel layout finale --}}
        <span class="card__link" style="cursor: default;"></span>
    @endif

    <div class="card__icon" aria-hidden="true">
        {{-- SVG dell'icona invariato --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 96" fill="none">
            <rect x="4" y="4" width="72" height="88" rx="6" ry="6" stroke="#1a1a2e" stroke-width="4" fill="white"/>
            <path d="M52 4 L76 28" stroke="#1a1a2e" stroke-width="4"/>
            <path d="M52 4 L52 28 L76 28" fill="#ede9fb" stroke="#1a1a2e" stroke-width="4" stroke-linejoin="round"/>
            <line x1="14" y1="44" x2="66" y2="44" stroke="#6C4FD4" stroke-width="3.5" stroke-linecap="round"/>
            <line x1="14" y1="56" x2="66" y2="56" stroke="#6C4FD4" stroke-width="3.5" stroke-linecap="round"/>
            <line x1="14" y1="68" x2="50" y2="68" stroke="#6C4FD4" stroke-width="3.5" stroke-linecap="round"/>
        </svg>
    </div>

    <div class="card__body">
        <h2 class="card__title">{{ $mat->titoloMateriale }}</h2>
        <p class="card__meta">{{ $mat->insegnamento }}</p>
        <p class="card__meta">{{ $mat->corso_di_laurea }}</p>
        <p class="card__meta card__meta--tipo">{{ $mat->tipologia }}</p>

       <x-star-rating :media="$mat->mediaValutazione ?? 0">
            <span class="materiale-rating__count">
                ({{ $mat->numeroRecensioni ?? 0 }})
            </span>
        </x-star-rating>

        <div class="card__footer">
            <div class="card__downloads" aria-label="{{ $mat->numeroDownload ?? 0 }} download">
                {{-- Icona download SVG invariata --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>{{ $mat->numeroDownload ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>