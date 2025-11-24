@php
    $address = $getState();
@endphp

<span>
    @if (strlen($address) > 10)
        <span x-data="{ expanded: false }">
            <span x-show="!expanded">{{ substr($address, 0, 10) }}...</span>
            <span x-show="expanded">{{ $address }}</span>
            <button type="button" @click="expanded = !expanded" class="text-blue-600 underline ml-1"
                onclick="event.stopPropagation()">
                <span x-text="expanded ? 'Show less' : 'Read more'"></span>
            </button>

        </span>
    @else
        {{ $address }}
    @endif
</span>
