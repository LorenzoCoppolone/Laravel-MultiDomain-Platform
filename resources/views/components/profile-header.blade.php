@props(['utente'])

<header class="profile-header">
    <div class="profile-photo">
        @if($utente->foto_profilo)
            <!-- Assumiamo che la foto sia salvata nello storage pubblico -->
            <img src="{{ asset('storage/' . $utente->foto_profilo) }}" alt="Foto profilo">
        @else
            <i class="fa fa-circle-user"></i>
        @endif
    </div>

    <div class="profile-identity">
        <h1 class="profile-name">{{ $utente->nome }} {{ $utente->cognome }}</h1>
        <p class="profile-meta"><i class="fa fa-envelope"></i> {{ $utente->email }}</p>
        <p class="profile-meta"><i class="fa fa-at"></i> {{ $utente->username }}</p>
        
        <!-- Slot per iniettare pulsanti (es. Modifica) -->
        {{ $slot }}
    </div>
</header>