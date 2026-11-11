@props(['slotName', 'formatClass' => ''])

@php
    $ads = ($advertisementSlots ?? collect())->get($slotName, collect());
@endphp

@if ($ads->isNotEmpty())
    <aside class="ad-slot ad-slot--{{ $slotName }} {{ $formatClass }} my-4" aria-label="Advertisement">
        <div class="text-secondary small text-uppercase mb-1" style="letter-spacing: .08em;">Advertisement</div>
        @foreach ($ads as $ad)
            <div class="ad-slot__item p-3 border rounded bg-body">{!! $ad->body_html !!}</div>
        @endforeach
    </aside>
@endif
