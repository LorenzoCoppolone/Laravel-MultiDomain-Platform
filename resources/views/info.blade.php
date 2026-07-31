<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Progetto - Portale Universitario</title>
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- SIDEBAR (uguale alla Home per coerenza) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            App UNIVAQ
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link">Home</a>
            </li>
            
            <li class="nav-item">
                <div class="nav-link">Moduli App &#9662;</div>
                <div class="dropdown-content">
                    <a href="{{ route('studyroom.home') }}" class="dropdown-item">Studyroom</a>
                </div>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('info') }}" class="nav-link" style="background-color: rgb(30 41 59); color: white;">Info Progetto</a>
            </li>
        </ul>
    </aside>

    <!-- AREA PRINCIPALE -->
    <main class="main-content" style="justify-content: flex-start;">
        <div class="info-container">
            
            <div class="info-header">
                <span class="badge">Documentazione Tecnica</span>
                <h1 class="info-main-title">Informazioni sul Progetto</h1>
                <p class="info-lead">
                    Panoramica dell'architettura software e delle scelte implementative adottate per il progetto didattico di Ingegneria Informatica.
                </p>
            </div>

            <!-- Griglia delle specifiche -->
            <div class="info-grid">
                
                <!-- Card 1: Architettura -->
                <div class="info-card">
                    <h3 class="info-card-title">Monolite Modulare</h3>
                    <p class="info-card-text">
                        Il progetto è suddiviso in moduli (come Studyroom)
                    </p>
                </div>

                <!-- Card 2: Obiettivo Didattico -->
                <div class="info-card">
                    <h3 class="info-card-title">Scopo Didattico</h3>
                    <p class="info-card-text">
                        L'obiettivo del progetto e' quello di fornire un'applicazione web per la gestione di un portale modulare.
                        Capace di offrire più servizi per la città dell'Aquila
                    </p>
                </div>

            </div>

            <!-- Pulsante di ritorno -->
            <div class="mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                    &larr; Torna alla Home
                </a>
            </div>

        </div>
    </main>

</body>
</html>