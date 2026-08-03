<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/studyroom_favicon.ico') }}">

    <title>@yield('title', 'StudyRoom')</title>

   <!-- CSS Layout e Componenti gestiti da Vite -->
@vite([
    'resources/css/studyroom/styleLayout.css',
    'resources/css/components/search-form.css',
    'resources/css/components/user-avatar.css'
    ])
    <!-- CSS Pagina -->
    @yield('pageCSS')

    <!-- Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Space+Mono:wght@400;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">

        <a href="{{ route('studyroom.home') }}" class="logo">StudyRoom</a>

        <!-- Richiamo Componente Search Form -->
        <x-search-form action="{{ url('/studyroom/cerca') }}" placeholder="Cerca..." formClass="navbar-search" buttonClass="btn-search" />

        <nav class="navbar-links">

            <a href="{{ url('/studyroom/popolari') }}" class="nav-link nav-esami">
                Prepara i tuoi esami
            </a>

            <div class="nav-auth">
                @if(Auth::guard('studente')->check())
                @php
                // Recupero l'utente loggato per passarlo ai campi
                $utente = Auth::guard('studente')->user();
                @endphp

                <!-- Richiamo Componente Avatar -->
                <!-- Se l'utente è loggato, mostra l'avatar -->
               <x-user-avatar 
                    :contenuto="$utente->immagine_profilo" 
                    :mimetype="$utente->immagine_profilo_mimeType" 
                />

                    <!-- USERNAME -->
                    <a href="{{ route('studyroom.profile.index') }}" class="nav-user-name">
                        {{ $utente->username }}
                    </a>

                @else

                    <i class="fa fa-circle-user nav-user-icon"></i>

                    <a href="{{ route('studyroom.login') }}" class="nav-link">Accedi /</a>
                    <a href="{{ route('studyroom.register') }}" class="nav-link">Registrati</a>

                @endif
            </div>

        </nav>
    </header>

    <!-- CONTENUTO PAGINA -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <a href="https://www.univaq.it" target="_blank" class="footer-logo-univaq">
            <img src="{{ asset('img/UnivaqLogo.png') }}" alt="Logo Università degli Studi dell'Aquila">
        </a>

        <div class="footer-center">
            <a href="{{ route('studyroom.home') }}" class="footer-brand">StudyRoom</a>
            <nav class="footer-links">
                <a href="{{ url('/studyroom/chiSiamo') }}">Chi siamo</a>
                <a href="{{ url('/studyroom/supporto') }}">Supporto</a>
                <a href="{{ url('/studyroom/faq') }}">FAQ</a>
                <a href="{{ url('/studyroom/termini') }}">Termini di utilizzo</a>
            </nav>
        </div>
    </footer>

</body>
</html>