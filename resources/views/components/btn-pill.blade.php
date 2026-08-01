@props(['type' => 'primary', 'icon' => null, 'href' => null, 'customClass' => ''])

@php
    $baseClass = 'btn btn-pill';
    $colorClass = match($type) {
        'secondary' => 'btn-pill-secondary',
        'danger' => 'btn-pill-danger',
        default => 'btn-pill-primary',
    };
    $classes = "$baseClass $colorClass $customClass";
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}">
        @if($icon) <i class="{{ $icon }}"></i> @endif
        {{ $slot }}
    </a>
@else
    <button type="submit" class="{{ $classes }}">
        @if($icon) <i class="{{ $icon }}"></i> @endif
        {{ $slot }}
    </button>
@endif