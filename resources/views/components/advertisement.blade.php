@props(['position' => 'top'])

@php
    $ads = app(\App\Services\CacheService::class)->getActiveAds($position);
@endphp

@if($ads->isNotEmpty())
<div class="advertisement advertisement-{{ $position }}">
    @foreach($ads as $ad)
        <a href="{{ $ad->link_url }}" target="_blank" rel="noopener sponsored" class="block mb-4">
            <img loading="lazy" src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" class="w-full rounded-lg" loading="lazy">
        </a>
    @endforeach
</div>
@endif
