@php
    $isLast = $loop->last ?? true;
    $limit = 4; // Your depth limit
@endphp

<div class="relative mt-4">
    @if ($depth > 1 && $depth <= $limit)
        <div
            class="absolute -left-6 top-[-16px] w-[2px] bg-gray-300 
            {{ $isLast ? 'h-[48px]' : 'h-[calc(100%+16px)]' }}">
        </div>

        <div class="absolute -left-6 top-8 w-6 !h-[2px] bg-gray-300"></div>
    @endif
    <x-chirp :chirp="$reply" :replying="$depth > $limit ? $reply->parent : null" />

    @if ($reply->replies->isNotEmpty())
        <div class="{{ $depth < $limit ? 'ml-6 pl-4' : 'ml-0' }}">
            @foreach ($reply->replies as $nestedReply)
                @include('chirps._reply', [
                    'reply' => $nestedReply,
                    'depth' => $depth + 1,
                ])
            @endforeach
        </div>
    @endif
</div>
