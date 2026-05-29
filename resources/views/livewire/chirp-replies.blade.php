<?php

use App\Models\Chirp;
use function Livewire\Volt\{state, mount};

state([
    'chirp' => null,
    'replies' => collect(),
    'hasMore' => true,
    'lastId' => null,
]);

mount(function (Chirp $chirp) {
    $this->chirp = $chirp;
    $this->loadMore();
});

$loadMore = function () {
    if (!$this->hasMore) {
        return;
    }

    $perPage = 20;

    $newReplies = \App\Models\Chirp::where('parent_id', $this->chirp->id)
        ->orderBy('id', 'asc')
        ->with(['user', 'tags', 'replies' => fn($q) => $q->orderBy('id', 'asc')->with(['user', 'tags', 'replies' => fn($q) => $q->orderBy('id', 'asc')->with(['user', 'tags', 'replies' => fn($q) => $q->orderBy('id', 'asc')->with(['user', 'tags'])])])])
        ->withCount(['likes', 'replies'])
        ->with(['userLike' => fn($q) => $q->where('user_id', auth()->id())])
        ->when($this->lastId, fn($q) => $q->where('id', '>', $this->lastId))
        ->limit($perPage + 1)
        ->get();

    if ($newReplies->count() > $perPage) {
        $newReplies = $newReplies->take($perPage);
        $this->hasMore = true;
    } else {
        $this->hasMore = false;
    }

    if ($newReplies->isNotEmpty()) {
        $this->lastId = $newReplies->last()->id;
        $this->replies = $this->replies->merge($newReplies)->values();
    }
};

?>

<div>
    @forelse ($replies as $reply)
        <div wire:key="reply-{{ $reply->id }}" wire:ignore>
            @include('chirps._reply', ['reply' => $reply, 'depth' => 1])
        </div>
    @empty
        <div class="hero py-12">
            <div class="hero-content text-center">
                <div>
                    <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <p class="mt-4 text-base-content/60">No replies yet. Be the first to reply!</p>
                </div>
            </div>
        </div>
    @endforelse

    @if ($hasMore)
        <div x-data x-intersect.margin.400px="$wire.loadMore()" class="flex justify-center py-8">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    @else
        @if ($replies->isNotEmpty())
            <div class="flex justify-center py-8 text-base-content/40 text-sm">
                End of replies.
            </div>
        @endif
    @endif
</div>
