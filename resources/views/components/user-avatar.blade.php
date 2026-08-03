@props(['contenuto' => null, 'mimetype' => null])

<a href="{{ route('studyroom.profile.index') }}" class="nav-user-avatar">
    @if($contenuto)
        <img src="data:{{ $mimetype }};base64,{{ base64_encode($contenuto) }}" alt="Foto profilo">
    @else
        <i class="fa fa-circle-user"></i>
    @endif
</a>