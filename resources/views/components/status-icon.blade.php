@props(['type' => 'info', 'icon'])

<div class="{{ $type === 'success' ? 'check-circle' : 'icon-circle' }}">
    <i class="{{ $icon }}"></i>
</div>