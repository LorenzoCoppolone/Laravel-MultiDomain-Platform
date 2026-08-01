@extends('studyroom.layouts.layout')

@section('title', 'Profilo | StudyRoom')

@section('pageCSS')
    @vite([
        'resources/css/components/profile-header.css',
        'resources/css/components/btn-pill.css',
        'resources/css/studyroom/styleProfiloUtente.css'
    ])
@endsection

@section('content')

    <a href="{{ route('studyroom.dashboard') }}" class="back-link">
        <i class="fa fa-arrow-left"></i> Torna alla home
    </a>

    <section class="profile-card">

        <!-- HEADER: Componente -->
        <x-profile-header :utente="Auth::guard('studente')->user()">
            <!-- Bottone Modifica iniettato nello slot -->
            <x-btn-pill 
                href="{{ route('studyroom.profile.edit') }}" 
                icon="fa fa-pen" 
                type="primary" 
                customClass="mt-3">
                Modifica
            </x-btn-pill>
        </x-profile-header>

        <hr class="profile-divider">

        <!-- SEZIONI -->
        <nav class="profile-sections">
            <a href="{{ url('/studyroom/preferiti') }}" class="section-link">
                <i class="fa fa-heart"></i> Preferiti
            </a>
            <a href="{{ url('/studyroom/download') }}" class="section-link">
                <i class="fa fa-download"></i> Scaricati
            </a>
            <a href="{{ url('/studyroom/recensioni') }}" class="section-link">
                <i class="fa fa-star"></i> Mie recensioni
            </a>
            <a href="{{ url('/studyroom/caricati') }}" class="section-link">
                <i class="fa fa-file-arrow-up"></i> Caricati
            </a>
        </nav>

        <hr class="profile-divider">

        <!-- AZIONI ACCOUNT -->
        <div class="profile-account-actions">
            
            <x-btn-pill href="{{ route('studyroom.password.request') }}" icon="fa fa-key" type="secondary">
                Modifica password
            </x-btn-pill>

            <!-- Logout sicuro con metodo POST -->
            <form method="POST" action="{{ route('studyroom.logout') }}" style="width: 100%;">
                @csrf
                <x-btn-pill type="danger" icon="fa fa-right-from-bracket" customClass="w-100">
                    Logout
                </x-btn-pill>
            </form>

        </div>

    </section>

@endsection