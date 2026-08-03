@extends('studyroom.layouts.layout')

@section('title', 'Modifica Profilo | StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/profile-input.css',
        'resources/css/components/profile-photo-upload.css',
        'resources/css/components/btn-pill.css', 
        'resources/css/studyroom/styleModificaProfilo.css',
        'resources/js/studyroom/modificaProfilo.js'
    ])
@endsection

@section('content')

@php
    // Recupero l'utente loggato per passarlo ai campi
    $utente = Auth::guard('studente')->user();
@endphp

<section class="edit-card">

    <h1 class="edit-title"><i class="fa fa-pen"></i> Modifica profilo</h1>

    <!-- Avvisi di sistema (es. aggiornamento completato o errore globale) -->
    @if (session('status'))
        <p class="edit-error" style="border-color: #4ade80; color: #166534; background: #f0fdf4;">
            {{ session('status') }}
        </p>
    @endif

    @if (session('error'))
        <p class="edit-error">{{ session('error') }}</p>
    @endif

    <form action="{{ route('studyroom.profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form" id="formModificaProfilo">
        
        <!-- Direttive di sicurezza e metodo Laravel -->
        @csrf
        @method('PATCH')

        <!-- FOTO PROFILO (Componente) -->
      <x-profile-photo-upload 
    :contenuto="$utente->immagine_profilo" 
    :mimetype="$utente->immagine_profilo_mimeType" 
/>

        <!-- NOME (Componente) -->
        <x-profile-input 
            id="nome" 
            name="nome" 
            label="Nome" 
            icon="fa fa-user" 
            :value="$utente->nome"
            :placeholder="$utente->nome"
            pattern="[a-zA-ZÀ-ÿ\s'\-]+" 
            title="Solo lettere, nessun numero" 
            required="true" 
        />

        <!-- COGNOME (Componente) -->
        <x-profile-input 
            id="cognome" 
            name="cognome" 
            label="Cognome" 
            icon="fa fa-id-badge" 
            :value="$utente->cognome"
            :placeholder="$utente->cognome"
            pattern="[a-zA-ZÀ-ÿ\s'\-]+" 
            title="Solo lettere, nessun numero" 
            required="true" 
        />

        <!-- CAMPO EMAIL (Componente) -->
        <x-profile-input 
            id="email" 
            name="email" 
            label="Email" 
            icon="fa fa-envelope" 
            :value="$utente->email"
            :placeholder="$utente->email"
            required="true" 
        />

        <!-- USERNAME (Componente) -->
        <x-profile-input 
            id="username" 
            name="username" 
            label="Username" 
            icon="fa fa-at" 
            :value="$utente->username"
            :placeholder="$utente->username"
            pattern="[a-zA-Z0-9_]+" 
            title="Solo lettere, numeri e _ (no spazi)" 
            required="true" 
        />

        <!-- AZIONI -->
        <div class="edit-actions">
            <!-- Pulsante Annulla -->
            <x-btn-pill href="{{ route('studyroom.profile.index') }}" icon="fa fa-xmark" type="secondary">
                Annulla
            </x-btn-pill>
            
            <!-- Pulsante Salva -->
            <x-btn-pill icon="fa fa-floppy-disk" type="primary">
                Salva modifiche
            </x-btn-pill>
        </div>

    </form>

</section>

@endsection