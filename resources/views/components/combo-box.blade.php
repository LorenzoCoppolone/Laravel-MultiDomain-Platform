@props([
    'id', 
    'inputId', 
    'name', 
    'hiddenId', 
    'listId', 
    'label' => false, // Ora è opzionale (di default non si vede)
    'placeholder', 
    'value' => '', 
    'hiddenValue' => '', 
    'lockedPlaceholder' => '', 
    'readyPlaceholder' => '', 
    'disabled' => false, 
    'required' => false,
    'wrapperClass' => 'form-group' // Di base in verticale, ma modificabile!
])

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}">{{ $label }}</label>
    @endif
    <div class="combo" id="{{ $id }}">
        <i class="fa fa-magnifying-glass combo-icon"></i>
        <input type="text" id="{{ $inputId }}" class="combo-input" autocomplete="off"
               placeholder="{{ $placeholder }}"
               @if($lockedPlaceholder) data-placeholder-locked="{{ $lockedPlaceholder }}" @endif
               @if($readyPlaceholder) data-placeholder-ready="{{ $readyPlaceholder }}" @endif
               value="{{ old($inputId, $value) }}"
               @if($required) required @endif
               @if($disabled) disabled @endif>
        <input type="hidden" name="{{ $name }}" id="{{ $hiddenId }}" value="{{ old($name, $hiddenValue) }}">
        
        <ul class="combo-list" id="{{ $listId }}" role="listbox">
            {{ $slot }}
            <li class="combo-empty" hidden>Nessun risultato trovato</li>
        </ul>
    </div>
    @error($name)
        <div class="error-msg">{{ $message }}</div>
    @enderror
</div>