<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motivi Segnalazione | StudyRoom Admin</title>

    <!-- Fonts e Icone -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Rajdhani:wght@700&family=Exo+2:wght@700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS (Aggiunto anche il CSS della paginazione) -->
    @vite([
        'resources/css/studyroom/styleAdmin.css',
        'resources/css/studyroom/styleMotivi.css',
        'resources/css/components/pagination.css'
    ])
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

    <main class="admin-dashboard motivi-container">

        <!-- Pulsante Indietro (torna alla gestione dello specifico materiale) -->
        <a href="{{ route('studyroom.admin.gestisci-segnalazione', $materiale->idMateriale) }}" class="back-link">
            <i class="fa fa-arrow-left"></i> Torna alla gestione materiale
        </a>

        <h1 class="page-title" style="margin-bottom: 0.5rem;">Motivi delle Segnalazioni</h1>
        <p class="subtitle">Riferite al materiale: <strong>{{ $materiale->titolo }}</strong></p>

        <!-- ===================== LISTA MOTIVI ===================== -->
        <section class="motivi-list" style="margin-top: 2rem;">

            @forelse ($motivi as $motivo)
                <div class="motivo-card">
                    
                    <!-- Intestazione Card: Username e Data -->
                    <div class="motivo-card__header">
                        <div class="motivo-card__user">
                            <div class="motivo-card__avatar">
                                <i class="fa fa-user"></i>
                            </div>
                            <span class="motivo-card__username">{{ $motivo->username }}</span>
                        </div>
                        
                        <!-- Mostra la data formattata (con controllo di sicurezza) -->
                        @if(!empty($motivo->created_at))
                            <span class="motivo-card__date">
                                {{ \Carbon\Carbon::parse($motivo->created_at)->format('d/m/Y H:i') }}
                            </span>
                        @else
                            <span class="motivo-card__date">Data non disponibile</span>
                        @endif
                    </div>

                    <!-- Corpo Card: Testo del motivo -->
                    <div class="motivo-card__body">
                        <p>{{ $motivo->motivo }}</p>
                    </div>

                </div>
            @empty
                <div class="empty-state">
                    <i class="fa fa-clipboard-check"></i>
                    <p>Nessuna motivazione testuale inserita per questo materiale.</p>
                </div>
            @endforelse
        
            <!-- ===================== PAGINAZIONE ===================== -->
            <!-- Chiuso nel suo div e corrette le parentesi graffe -->
            <div style="margin-top: 2rem;">
                {{ $motivi->withQueryString()->links('components.pagination') }}
            </div>
            
        </section>
        <!-- ===================== /LISTA MOTIVI ===================== -->

    </main>

</body>
</html>