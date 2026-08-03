@props(['contenuto' => null, 'mimetype' => null])

<div class="edit-photo-block">
    <div class="edit-photo" id="previewWrapper">
        @if($contenuto)
            <!-- Qui convertiamo il BLOB in immagine visibile Base64 -->
            <img src="data:{{ $mimetype }};base64,{{ base64_encode($contenuto) }}" alt="Foto profilo" id="previewImg">
        @else
            <i class="fa fa-circle-user" id="previewPlaceholder"></i>
        @endif
    </div>

    <!-- Applichiamo le classi del componente btn-pill alla label -->
    <label for="immagine" class="btn-pill btn-pill-secondary edit-photo-btn">
        <i class="fa fa-camera"></i> Cambia immagine
    </label>
    
    <input type="file" name="immagine" id="immagine" accept="image/png, image/jpeg, image/jpg, image/webp" hidden>
    <span class="edit-photo-hint">PNG o JPG (Max 2MB)</span>

    @error('immagine')
        <span style="color: var(--color-accent, #e85555); font-size: 0.85rem;">{{ $message }}</span>
    @enderror
</div>