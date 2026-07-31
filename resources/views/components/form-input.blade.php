@props([
    'type' => 'text',
    'name',
    'placeholder',
    'icon',
    'id' => null,
    'pattern' => null,
    'title' => null,
    'required' => false,
    'minlength' => null
])

<div class="campo-input">
    <input 
        type="{{ $type }}" 
        placeholder="{{ $placeholder }}" 
        name="{{ $name }}" 
        id="{{ $id ?? $name }}"
        value="{{ old($name) }}"
        @if($pattern) pattern="{{ $pattern }}" @endif
        @if($title) title="{{ $title }}" @endif
        @if($minlength) minlength="{{ $minlength }}" @endif
        @if($required) required @endif
    >
    <i class="{{ $icon }}"></i>
</div>

<!-- Gestione nativa degli errori di Laravel -->
@error($name)
    <span class="msg-errore">{{ $message }}</span>
@enderror