@props(['id', 
        'name', 
        'label',
        'icon',
        'iconId' => null, 
        'value' => '', 
        'placeholder' => '', 
        'pattern' => null, 
        'title' => null, 
        'required' => false])

<div class="edit-field">
    <label for="{{ $id }}">{{ $label }}</label>
    
    <div class="campo-input">
        <i class="{{ $icon }}"></i>
        <input type="text" 
               id="{{ $id }}" 
               name="{{ $name }}"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
               @if($pattern) pattern="{{ $pattern }}" @endif
               @if($title) title="{{ $title }}" @endif
               @if($required) required @endif>
    </div>

    <!-- Errore di validazione Laravel specifico per questo campo -->
    @error($name)
        <span style="color: var(--color-accent, #e85555); font-size: 0.85rem; margin-top: 0.2rem; margin-left: 0.5rem;">
            {{ $message }}
        </span>
    @enderror
</div>