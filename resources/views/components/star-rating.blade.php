@props(['media' => 0])

@php
    // Arrotonda la media al numero intero più vicino (es. 4.3 diventa 4)
    $stelle = round($media);
@endphp

<div class="materiale-rating" aria-label="Valutazione: {{ $stelle }} su 5">
    @for ($i = 0; $i < 5; $i++)
        @if ($i < $stelle)
            <span class="star star--full">★</span>
        @else
            <span class="star star--empty">★</span>
        @endif
    @endfor
    
    {{ $slot }}
</div>