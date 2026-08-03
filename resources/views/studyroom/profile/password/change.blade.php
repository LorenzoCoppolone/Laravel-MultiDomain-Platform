<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimposta Password • StudyRoom</title>

    <!-- Icone Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS e JS gestiti tramite Vite -->
    @vite([
        'resources/css/studyroom/styleForm.css',
        'resources/js/studyroom/validazione.js',
        'resources/css/components/form-input.css'
    ])
</head>

<body>

<main>

    <!-- LOGO -->
    <a href="{{ route('studyroom.dashboard') }}" class="logo">StudyRoom</a>

    <!-- BOX FORM -->
    <div class="form-login-container">

        <h2>Reimposta Password</h2>

        <form method="POST" action="{{ route('studyroom.password.store') }}">
            @csrf
            @method('PUT')

            <!-- Token di sicurezza -->
            @isset($token)
                <input type="hidden" name="token" value="{{ $token }}">
            @endisset

          <!-- PASSWORD CORRENTE -->
            <x-form-input 
                type="password" 
                name="current_password" 
                id="currentPassword" 
                placeholder="Password corrente" 
                icon="bx bx-lock-alt toggle-password"
                toggleId="toggleCurrent" 
                required 
            />
            @error('current_password')
                <span class="msg-errore">{{ $message }}</span>
            @enderror

            <!-- NUOVA PASSWORD -->
            <x-form-input 
                type="password" 
                name="password" 
                id="password" 
                placeholder="Nuova password" 
                icon="bx bx-show toggle-password"
                toggleId="togglePassword" 
                required 
            />
            @error('password')
                <span class="msg-errore">{{ $message }}</span>
            @enderror

            <!-- CONFERMA PASSWORD -->
            <x-form-input 
                type="password" 
                name="password_confirmation" 
                id="confermaPassword" 
                placeholder="Conferma Password" 
                icon="bx bx-show toggle-password"
                toggleId="toggleConferma" 
                required 
            />

            <!-- ERRORE GLOBALE -->
            @if(session('error') || isset($errore))
                <span class="msg-errore" style="text-align: center; margin-bottom: 15px;">
                    {{ session('error') ?? $errore }}
                </span>
            @endif

            <!-- BOTTONE -->
            <button type="submit" class="btn">Reimposta</button>

        </form>

        <!-- LINK PASSWORD DIMENTICATA -->
        <p class="registrazione" style="margin-top: 20px;">
            <a href="{{ route('studyroom.password.request') }}">Hai dimenticato la tua password?</a>
        </p>

        <!-- LINK HOME -->
        <p class="registrazione" style="margin-top: 8px;">
            Torna alla <a href="{{ route('studyroom.dashboard') }}">Home</a>
        </p>

    </div>

</main>
</body>
</html>