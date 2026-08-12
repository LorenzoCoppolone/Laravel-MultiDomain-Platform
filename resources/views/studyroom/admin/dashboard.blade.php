<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | StudyRoom</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Rajdhani:wght@700&family=Exo+2:wght@700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS di Laravel tramite Vite -->
    @vite([
        'resources/css/studyroom/styleAdmin.css',
        'resources/css/components/pagination.css',
        'resources/css/components/alert.css'
    ])

    @if(session('error') || isset($errore))
        <x-alert type="danger" :message="session('error') ?? $errore" />
    @endif
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    
</head>
<body>

    <!-- ===================== HEADER ADMIN ===================== -->
     <header class="admin-header">
        <a href="{{ route('studyroom.admin.dashboard') }}" class="logo">StudyRoom</a>

        <form method="POST" action="{{ route('studyroom.logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-logout" style="background: transparent; border: none; font: inherit; cursor: pointer; color: inherit;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </header>
    <!-- ===================== /HEADER ADMIN ===================== -->


    <!-- ===================== MAIN DASHBOARD ===================== -->
    <main class="admin-dashboard">
        <h1 class="page-title">Dashboard Admin</h1>

        <!-- Tabella segnalazioni -->
        <div class="table-card">
            <div class="table-header">
                <span>Titolo</span>
                <span>N° Segnalazioni</span>
                <span>Azione</span>
            </div>

            {{-- @forelse è la magia di Blade che unisce foreach e if/else (empty) --}}
            @forelse ($segnalazioni as $s)
                <div class="table-row">
                    <span class="titolo-cell">{{ $s->titoloMateriale }}</span>
                    
                    <span>
                        <span class="badge">
                             {{ $s->numeroSegnalazioni }}
                        </span>
                    </span>
                    
                    <span>
                        {{-- Usiamo la rotta Laravel nominata --}}
                        <a href="{{ route('studyroom.admin.gestisci-segnalazione', $s->idMateriale) }}" class="btn-gestisci">
                            Gestisci
                        </a>
                    </span>
                </div>
            @empty
                <div class="empty">Nessuna segnalazione presente.</div>
            @endforelse
        </div>
    </main>
    <!-- ===================== /MAIN DASHBOARD ===================== -->


    <!-- ===================== PAGINAZIONE ===================== -->
    <div style="margin-top: 2rem;">
        {{ $segnalazioni->withQueryString()->links('components.pagination') }}
    </div>
    <!-- ===================== /PAGINAZIONE ===================== -->

</body>
</html>