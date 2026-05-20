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

<div class="relative text-gray-400 focus-within:text-gray-600 w-full max-w-[30%]">
    <!-- Icon Container -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <!-- Removed absolute from here -->
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                clip-rule="evenodd" />
        </svg>
    </div>
    <!-- Input Field -->
    <input type="text" wire:model="search" wire:keydown.enter="performSearch"
        class="searchBackground w-full pl-10 pr-4 py-2 border border-[#E4E4E5] rounded-full text-gray-600 placeholder-gray-400 focus:placeholder-gray-600 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400"
        placeholder="Search...">
</div>
