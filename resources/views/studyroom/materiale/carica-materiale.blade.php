@extends('studyroom.layouts.layout')

@section('title', 'Carica Materiale - StudyRoom')

@section('pageCSS')
    <!-- Carico il CSS principale e i vari componenti tramite Vite -->
    @vite([
        'resources/css/components/alert.css',
        'resources/css/components/file-upload.css',
        'resources/css/components/combo-box.css',
        'resources/css/components/checkbox.css',
        'resources/css/components/hero-search.css',
        'resources/css/components/search-form.css',
        'resources/css/components/material-filters.css',
        'resources/css/studyroom/styleCarica.css',
        'resources/css/studyroom/styleRicercaMateriale.css',
        'resources/js/studyroom/upload.js'
    ])
@endsection

@section('content')

<section class="upload-card">

    <h1 class="upload-card-title"><i class="fa fa-cloud-arrow-up"></i> Carica materiale</h1>

    <!-- FEEDBACK GLOBALI -->
    @if(session('error') || isset($errore))
        <x-alert type="danger" :message="session('error') ?? $errore" />
    @endif
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <!-- TIPO DOCUMENTO -->
    <div class="type-selector">
        <button type="button" id="btnAppunto" class="type-btn {{ old('tipo', $tipo ?? 'appunto') != 'esame' ? 'active' : '' }}">
            <i class="fa fa-pen-to-square"></i> Appunto
        </button>
        <button type="button" id="btnEsame" class="type-btn {{ old('tipo', $tipo ?? 'appunto') == 'esame' ? 'active' : '' }}">
            <i class="fa fa-graduation-cap"></i> Esame
        </button>
    </div>

    <!-- FORM PRINCIPALE -->
    <form method="POST" enctype="multipart/form-data" action="{{ route('studyroom.materiali.salva') }}" class="upload-form">
        @csrf
        <input type="hidden" name="tipo" id="tipoInput" value="{{ old('tipo', $tipo ?? 'appunto') }}">

        <!-- FILE UPLOAD COMPONENT -->
        <x-file-upload name="file" required maxSize="10MB" accept="application/pdf" reqiired/>
         @error('file') <div class="error-msg">{{ "Inserisci un file" }}</div> @enderror
         
        <!-- COMBOBOX: CORSO DI LAUREA -->
        <x-combo-box 
            id="cdlCombo" inputId="cdlInput" name="cdl" hiddenId="cdlValue" listId="cdlList"
            label="Corso di Laurea" placeholder="Scrivi il tuo corso di laurea..."
            :value="$selectedCdlNome ?? ''" :hiddenValue="$selectedCdl ?? ''" required>
            
            @foreach($corsi as $c)
                {{-- Usiamo codice_corso come data-value poiché è la chiave primaria del corso --}}
                <li class="combo-item" role="option" data-value="{{ $c->codice_corso }}" data-label="{{ $c->nome_corso }}">{{ $c->nome_corso }}</li>
            @endforeach
        @error('cdl')  <div class="error-msg">{{ "Il corso di laurea non puo' essere vuoto" }}</div> @enderror
        </x-combo-box>

        <!-- COMBOBOX: INSEGNAMENTO -->
        <x-combo-box 
            id="insCombo" inputId="insInput" name="insegnamento" hiddenId="insValue" listId="insList"
            label="Insegnamento" placeholder="Seleziona prima un corso di laurea"
            lockedPlaceholder="Seleziona prima un corso di laurea" readyPlaceholder="Scrivi l'insegnamento..."
            :value="$selectedInsNome ?? ''" :hiddenValue="$selectedIns ?? ''" required >
            
            @foreach($insegnamenti as $i)
                {{-- Usiamo corso_di_laurea_codice per abbinarlo correttamente al codice_corso del corso --}}
                <li class="combo-item" role="option" data-value="{{ $i->id }}" data-cdl="{{ $i->corso_di_laurea_codice }}" data-label="{{ $i->nome_insegnamento }}">{{ $i->nome_insegnamento }}</li>
            @endforeach
        @error('insegnamento')  <div class="error-msg">{{ "L'insegnamento non puo' essere vuoto" }}</div> @enderror
        </x-combo-box>

        <!-- TITOLO -->
        <div class="form-group">
            <label for="titoloInput">Titolo</label>
            <input type="text" name="titolo" id="titoloInput" required placeholder="es. Programmazione Web" value="{{ old('titolo', $titolo ?? '') }}">
            @error('titolo') <div class="error-msg">{{ "il titolo non puo' essere vuoto" }}</div> @enderror
        </div>

        <!-- TAG -->
        <div class="form-group" id="tagGroup">
            <label for="tagSelect" >Tag</label>
            <select name="tag" id="tagSelect" required {{ old('tipo', $tipo ?? 'appunto') == 'esame' ? 'disabled' : '' }}>
                <option value="">Seleziona tipo</option>
                <option value="Riassunto" {{ old('tag') == 'Riassunto' ? 'selected' : '' }}>Riassunto</option>
                <option value="Note" {{ old('tag') == 'Note' ? 'selected' : '' }}>Note</option>
                <option value="Esercizi" {{ old('tag') == 'Esercizi' ? 'selected' : '' }}>Esercizi</option>
            </select>
            @error('tag') <div class="error-msg">{{ "il tag non puo' essere vuoto" }}</div> @enderror
        </div>

        <!-- CHECKBOX COMPONENT -->
        <x-checkbox name="terms" id="termsCheck" label="Accetto i Termini e Condizioni" required/>
        @error('terms') <div class="error-msg">{{ "Devi accettare i termini e le condizioni" }}</div> @enderror

        <!-- BOTTONI -->
        <div class="upload-actions">
            <a href="{{ route('studyroom.home') }}" class="btn btn-secondary btn-home"><i class="fa fa-arrow-left"></i> Home</a>
            <button type="submit" class="btn btn-carica">
                <i class="fa fa-cloud-arrow-up"></i> Carica materiale
            </button>
        </div>

    </form>

</section>
@endsection