@props([
    'action' => url('/studyroom/cerca'),
    'placeholder' => 'Cerca...',
    'formClass' => 'navbar-search',
    'buttonClass' => 'btn-search'
])

<form class="{{ $formClass }}" method="GET" action="{{ $action }}">
    <input type="text" name="titolo" placeholder="{{ $placeholder }}" maxlength="100" required>
    <button class="{{ $buttonClass }}" type="submit">
        <i class="fa fa-magnifying-glass"></i>
    </button>
</form>