<?php

use App\Models\Chirp;
use function Livewire\Volt\{state};

state([
    'chirp' => fn(Chirp $chirp) => $chirp,
    'count' => fn(Chirp $chirp) => $chirp->likes()->count(),
    'status' => fn(Chirp $chirp) => $chirp
        ->likes()
        ->where('user_id', auth()->id())
        ->exists(),
]);

$toggle = function () {
    $userId = auth()->id();

    $deleted = $this->chirp->likes()->where('user_id', $userId)->delete();

    if (!$deleted) {
        $this->chirp->likes()->create(['user_id' => $userId]);
    }

    $this->count = $this->chirp->likes()->count();
    $this->status = (bool) $this->chirp->likes()->where('user_id', $userId)->exists();
};
?>

<div x-data="{
    localStatus: @js($status),
    processing: false,

    async toggleLike() {
        const isLiking = !this.localStatus;
        this.localStatus = isLiking; // optimistic fill only
        this.processing = true;

        try {
            await this.$wire.toggle();
            // Let Livewire re-render the count — don't touch it
            this.localStatus = this.$wire.status; // reconcile fill with server
        } catch (e) {
            this.localStatus = !isLiking; // rollback fill
        } finally {
            this.processing = false;
        }
    }
}" class="flex gap-2 mt-3 -mb-4 mr-0.5 items-center leading-none text-base-content/60">

    <button wire:loading.attr="disabled" @click="toggleLike()" :disabled="processing"
        :class="processing ? 'opacity-50 cursor-not-allowed' : ''"
        class="group/like flex flex-col cursor-pointer relative size-[1em] justify-center items-center">

        <div x-show="localStatus" class="contents">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#4887DC"
                class="absolute size-[1em] self-end opacity-100 transition-all stroke-[#4887DC] duration-500 ease-in-out">
                <path
                    d="M2.09 15a1 1 0 0 0 1-1V8a1 1 0 1 0-2 0v6a1 1 0 0 0 1 1ZM5.765 13H4.09V8c.663 0 1.218-.466 1.556-1.037a4.02 4.02 0 0 1 1.358-1.377c.478-.292.907-.706.989-1.26V4.32a9.03 9.03 0 0 0 0-2.642c-.028-.194.048-.394.224-.479A2 2 0 0 1 11.09 3c0 .812-.08 1.605-.235 2.371a.521.521 0 0 0 .502.629h1.733c1.104 0 2.01.898 1.901 1.997a19.831 19.831 0 0 1-1.081 4.788c-.27.747-.998 1.215-1.793 1.215H9.414c-.215 0-.428-.035-.632-.103l-2.384-.794A2.002 2.002 0 0 0 5.765 13Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#559bf6"
                class="absolute size-[1em] self-end opacity-0 group-hover/like:opacity-100 transition-all stroke-[#559bf6] duration-500 ease-in-out">
                <path
                    d="M2.09 15a1 1 0 0 0 1-1V8a1 1 0 1 0-2 0v6a1 1 0 0 0 1 1ZM5.765 13H4.09V8c.663 0 1.218-.466 1.556-1.037a4.02 4.02 0 0 1 1.358-1.377c.478-.292.907-.706.989-1.26V4.32a9.03 9.03 0 0 0 0-2.642c-.028-.194.048-.394.224-.479A2 2 0 0 1 11.09 3c0 .812-.08 1.605-.235 2.371a.521.521 0 0 0 .502.629h1.733c1.104 0 2.01.898 1.901 1.997a19.831 19.831 0 0 1-1.081 4.788c-.27.747-.998 1.215-1.793 1.215H9.414c-.215 0-.428-.035-.632-.103l-2.384-.794A2.002 2.002 0 0 0 5.765 13Z" />
            </svg>
        </div>

        <div x-show="!localStatus" class="contents">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                class="absolute size-[1em] self-end opacity-0 group-hover/like:opacity-100 transition-all stroke-[#6A6A6C] duration-500 ease-in-out">
                <path
                    d="M2.09 15a1 1 0 0 0 1-1V8a1 1 0 1 0-2 0v6a1 1 0 0 0 1 1ZM5.765 13H4.09V8c.663 0 1.218-.466 1.556-1.037a4.02 4.02 0 0 1 1.358-1.377c.478-.292.907-.706.989-1.26V4.32a9.03 9.03 0 0 0 0-2.642c-.028-.194.048-.394.224-.479A2 2 0 0 1 11.09 3c0 .812-.08 1.605-.235 2.371a.521.521 0 0 0 .502.629h1.733c1.104 0 2.01.898 1.901 1.997a19.831 19.831 0 0 1-1.081 4.788c-.27.747-.998 1.215-1.793 1.215H9.414c-.215 0-.428-.035-.632-.103l-2.384-.794A2.002 2.002 0 0 0 5.765 13Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="absolute size-[1em] stroke-[#6A6A6C] opacity-100 group-hover/like:!opacity-0 transition-all duration-500 ease-in-out">
                <path
                    d="M2.09 15a1 1 0 0 0 1-1V8a1 1 0 1 0-2 0v6a1 1 0 0 0 1 1ZM5.765 13H4.09V8c.663 0 1.218-.466 1.556-1.037a4.02 4.02 0 0 1 1.358-1.377c.478-.292.907-.706.989-1.26V4.32a9.03 9.03 0 0 0 0-2.642c-.028-.194.048-.394.224-.479A2 2 0 0 1 11.09 3c0 .812-.08 1.605-.235 2.371a.521.521 0 0 0 .502.629h1.733c1.104 0 2.01.898 1.901 1.997a19.831 19.831 0 0 1-1.081 4.788c-.27.747-.998 1.215-1.793 1.215H9.414c-.215 0-.428-.035-.632-.103l-2.384-.794A2.002 2.002 0 0 0 5.765 13Z" />
            </svg>
        </div>
    </button>

    {{-- Livewire owns the count — no Alpine interference --}}
    <p class="text-[1em]">{{ $count }}</p>
</div>
