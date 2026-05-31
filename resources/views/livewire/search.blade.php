<?php
use function Livewire\Volt\{state};

// 1. Define the state (this is your wire:model="search")
state(['search' => '']);

// 2. Define the function (this is your wire:keydown.enter="performSearch")
$performSearch = function () {
    // This redirects the user to your dedicated search results page
    if (str_contains(url()->previous(), '/search/chirps')) {
        return redirect('/search/chirps?query=' . urlencode($this->search));
    }
    return redirect('/search/users?query=' . urlencode($this->search));
};
?>
<div x-data="{ open: false }" class="relative flex items-center justify-end md:w-full md:justify-center">

    <button @click.stop="open = !open" class="p-2 text-gray-400 md:hidden hover:text-gray-600 focus:outline-none">
        <x-letsicon-search-alt-fill x-cloak
            class="!size-6 text-[#808080] hover:text-[#4697E7] transition-colors duration-500 ease-in-out !cursor-pointer" />
    </button>

    <div @click.outside="open = false" @keydown.escape.window="open = false"
        :class="open ? 'fixed top-16 left-1/2 -translate-x-1/2 z-50 flex w-[90vw] max-w-[320px] max-md:z-[999]' :
            'hidden md:flex md:relative md:w-full md:left-auto md:translate-x-0'"
        x-cloak class="search-div text-gray-400 focus-within:text-gray-600 md:max-w-md">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <x-letsicon-search-alt-fill class="!size-5" />
            </div>

            <input type="text" wire:model="search" wire:keydown.enter="performSearch"
                class="searchBackground w-full pl-10 pr-4 py-2 border border-[#E4E4E5] rounded-full text-gray-600 placeholder-gray-400 focus:placeholder-gray-600 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 bg-white shadow-xl md:shadow-none"
                placeholder="Search...">
        </div>
    </div>
</div>
