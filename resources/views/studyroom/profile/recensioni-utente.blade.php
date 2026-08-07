@extends('studyroom.layouts.layout')

@section('title', 'Le mie recensioni — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/star-rating.css', /* Richiamo CSS globale delle stelle */
        'resources/css/components/pagination.css',  /* Richiamo CSS della paginazione */
        'resources/css/studyroom/styleRecensioniUtente.css', /* CSS specifico per questa pagina */
        'resources/css/components/popup.css',
        'resources/js/components/popup.js'
    ])
@endsection

@section('content')

{{-- Toast di esito (successo/errore) tramite flash message in sessione --}}
@if (session('success') || session('error'))
    <!-- Puoi mantenere id="popup-preferiti" o usare una classe/id generico gestito dal tuo popup.js -->
    <div id="popup-preferiti" class="popup {{ session('success') ? 'popup-successo' : 'popup-errore' }}">
        <span class="popup-messaggio">{{ session('success') ?? session('error') }}</span>
    </div>
@endif

<section class="reviews-wrapper">

<section class="reviews-wrapper">

    <h1 class="reviews-title"><i class="fa fa-star"></i> Le mie recensioni</h1>

    @if ($recensioni->count() > 0)

        <div class="reviews-list">

            @foreach ($recensioni as $rec)
                <article class="review-card">

                    <header class="review-card__head">
                        <!-- Link al dettaglio del materiale -->
                        <a href="{{ route('studyroom.materiale.dettagli', $rec->idMateriale) }}" class="review-card__material">
                            <i class="fa fa-file-lines"></i>
                            {{ $rec->titoloMateriale }}
                        </a>

                        <!-- Componente Star Rating -->
                        <div class="review-card__rating">
                            <x-star-rating :media="$rec->voto ?? 0">
                                <span class="review-card__rating-value">{{ round($rec->voto ?? 0) }}/5</span>
                            </x-star-rating>
                        </div>
                    </header>

                    <p class="review-card__comment">{{ $rec->commento }}</p>

                    <!-- ===================== AZIONI CARD ===================== -->
                    <footer class="review-card__footer">
                        {{-- Presumo che la rotta prenda l'ID del materiale per capire quale recensione dell'utente eliminare --}}
                        <form action="{{ route('studyroom.materiale.elimina-recensione', $rec->idRecensione) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa recensione?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-review" aria-label="Elimina recensione">
                                <i class="fa fa-trash"></i> Elimina
                            </button>
                        </form>
                    </footer>

                </article>
            @endforeach

        </div>

        <!-- ===================== PAGINAZIONE ===================== -->
        <div style="margin-top: 2.5rem;">
            {{ $recensioni->links('components.pagination') }}
        </div>

    @else

        <!-- STATO VUOTO -->
        <div class="reviews-empty">
            <div class="reviews-empty__icon">
                <i class="fa fa-star"></i>
            </div>
            <p class="reviews-empty__title">Non hai ancora scritto recensioni</p>
            <span class="reviews-empty__text">
                Scarica un materiale e lascia la tua valutazione:
                le recensioni che scrivi compariranno qui.
            </span>
            <a href="{{ route('studyroom.materiali.popolari') }}" class="reviews-empty__btn">
                <i class="fa fa-compass"></i> Esplora i materiali
            </a>
        </div>

    @endif

</section>

@endsection