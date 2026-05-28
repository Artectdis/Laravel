<?php
use function Livewire\Volt\{state, mount, on};
use App\Models\Chirp;

state(['tag' => ''])->url();
state(['feed' => 'global'])->url();

state([
    'chirps' => collect(),
    'lastCreatedAt' => null,
    'hasMore' => true,
]);

on([
    'feed-changed' => function ($feed) {
        $this->feed = $feed;
        $this->resetList();
        $this->dispatch('feed-loaded');
    },
]);

$updatedTag = fn() => $this->resetList();
$updatedFeed = fn() => $this->resetList();

$resetList = function () {
    $this->chirps = collect();
    $this->lastCreatedAt = null;
    $this->hasMore = true;
    $this->loadMore();
};

mount(function () {
    $this->loadMore();
});

$loadMore = function () {
    if (!$this->hasMore) {
        return;
    }

    $perPage = 20;
    $blockedIds = auth()->check() ? auth()->user()->blocks()->pluck('blocked_user_id') : collect();

    $tagId = data_get($this->tag, 'id', $this->tag);

    $query = Chirp::whereNull('parent_id')
        ->when($tagId, fn($q) => $q->whereRelation('tags', 'tags.id', $tagId))
        ->whereNotIn('user_id', $blockedIds)
        ->with('user:id,name,email,avatar,email_verified_at', 'tags')
        ->withCount(['likes', 'replies'])
        ->with(['userLike' => fn($q) => $q->where('user_id', auth()->id())])
        ->select('id', 'user_id', 'message', 'created_at', 'updated_at', 'parent_id')
        ->latest();

    if ($this->feed === 'following' && auth()->check()) {
        $followingIds = auth()->user()->following()->pluck('users.id');
        $query->whereIn('user_id', $followingIds)->where('user_id', '!=', auth()->id());
    }

    if ($this->lastCreatedAt !== null) {
        $query->where('created_at', '<', $this->lastCreatedAt);
    }

    $newChirps = $query->limit($perPage + 1)->get();

    if ($newChirps->count() > $perPage) {
        $newChirps = $newChirps->take($perPage);
        $this->hasMore = true;
    } else {
        $this->hasMore = false;
    }

    if ($newChirps->isNotEmpty()) {
        $this->lastCreatedAt = $newChirps->last()->created_at;
        $this->chirps = $this->chirps->merge($newChirps)->unique('id')->values();
    }
};

?>

<div class="space-y-4" x-data="{ loading: false }" x-on:feed-changed.window="loading = true"
    x-on:feed-loaded.window="loading = false">

    <div x-cloak x-show="loading" class="flex justify-center py-16">
        <span class="loading loading-spinner loading-lg text-primary"></span>
    </div>

    <div x-cloak x-show="!loading" class="space-y-4">
        @forelse ($chirps as $chirp)
            <div wire:key="chirp-{{ $chirp->id }}-{{ $feed }}">
                <x-chirp :chirp="$chirp" />
            </div>
        @empty
            @if (!$hasMore)
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="mt-4 text-base-content/60">No chirps yet. Be the first to chirp!</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforelse

        @if ($hasMore)
            <div x-data x-intersect.margin.800px="$wire.loadMore()" class="flex justify-center py-8">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>
        @else
            @if ($chirps->isNotEmpty())
                <div class="flex justify-center py-8 text-base-content/40 text-sm">
                    You've reached the end.
                </div>
            @endif
        @endif
    </div>
</div>
