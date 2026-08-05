@extends('studyroom.layouts.layout')

@section('title', 'Risultati ricerca — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/material-card.css',
        'resources/css/components/pagination.css',
        'resources/css/studyroom/styleRicercaMateriale.css',
        'resources/css/studyroom/star-rating.css',
        'resources/js/studyroom/upload.js'
    ])
@endsection

@section('content')

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