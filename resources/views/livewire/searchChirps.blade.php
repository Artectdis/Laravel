<?php
use function Livewire\Volt\{state, computed, layout};
use App\Models\Chirp;

// 1. Tell Volt which layout to use (standard x-layout)
layout('components.layout');

// 2. Grab 'query' from the URL (?query=abc)
state(['query' => fn() => request('query')]);

// 3. Perform the database search
$chirps = computed(function () {
    return Chirp::where('message', 'ilike', "%{$this->query}%")->paginate(10);
});
?>

<div class="px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-row mb-4 border-b border-gray-300">
            <a href="/search/users/?query={{ $query }}"
                class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !text-xl">Users</a>
            <a href="/search/chirps/?query={{ $query }}"
                class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !border-b-2 !border-[#5580d2] !text-xl">Chirps</a>
        </div>
        <h2 class="text-xl">Results for: <strong>{{ $query }}</strong></h2>
        <p class="text-gray-500 mb-6">{{ $this->chirps->total() }} Chirps Found.</p>

        <div class="flex flex-col gap-2">
            @forelse ($this->chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <p class="text-gray-500 text-lg">No chirps found matching your search.</p>
            @endforelse
        </div>
        {{ $this->chirps->withQueryString()->onEachSide(1)->links() }}
    </div>
</div>
