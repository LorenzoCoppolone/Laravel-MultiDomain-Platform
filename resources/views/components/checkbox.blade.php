@props(['name', 'id', 'label', 'required' => false])

<div class="form-check">
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" @if($required) required @endif>
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@error($name)
    <div class="error-msg">{{ $message }}</div>
@enderror