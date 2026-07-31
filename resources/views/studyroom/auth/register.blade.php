<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione | StudyRoom</title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/studyroom_favicon.ico') }}">

    <!-- CSS gestito tramite Vite (assicurati di aggiungerlo in vite.config.js) -->
    <!-- Carichiamo il CSS del componente E il CSS della pagina -->
    @vite([
        'resources/css/components/form-input.css',
        'resources/css/studyroom/styleForm.css',
        'resources/js/studyroom/validazione.js'
        ])
    <!-- Icone -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <main>
        <div class="logo">
            <a href="{{ route('studyroom.home') }}">
                <p>StudyRoom</p>
            </a>
        </div>

        <div class="form-login-container">

            <form action="{{ route('studyroom.register') }}" method="POST" id="formRegistrazione">
                <!-- TOKEN CSRF OBBLIGATORIO IN LARAVEL -->
                @csrf 
                
                <h2>Registrati</h2>

                <!-- Errore globale (es. status personalizzato dalla sessione) -->
                @if (session('status'))
                    <span class="msg-errore">{{ session('status') }}</span>
                @endif

                <!-- Nome -->
                <x-form-input 
                    name="nome" 
                    placeholder="Nome" 
                    icon="bx bx-user" 
                    pattern="[a-zA-ZÀ-ÿ\s'\-]+" 
                    title="Solo lettere, nessun numero" 
                    required="true" 
                />

                <!-- Cognome -->
                <x-form-input 
                    name="cognome" 
                    placeholder="Cognome" 
                    icon="bx bx-badge" 
                    pattern="[a-zA-ZÀ-ÿ\s'\-]+" 
                    title="Solo lettere, nessun numero" 
                    required="true" 
                />

                <!-- Username -->
                <x-form-input 
                    name="username" 
                    placeholder="Username" 
                    icon="bx bx-at" 
                    pattern="[a-zA-Z0-9_]+" 
                    title="Solo lettere, numeri e _ (no spazi)" 
                    required="true" 
                />

                <!-- Email -->
                <x-form-input 
                    type="email"
                    name="email" 
                    placeholder="Email" 
                    icon="bx bx-envelope" 
                    pattern="^[a-zA-Z0-9._%+\-]+@(student\.univaq\.it|univaq\.it)$" 
                    title="Usa la tua email universitaria (@student.univaq.it)" 
                    required="true" 
                />

                <!-- Password (mantenuta estesa per via del JS custom togglePassword) -->
                <div class="campo-input">
                    <input type="password" placeholder="Password" name="password" id="password" minlength="8" title="Minimo 8 caratteri" required>
                    <i class="bx bx-show toggle-password" id="togglePassword"></i>
                </div>
                @error('password')<span class="msg-errore">{{ $message }}</span>@enderror

                <!-- Conferma Password (Nome modificato in password_confirmation per Laravel) -->
                <div class="campo-input">
                    <input type="password" placeholder="Conferma Password" name="password_confirmation" id="confermaPassword" required>
                    <i class="bx bx-show toggle-password" id="toggleConferma"></i>
                </div>
                <span class="msg-errore" id="err-conferma"></span>
                @error('password_confirmation')<span class="msg-errore">{{ $message }}</span>@enderror

                <button class="btn" type="submit">Registrati</button>

                <div class="registrazione">
                    <p>Hai già un account?
                        <a href="{{ route('studyroom.login') }}">Accedi</a>
                    </p>
                </div>
            </form>
        </div>
    </main>

    <!-- Script JavaScript posizionato nella cartella public/js/ -->
    <script src="{{ asset('js/validazione.js') }}"></script>
</body>
</html>