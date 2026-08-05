<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupero Password • StudyRoom</title>

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

        <h2>Recupero Password</h2>

        <!-- Testo esplicativo (adattato dal default di Laravel) -->
        <p style="font-size: 14px; color: #555; text-align: center; margin-bottom: 20px; line-height: 1.5;">
            Hai dimenticato la password? Nessun problema. Inserisci il tuo indirizzo email e ti invieremo un link per sceglierne una nuova.
        </p>

        <!-- MESSAGGIO DI SUCCESSO INVIO EMAIL (Sostituisce x-auth-session-status) -->
       <!-- FEEDBACK SUCCESSO -->
        @if (session('status'))
            <div class="alert-box alert-success">
                <i class='bx bx-check-circle'></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('studyroom.password.email') }}">
            @csrf

            <!-- Email -->
            <x-form-input 
                type="email" 
                name="email" 
                id="email" 
                placeholder="Email" 
                icon="bx bx-envelope"
                pattern="^[a-zA-Z0-9._%+\-]+@(student\.univaq\.it|univaq\.it)$" 
                title="Usa la tua email universitaria (@student.univaq.it)"
                required="true" 
            />
            @error('email')
                <span class="msg-errore">{{ $message }}</span>
            @enderror

            <!-- BOTTONE -->
            <button type="submit" class="btn">Invia link di recupero</button>

        </form>

        <!-- LINK RITORNO -->
        <p class="registrazione" style="margin-top: 15px;">
            Torna alla <a href="{{ route('studyroom.login') }}">Login</a>
        </p>

    </div>

</main>

</body>
</html>