@props(['type' => 'success', 'message'])

<div class="alert-box alert-{{ $type }}">
    <i class="{{ $type === 'success' ? 'bx bx-check-circle' : 'bx bx-error-circle' }}"></i>
    <span>{{ $message }}</span>
</div>