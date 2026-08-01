<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica Email | StudyRoom</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/studyroom_favicon.ico') }}">

    <!-- CSS gestito tramite Vite -->
    @vite([
        'resources/css/components/status-icon.css',
        'resources/css/components/alert-box.css',
        'resources/css/studyroom/styleEmailPages.css'
    ])

    <!-- Icone -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="page-container">
        
        <div class="logo">
            <a href="{{ route('studyroom.home') }}">
                <p>StudyRoom</p>
            </a>
        </div>
        
        <div class="card">
            
            <!-- Componente Icona -->
            <x-status-icon type="info" icon="bx bx-mail-send" />
            
            <h2>Controlla la tua email</h2>
            <p>Abbiamo inviato un link di conferma a:</p>
            
            <!-- Recupera dinamicamente l'email dall'utente loggato nel guard specifico -->
            <div class="email-badge">
                {{ Auth::guard('studente')->user()->email }}
            </div>
            
            <hr class="divider">
            
            <!-- Componente Alert -->
            <x-alert-box>
                Controlla anche la cartella <strong>Spam</strong> o <strong>Posta indesiderata</strong>
            </x-alert-box>
            
            <!-- Status di conferma (se appena reinviata) -->
            @if (session('status') == 'verification-link-sent')
                <div style="color: #22c55e; font-size: 13px; font-weight: 600; margin-bottom: 1rem;">
                    Un nuovo link di verifica è stato inviato all'indirizzo email fornito.
                </div>
            @endif

            <div class="resend-row">
                <span>Non hai ricevuto nulla?</span>
                
                <!-- Form POST per il reinvio (Sicurezza Laravel CSRF) -->
                <form method="POST" action="{{ route('studyroom.verification.send') }}">
                    @csrf
                    <button type="submit" class="resend-btn">
                        Invia di nuovo
                    </button>
                </form>
            </div>
            
        </div>
    </div>
    
</body>
</html>