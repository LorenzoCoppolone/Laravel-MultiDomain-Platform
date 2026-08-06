@extends('studyroom.layouts.layout')

@section('title', 'Assistenza — StudyRoom')

@section('pageCSS')
    @vite('resources/css/studyroom/styleAssistenza.css')
@endsection

@section('content')
<div class="supporto-container">
    <div class="supporto-card">
        
        <!-- Icona -->
        <div class="supporto-icon">
            <i class="fa fa-envelope-open-text"></i>
        </div>
        
        <h1>Come possiamo aiutarti?</h1>
        
        <p>
            Hai riscontrato un problema tecnico, hai bisogno di informazioni sul tuo account o vuoi segnalare un comportamento scorretto? 
            Il nostro team è a tua completa disposizione.
        </p>

        <!-- Riquadro Email -->
        <div class="email-box">
            <i class="fa fa-paper-plane"></i>
            <!-- Placeholder della mail va cambiato successivamente -->
            <a href="mailto:assistenza@studyroom.it" class="email-address">assistenza@studyroom.it</a>
        </div>

        <p style="font-size: 14px; color: #999; margin-bottom: 30px;">
            Cerchiamo di rispondere a tutte le richieste entro 24/48 ore lavorative.
        </p>

        <!-- Bottone per aprire il client di posta -->
        <a href="mailto:assistenza@studyroom.it?subject=Richiesta%20di%20Assistenza%20StudyRoom" class="btn-primary">
            Scrivici un'email
        </a>
        
    </div>
</div>
@endsection