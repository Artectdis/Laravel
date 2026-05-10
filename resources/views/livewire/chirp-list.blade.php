<?php
use function Livewire\Volt\{state, mount};
use App\Models\Chirp;

// Use a typed collect() so Livewire serializes it correctly across requests
state(['chirps' => collect(), 'lastId' => null, 'hasMore' => true, 'loading' => false]);

mount(function () {
    $this->loadMore();
});

$loadMore = function () {
    if (!$this->hasMore || $this->loading) {
        return;
    }

    $this->loading = true;

    $perPage = 20; // Load 20 at a time — fewer round trips for 100 chirps

    $query = Chirp::with('user:id,name,email,avatar') // Only needed user columns
        ->select('id', 'user_id', 'message', 'created_at', 'updated_at') // No table prefix needed here
        ->latest('id'); // Relies on the PK index — fastest possible ordering

    // Keyset: no OFFSET, no cursor encoding overhead, just a WHERE on an indexed column
    if ($this->lastId !== null) {
        $query->where('id', '<', $this->lastId);
    }

    $newChirps = $query->limit($perPage + 1)->get();

    if ($newChirps->count() > $perPage) {
        $newChirps = $newChirps->take($perPage);
        $this->hasMore = true;
    } else {
        $this->hasMore = false;
    }

    // Bug fix from V1: always update lastId, even on first page
    if ($newChirps->isNotEmpty()) {
        $this->lastId = $newChirps->last()->id;
    }

    $this->chirps = $this->chirps->merge($newChirps);
    $this->loading = false;
};
?>

<div class="space-y-4">
    @forelse ($chirps as $chirp)
        <div wire:key="chirp-{{ $chirp->id }}">
            <x-chirp :chirp="$chirp" />
        </div>
    @empty
        @if (!$hasMore)
            <div class="hero py-12">
                <div class="hero-content text-center">
                    <div>
                        <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    {{-- 
        800px margin: triggers loadMore well before the spinner is visible.
        wire:loading.remove hides the spinner once Livewire responds.
        The `loading` guard on the PHP side prevents duplicate concurrent calls.
    --}}
    @if ($hasMore)
        <div x-data x-intersect.margin.800px="$wire.loadMore()" class="flex justify-center py-8">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    @endif
</div>
