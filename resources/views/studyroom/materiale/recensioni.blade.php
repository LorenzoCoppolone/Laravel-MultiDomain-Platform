@extends('studyroom.layouts.layout')

@section('title', 'Recensioni — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/star-rating.css', /* Richiamo il CSS globale delle stelle */
        'resources/css/components/pagination.css',  /* Richiamo il CSS della paginazione */
        'resources/css/studyroom/styleRecensioniMateriale.css' /* Il CSS specifico di questa pagina */
    ])
@endsection

@section('content')

<section class="reviews-wrapper">

    <!-- Aggiunto il link per tornare indietro (il CSS c'era nel tuo template, ma mancava l'HTML!) -->
    <a href="{{ route('studyroom.materiale.dettagli', $idMateriale) }}" class="back-link">
        <i class="fa fa-arrow-left"></i> Torna al materiale
    </a>

    <h1 class="reviews-title"><i class="fa fa-star"></i> Recensioni</h1>

    @if ($recensioni->count() > 0)

        <div class="reviews-list">

            @foreach ($recensioni as $rec)
                <article class="review-card">

                    <header class="review-card__head">
                        <span class="review-card__material">
                            <i class="fa fa-circle-user"></i>
                            {{ $rec->username ?? 'Utente sconosciuto' }}
                        </span>

                        <!-- Sfruttiamo il componente star-rating passando il voto e usando lo slot per il testo -->
                        <div class="review-card__rating">
                            <x-star-rating :media="$rec->voto ?? 0">
                                <span class="review-card__rating-value">{{ round($rec->voto ?? 0) }}/5</span>
                            </x-star-rating>
                        </div>
                    </header>

                    <p class="review-card__comment">{{ $rec->commento }}</p>

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
            <p class="reviews-empty__title">Questo materiale non ha ancora recensioni</p>
            <span class="reviews-empty__text">
                Sii il primo a lasciare una valutazione: le recensioni compariranno qui.
            </span>
            <a href="{{ route('studyroom.materiale.dettagli', $idMateriale) }}" class="reviews-empty__btn">
                <i class="fa fa-arrow-left"></i> Vai al materiale
            </a>
        </div>

    @endif

</section>

@endsection