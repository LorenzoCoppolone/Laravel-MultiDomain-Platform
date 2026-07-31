@extends('studyroom.layouts.layout')

@section('title')
    Home | StudyRoom
@endsection

@section('pageCSS')
    @vite(['resources/css/studyroom/styleHome.css'])
@endsection

@section('content')

<section class="section-search">

    <h1 class="section-title">Che esame stai preparando?</h1>
    <p class="section-subtitle">Non studiare da solo: usa gli appunti della community</p>

    <!-- Richiamo Componente Search Form (Versione Home) -->
    <x-search-form 
        action="{{ url('/studyroom/cerca') }}" 
        placeholder="Inizia a cercare" 
        formClass="search-box" 
        buttonClass="btn-search-main" 
    />

</section>

<!-- SEZIONE UPLOAD -->
<section class="section-upload">
    <div class="upload-text">
        <h2 class="upload-title">Condividi il tuo materiale</h2>
        <p class="upload-subtitle">Entra a far parte della community</p>
    </div>

    <a href="{{ url('/studyroom/carica') }}" class="upload-box">
        <i class="fa fa-cloud-arrow-up upload-icon"></i>
        <p class="upload-label"><strong>Carica File</strong></p>
        <p class="upload-hint">(Appunti, Esami passati, esercizi, ecc)</p>
    </a>
</section>

@endsection