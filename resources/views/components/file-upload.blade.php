@props(['name', 'id' => 'fileInput', 'accept' => 'application/pdf', 'maxSize' => '10MB'])

<label class="upload-box">
    <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" required hidden>
    <div class="upload-content">
        <i class="fa fa-file-pdf upload-box-icon"></i>
        <p class="upload-title">Scegli file</p>
        <p class="upload-info">Max {{ $maxSize }} &bull; Formato PDF</p>
        <span class="btn btn-primary upload-btn">Upload <i class="fa fa-hand-pointer"></i></span>
    </div>
    <div id="fileName" class="file-name"></div>
</label>
@error($name)
    <div class="error-msg">{{ $message }}</div>
@enderror