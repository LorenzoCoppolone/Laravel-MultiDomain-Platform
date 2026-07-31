@props(['base64' => null])

<a href="{{ route('studyroom.profile.edit') }}" class="nav-user-avatar">
    @if($base64)
        <img src="{{ $base64 }}" alt="Foto profilo">
    @else
        <i class="fa fa-circle-user"></i>
    @endif
</a>