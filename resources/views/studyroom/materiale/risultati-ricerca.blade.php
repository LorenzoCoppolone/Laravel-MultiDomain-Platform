@extends('studyroom.layouts.layout')

@section('title', 'Risultati ricerca — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/hero-search.css',
        'resources/css/components/combo-box.css',
        'resources/css/components/material-card.css',
        'resources/css/components/material-filter.css',
        'resources/css/components/pagination.css',
        'resources/css/studyroom/styleRicercaMateriale.css',
        'resources/js/studyroom/upload.js'
    ])
@endsection

@section('content')

@php
    $queryCorrente = session('ricerca_titolo') ?? ($filtri['titolo'] ?? '');
    // CORRETTO: puntiamo a 'criterio' come definito nel form e nel controller
    $ordinamento = $filtri['criterio'] ?? '';
@endphp

<!-- ===================== HERO SEARCH ===================== -->
<x-hero-search :action="route('studyroom.materiali.filtra')" :query="$queryCorrente" />
<!-- ===================== /HERO SEARCH ===================== -->

<!-- ===================== FILTRI ===================== -->
<section class="filters-bar">
    <form id="filter-form" class="filters-panel" action="{{ route('studyroom.materiali.filtra') }}" method="GET">

        <input type="hidden" name="titolo" value="{{ $queryCorrente }}">
        <input type="hidden" name="page" value="1">

        <div class="filters-bar__pills">

            <!-- COMBOBOX: CORSO DI LAUREA -->
            <x-combo-box 
                id="cdlCombo" inputId="cdlInput" name="cdl" hiddenId="cdlValue" listId="cdlList"
                wrapperClass="filter-combo"
                placeholder="Corso di Laurea"
                :value="$selectedCdlNome ?? ''" :hiddenValue="$selectedCdl ?? ''">
                
                @foreach($corsi as $c)
                    <li class="combo-item" role="option" data-value="{{ $c->codice_corso }}" data-label="{{ $c->nome_corso }}">{{ $c->nome_corso }}</li>
                @endforeach
            </x-combo-box>

            <!-- COMBOBOX: INSEGNAMENTO -->
            <x-combo-box 
                id="insCombo" inputId="insInput" name="insegnamento" hiddenId="insValue" listId="insList"
                wrapperClass="filter-combo"
                placeholder="Seleziona un corso"
                lockedPlaceholder="Seleziona prima un corso" readyPlaceholder="Scrivi l'insegnamento..."
                :value="$selectedInsNome ?? ''" :hiddenValue="$selectedIns ?? ''" disabled>
                
                @foreach($insegnamenti as $i)
                    <li class="combo-item" role="option" data-value="{{ $i->id }}" data-cdl="{{ $i->corso_di_laurea_codice }}" data-label="{{ $i->nome_insegnamento }}">{{ $i->nome_insegnamento }}</li>
                @endforeach
            </x-combo-box>

            <!-- Tipologia -->
            <div class="filter-pill {{ !empty($filtri['tipologia']) ? 'filter-pill--active' : '' }}">
                <select name="tipologia" class="filter-pill__select">
                    <option value="">Tipologia</option>
                    <option value="appunto" {{ ($filtri['tipologia'] ?? '') == 'appunto' ? 'selected' : '' }}>Appunto</option>
                    <option value="esame" {{ ($filtri['tipologia'] ?? '') == 'esame' ? 'selected' : '' }}>Esame</option>
                </select>
            </div>

            <!-- Ordinamento -->
            <div class="filter-pill {{ !empty($ordinamento) ? 'filter-pill--active' : '' }}">
                <select name="criterio" class="filter-pill__select">
                    <option value="" {{ $ordinamento == '' ? 'selected' : '' }}>Ordina per…</option>
                    <option value="download" {{ $ordinamento == 'download' ? 'selected' : '' }}>Più scaricati</option>
                    <option value="valutazione" {{ $ordinamento == 'valutazione' ? 'selected' : '' }}>Valutazione</option>
                </select>
            </div>

        </div> <!-- /Fine filters-bar__pills -->

        <button type="submit" class="btn-applica-filtri">
            Applica filtri
        </button>

    </form>
</section>
<!-- ===================== /FILTRI ===================== -->

<!-- ===================== RISULTATI ===================== -->
<section class="results-section">
    <div class="results-grid">

        @forelse($materiali as $mat)
            <x-material-card 
                :mat="$mat"
                detailRoute="studyroom.materiale.dettagli"
                reviewsRoute="studyroom.materiale.recensioni"
                idParam="idMateriale"
            />
        @empty
            <div class="results-empty">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    <line x1="8" y1="11" x2="14" y2="11"/>
                </svg>
                <p>Nessun materiale trovato per la tua ricerca.</p>
                <span>Prova a modificare i filtri o la parola chiave.</span>
            </div>
        @endforelse

    </div>
</section>
<!-- ===================== /RISULTATI ===================== -->


<!-- ===================== PAGINAZIONE ===================== -->
{{ $materiali->withQueryString()->links('components.pagination') }}

@endsection