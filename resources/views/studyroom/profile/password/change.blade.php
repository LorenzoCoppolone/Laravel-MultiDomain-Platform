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
    <a href="{{ route('studyroom.home') }}" class="logo">StudyRoom</a>

   <!-- BOX FORM -->
    <div class="form-login-container">

        <h2>Reimposta Password</h2>

    <form method="POST" action="{{ route('studyroom.profile.password.update') }}">
    @csrf

    <!-- PASSWORD CORRENTE -->
    <div class="campo-input">
        <input type="password" placeholder="Password Attuale" name="current_password" id="current_password" required>
        <i class="bx bx-show toggle-password"></i>
    </div>
    @error('current_password', 'updatePassword')
        <span class="msg-errore">{{ $message }}</span>
    @enderror
<!-- NUOVA PASSWORD -->
            <div class="campo-input">
                <input type="password" placeholder="Nuova Password" name="password" id="password" required>
                <i class="bx bx-show toggle-password"></i>
            </div>
            @error('password', 'updatePassword')
                <span class="msg-errore">{{ $message }}</span>
            @enderror

            <!-- CONFERMA PASSWORD -->
            <div class="campo-input">
                <!-- Il 'name' serve a Laravel, l' 'id' serve al nostro file JS -->
                <input type="password" placeholder="Conferma Password" name="password_confirmation" id="confermaPassword" required>
                <i class="bx bx-show toggle-password"></i>
            </div>
            
            <!-- QUI APPARE IL MESSAGGIO JS (COINCIDONO/NON COINCIDONO) -->
            <span id="err-conferma" class="msg-errore" style="display:block; text-align:center; font-size:0.9rem; margin-top:5px; margin-bottom:10px;"></span>
    <!-- ERRORE GLOBALE -->
    @if(session('status') === 'password-updated')
        <span class="msg-successo" style="text-align: center; margin-bottom: 15px; color: green; display:block;">
            Password aggiornata con successo!
        </span>
    @else
        <span class="msg-errore" style="text-align: center; margin-bottom: 15px; color: red; display:block;">
            Password non aggiornata, ricorda che la password non può essere uguale a quella corrente!
        </span>
    @endif

    <!-- BOTTONE -->
    <button type="submit" class="btn">Reimposta</button>

    </form>
        <!-- LINK HOME -->
        <p class="registrazione" style="margin-top: 8px;">
            Torna alla <a href="{{ route('studyroom.home') }}">Home</a>
        </p>

    </div>

</main>
</body>
</html>