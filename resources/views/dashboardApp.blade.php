<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portale Universitario Modulare</title>
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            App UNIVAQ
        </div>
        
        <ul class="nav-menu">
            
            <!-- Elemento con Menù a tendina -->
            <li class="nav-item">
                <div class="nav-link">Moduli App &#9662;</div>
                <div class="dropdown-content">
                    <!-- Qui richiami le rotte con i nomi prefissati che abbiamo sistemato! -->
                    <a href="{{ route('studyroom.home') }}" class="dropdown-item">Studyroom</a>
                </div>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('info') }}" class="nav-link">Info Progetto</a>
            </li>
        </ul>
    </aside>

    <!-- AREA PRINCIPALE -->
    <main class="main-content">
        <div class="badge">Progetto Didattico Laravel</div>
        <h1 class="hero-title">Benvenuto nel Portale</h1>
        <p class="hero-subtitle">
            Seleziona un modulo dalla barra laterale per accedere ai servizi specifici. 
            Ogni modulo è gestito in modo indipendente con un proprio sistema di autenticazione dedicato.
        </p>
    </main>

</body>
</html>