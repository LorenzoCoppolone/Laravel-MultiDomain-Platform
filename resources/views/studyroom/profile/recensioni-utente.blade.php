@extends('studyroom.layouts.layout')

@section('title', 'Le mie recensioni — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/star-rating.css', 
        'resources/css/components/pagination.css',  
        'resources/css/studyroom/styleRecensioniUtente.css', 
        'resources/css/components/popup.css',
        'resources/js/components/popup.js'
    ])
@endsection

@section('content')

{{-- Toast di esito (successo/errore) tramite flash message in sessione --}}
@if (session('success') || session('error'))
    <div id="popup-preferiti" class="popup {{ session('success') ? 'popup-successo' : 'popup-errore' }}">
        <span class="popup-messaggio">{{ session('success') ?? session('error') }}</span>
    </div>
@endif

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

                        <!-- 1. MODIFICA RECENSIONE (Tramite Disclosure / Slide-down in-line) -->
                        <details class="materiale-disclosure" style="display:inline-block; width:100%;">
                            <summary class="btn-azione" style="cursor: pointer; list-style: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <i class="fa fa-pen"></i> Modifica
                            </summary>
                            
                            <form class="materiale-form" action="{{ route('studyroom.materiale.modifica-recensione', $rec->idRecensione) }}" method="POST" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--color-border);">
                                @csrf
                                @method('PUT')

                                <label for="voto-{{ $rec->idRecensione }}">Nuovo Voto</label>
                                <select id="voto-{{ $rec->idRecensione }}" name="voto" required style="width: 100%; margin-bottom: 0.75rem; padding: 0.5rem;">
                                    <option value="5" {{ $rec->voto == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
                                    <option value="4" {{ $rec->voto == 4 ? 'selected' : '' }}>★★★★ (4)</option>
                                    <option value="3" {{ $rec->voto == 3 ? 'selected' : '' }}>★★★ (3)</option>
                                    <option value="2" {{ $rec->voto == 2 ? 'selected' : '' }}>★★ (2)</option>
                                    <option value="1" {{ $rec->voto == 1 ? 'selected' : '' }}>★ (1)</option>
                                </select>

                                <label for="commento-{{ $rec->idRecensione }}">Nuovo Commento</label>
                                <textarea id="commento-{{ $rec->idRecensione }}" name="commento" rows="3" maxlength="255"
                                          placeholder="Modifica il tuo commento (max 255 caratteri)…" style="width: 100%; margin-bottom: 0.75rem; padding: 0.5rem;">{{ $rec->commento }}</textarea>

                                <button type="submit" class="btn-azione btn-azione--primary" style="padding: 0.5rem 1rem;">
                                    Salva modifiche
                                </button>
                            </form>
                        </details>

                        <!-- 2. ELIMINA RECENSIONE -->
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