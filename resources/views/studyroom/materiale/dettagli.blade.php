@extends('studyroom.layouts.layout')

@section('title', ($materiale->titoloMateriale ?? 'Materiale') . ' — StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/popup.css',
        'resources/css/studyroom/styleDettagliMateriale.css',
        'resources/css/components/star-rating.css',
        'resources/js/components/popup.js',
        'resources/js/studyroom/ajax.js'
    ])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@endsection

@section('content')

{{-- Toast di esito (successo/errore) tramite flash message in sessione --}}
@if (session('success') || session('error'))
    <div id="popup-preferiti" class="popup {{ session('success') ? 'popup-successo' : 'popup-errore' }}">
        <span class="popup-messaggio">{{ session('success') ?? session('error') }}</span>
    </div>
@endif

<section class="materiale-page">

    <div class="materiale-layout">

        <!-- ===================== CONTENUTO PDF ===================== -->
        <div class="materiale-viewer" id="pdf-container" data-stream-url="{{ route('studyroom.materiale.stream', $materiale->idMateriale) }}">
            @if (!$materiale)
                <div class="materiale-viewer__empty">
                    <i class="fa fa-file-pdf"></i>
                    <p>Anteprima non disponibile</p>
                </div>
            @endif
        </div>
        <!-- ===================== /CONTENUTO PDF ===================== -->


        <!-- ===================== INFO + AZIONI ===================== -->
        <aside class="materiale-info">

            <h1 class="materiale-info__title">
                {{ $materiale->titoloMateriale }}
            </h1>

            <span class="materiale-info__tipo">{{ $materiale->tipologia }}</span>

            <!-- Caricato da -->
            <p class="materiale-info__author">
                <i class="fa fa-circle-user"></i>
                Caricato da <strong>{{ $materiale->nome_studente ?? 'Utente sconosciuto' }}</strong>
            </p>

            <!-- Metadati -->
            <ul class="materiale-meta">
                <li>
                    <i class="fa fa-book"></i>
                    <span class="materiale-meta__label">Insegnamento</span>
                    <span class="materiale-meta__value">{{ $materiale->insegnamento }}</span>
                </li>
                <li>
                    <i class="fa fa-graduation-cap"></i>
                    <span class="materiale-meta__label">Corso di Laurea</span>
                    <span class="materiale-meta__value">{{ $materiale->corso_di_laurea }}</span>
                </li>
            </ul>

            <!-- Valutazione + download -->
            <div class="materiale-stats">
                
                {{-- Usiamo il componente riutilizzabile creato prima --}}
                <x-star-rating :media="$materiale->mediaValutazione ?? 0">
                    <a class="materiale-rating__count" href="{{ route('studyroom.materiale.recensioni', $materiale->idMateriale) }}">
                        ({{ $materiale->numeroRecensioni ?? 0 }} recensioni)
                    </a>
                </x-star-rating>

                <div class="materiale-downloads" aria-label="{{ $materiale->numeroDownload ?? 0 }} download">
                    <i class="fa fa-download"></i>
                    <span>{{ $materiale->numeroDownload ?? 0 }} download</span>
                </div>
            </div>

            <!-- ===================== AZIONI ===================== -->
            <div class="materiale-actions">

                <!-- Scarica (Trasformato in un link <a> con lo stile del bottone, niente JS necessario!) -->
                <a href="{{ route('studyroom.materiale.download', $materiale->idMateriale) }}" class="btn-azione btn-azione--primary">
                    <i class="fa fa-download"></i> Scarica
                </a>

                <!-- Preferiti -->
                <form action="{{ route('studyroom.materiale.preferiti') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idMateriale" value="{{ $materiale->idMateriale }}">
                    <button type="submit" class="btn-azione {{ $preferito ? 'btn-azione--active' : '' }}">
                        @if ($preferito)
                            <i class="fa fa-heart"></i> Rimuovi dai preferiti
                        @else
                            <i class="fa fa-heart"></i> Aggiungi ai preferiti
                        @endif
                    </button>
                </form>

                <!-- Recensione (form inline) -->
                <details class="materiale-disclosure">
                    <summary class="btn-azione">
                        <i class="fa fa-star"></i> Lascia una recensione
                    </summary>
                    <form class="materiale-form" action="{{ route('studyroom.materiale.salva-recensione') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idMateriale" value="{{ $materiale->idMateriale }}">

                        <label for="voto">Voto</label>
                        <select id="voto" name="voto" required>
                            <option value="">Seleziona un voto</option>
                            <option value="5">★★★★★ (5)</option>
                            <option value="4">★★★★ (4)</option>
                            <option value="3">★★★ (3)</option>
                            <option value="2">★★ (2)</option>
                            <option value="1">★ (1)</option>
                        </select>

                        <label for="commento">Commento</label>
                        <textarea id="commento" name="commento" rows="3" maxlength="255"
                                  placeholder="Scrivi un commento (max 255 caratteri)…"></textarea>

                        <button type="submit" class="btn-azione btn-azione--primary">
                            Invia recensione
                        </button>
                    </form>
                </details>

                <!-- Segnalazione (form inline) -->
                <details class="materiale-disclosure">
                    <summary class="btn-azione btn-azione--danger">
                        <i class="fa fa-flag"></i> Segnala materiale
                    </summary>
                    <form class="materiale-form" action="{{ route('studyroom.materiale.aggiungi-segnalazione') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idMateriale" value="{{ $materiale->idMateriale }}">

                        <label for="motivo">Motivo della segnalazione</label>
                        <textarea id="motivo" name="motivo" rows="3" maxlength="255" required
                                  placeholder="Descrivi il problema (max 255 caratteri)…"></textarea>

                        <button type="submit" class="btn-azione btn-azione--danger">
                            Invia segnalazione
                        </button>
                    </form>
                </details>

            </div>
            <!-- ===================== /AZIONI ===================== -->

        </aside>
        <!-- ===================== /INFO + AZIONI ===================== -->

    </div>

</section>
@endsection