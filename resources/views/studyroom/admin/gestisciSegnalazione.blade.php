<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci Segnalazione | StudyRoom</title>

    <!-- Fonts e Icone -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Rajdhani:wght@700&family=Exo+2:wght@700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS di Laravel tramite Vite -->
    @vite(['resources/css/studyroom/styleAdmin.css',
            'resources/css/components/alert.css'])

    
     <!-- FEEDBACK GLOBALI -->
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
    <main class="admin-dashboard">

        <h1 class="page-title">Gestisci Segnalazione</h1>

        <div class="materiale-layout">

            <!-- ===================== CONTENUTO PDF ===================== -->
            <div class="materiale-viewer">
                @if (isset($materiale) && $materiale->idMateriale)
                    <iframe class="materiale-viewer__frame"
                            src="{{ route('studyroom.materiale.stream', $materiale->idMateriale) }}#toolbar=0&navpanes=0&scrollbar=0"
                            title="Contenuto del materiale"></iframe>
                @else
                    <div class="materiale-viewer__empty">
                        <i class="fa fa-file-pdf"></i>
                        <p>Anteprima non disponibile</p>
                    </div>
                @endif
            </div>
            <!-- ===================== /CONTENUTO PDF ===================== -->


            <!-- ===================== INFO + AZIONI ===================== -->
            <aside class="materiale-info">

                <h1 class="materiale-info__title">{{ $materiale->titolo }}</h1>

                <span class="materiale-info__tipo">Materiale segnalato</span>

                <!-- Caricato da -->
                <p class="materiale-info__author">
                    <i class="fa fa-circle-user"></i>
                    Caricato da <strong>{{ $utente->username }}</strong>
                </p>

                <!-- Dati utente -->
                <ul class="materiale-meta">
                    <li>
                        <i class="fa fa-id-card"></i>
                        <span class="materiale-meta__label">Nome</span>
                        <span class="materiale-meta__value">{{ $utente->nome }} {{ $utente->cognome }}</span>
                    </li>
                    <li>
                        <i class="fa fa-at"></i>
                        <span class="materiale-meta__label">Username</span>
                        <span class="materiale-meta__value">{{ $utente->username }}</span>
                    </li>
                    <li>
                        <i class="fa fa-envelope"></i>
                        <span class="materiale-meta__label">Email</span>
                        <span class="materiale-meta__value">{{ $utente->email }}</span>
                    </li>
                </ul>

                <!-- ===================== AZIONI ===================== -->
                <div class="materiale-actions">

                    <!-- NUOVO BOTTONE: Visualizza motivi segnalazione -->
                    <a href="{{ route('studyroom.admin.segnalazioni.motivi', $materiale->idMateriale) }}" class="btn-azione" style="margin-bottom: 0.5rem;">
                        <i class="fa fa-list"></i> Visualizza motivi
                    </a>

                    <!-- Accetta: archivia le segnalazioni, il materiale rimane -->
                    <form method="POST" action="{{ route('studyroom.admin.segnalazioni.annulla', $materiale->idMateriale) }}">
                        @csrf
                        <button type="submit" class="btn-azione btn-azione--primary"
                                onclick="return confirm('Accettare la segnalazione archivierà tutte le segnalazioni collegate. Continuare?')">
                            <i class="fa fa-check"></i> Annulla segnalazioni
                        </button>
                    </form>

                    <!-- Rifiuta: elimina il materiale e le segnalazioni -->
                    <form method="POST" action="{{ route('studyroom.admin.materiale.elimina', $materiale->idMateriale) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-azione"
                                onclick="return confirm('Questo eliminerà il materiale e tutte le segnalazioni ad esso associate. Continuare?')">
                            <i class="fa fa-trash"></i> Rimuovi materiale
                        </button>
                    </form>

                    <!-- Banna utente -->
                    <form method="POST" action="{{ route('studyroom.admin.utente.banna-utente', $utente->id) }}">
                        @csrf
                        <button type="submit" class="btn-azione btn-azione--danger"
                                onclick="return confirm('Sei sicuro di voler bannare questo utente?')">
                            <i class="fa fa-ban"></i> Banna utente
                        </button>
                    </form>

                </div>
                <!-- ===================== /AZIONI ===================== -->

            </aside>
            <!-- ===================== /INFO + AZIONI ===================== -->

        </div>

    </main>

</body>
</html>