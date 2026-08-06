@extends('studyroom.layouts.layout')

@section('title', 'Accesso Negato — StudyRoom')

@section('pageCSS')

    @vite('resources/css/studyroom/styleBanned.css')

@endsection

@section('content')
<div class="banned-container">
    <div class="banned-card">
        <!-- Icona del divieto -->
        <i class="fa fa-ban"></i>
        
        <h1>Account Sospeso</h1>
        
        <!-- Stampiamo dinamicamente il messaggio passato dalla funzione abort() nel middleware -->
        <p>
            {{'Non hai i permessi necessari per eseguire questa azione o accedere a questa pagina.' }}
        </p>
        
        <div class="banned-actions">
            <!-- Usa la rotta corretta per la tua homepage -->
            <a href="{{ route('studyroom.home') }}" class="btn-outline">Torna alla Home</a>
            
            <!-- Link alla rotta 'supporto' che hai definito -->
            <a href="{{ route('studyroom.banned.assistenza') }}" class="btn-primary">Contatta l'Assistenza</a>
        </div>
    </div>
</div>
@endsection