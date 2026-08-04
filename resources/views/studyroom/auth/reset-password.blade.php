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

            <!-- Token e Email nascosti (Richiesti dal controller nativo di Laravel) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

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

            
            <!-- SPAN PER IL JS (Deve esserci altrimenti il JS va in crash) -->
            <span id="err-conferma" class="msg-errore" style="text-align: center; display: block; margin-bottom:15px;"></span>

           <!-- FEEDBACK ERRORE GLOBALE -->
            @if ($errors->has('email'))
                <div class="alert-box alert-danger">
                    <i class='bx bx-error-circle'></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            <!-- BOTTONE -->
            <button type="submit" class="btn">Reimposta</button>

        </form>

        <!-- LINK LOGIN -->
        <p class="registrazione" style="margin-top: 15px;">
            Torna alla <a href="{{ route('studyroom.login') }}">Login</a>
        </p>

    </div>

</main>
</body>
</html>