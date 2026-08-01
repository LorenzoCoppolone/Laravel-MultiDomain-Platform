@props(['icon' => 'bx bx-error'])

<div class="nota-spam">
    <i class="{{ $icon }}"></i>
    <span>{{ $slot }}</span>
</div>