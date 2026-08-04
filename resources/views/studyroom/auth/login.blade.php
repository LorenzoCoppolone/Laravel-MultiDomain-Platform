<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi | StudyRoom</title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/studyroom_favicon.ico') }}">

    <!-- CSS gestito tramite Vite (riutilizziamo gli stessi della registrazione!) -->
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

            <!-- Rotta corretta verso il controller di login -->
            <form action="{{ route('studyroom.login') }}" method="POST">
                @csrf
                
                <h2>Accedi</h2>

                <!-- Messaggi di stato globali (es. "Password resettata con successo") -->
                @if (session('status'))
                    <div class="alert-box alert-success">
                        <i class='bx bx-check-circle'></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                
                <!-- Errore di autenticazione (credenziali errate) -->
                @error('email')
                    <span class="msg-errore" style="margin-top: 0; margin-bottom: 15px; text-align: center;">
                        {{ $message }}
                    </span>
                @enderror

                <!-- Componente Email (Riutilizzato!) -->
                <x-form-input 
                    type="email"
                    name="email" 
                    placeholder="Email" 
                    icon="bx bx-envelope" 
                    required="true" 
                />

                <!-- Password (Mantenuta in HTML esplicito per l'ID togglePassword del file JS) -->
                <div class="campo-input">
                    <input type="password" placeholder="Password" name="password" id="password" required>
                    <i class="bx bx-show toggle-password" id="togglePassword"></i>
                </div>
                @error('password')
                    <span class="msg-errore">{{ $message }}</span>
                @enderror

                <div class="Ricordami">
                    <label for="controllo">
                        <!-- NOME CAMBIATO IN 'remember' PER LARAVEL -->
                        <input type="checkbox" id="controllo" name="remember"> Ricordami
                    </label>
                    <a href="{{ route('studyroom.password.request') }}">Hai dimenticato la password?</a>
                </div>

                <button class="btn" type="submit">Accedi</button>

                <div class="registrazione">
                    <p>Non hai un account?
                        <a href="{{ route('studyroom.register') }}">Registrati</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
</body>
</html>