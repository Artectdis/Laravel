<?php
use function Livewire\Volt\{state, computed, layout};
use App\Models\User;

// 1. Tell Volt which layout to use (standard x-layout)
layout('components.layout');

// 2. Grab 'query' from the URL (?query=abc)
state(['query' => fn() => request('query')]);

// 3. Perform the database search
$users = computed(function () {
    return User::where('name', 'like', "%{$this->query}%")
        ->orWhere('email', 'like', "%{$this->query}%")
        ->paginate(10);
});
?>

<main class="px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-row mb-4 border-b border-gray-300">
            <a class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !border-b-2 !text-xl !border-[#5580d2]">Users</a>
            <a href="/search/chirps/?query={{ $query }}"
                class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !text-xl">Chirps</a>
        </div>
        <h2 class="text-xl">Results for: <strong>{{ $query }}</strong></h2>
        <p class="text-gray-500 mb-6">{{ $this->users->total() }} Users Found.</p>

        @forelse ($this->users as $user)
            <a href="/profile/{{ $user->id }}" class="group">
                <div
                    class="card p-6 bg-base-100 space-x-3 mb-4 group-hover:!bg-gray-300 !transition-all duration-500 ease-in-out">
                    <div class="flex flex-row items-stretch gap-2 h-[60px]">
                        <div class="avatar">
                            <div class="aspect-square h-full rounded-full">
                                <img loading="lazy" src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s avatar"
                                    class="rounded-full" />
                            </div>
                        </div>

                        <div class="ml-4 rounded-lg">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-gray-500 text-lg">No users found matching your search.</p>
        @endforelse
        {{ $this->users->withQueryString()->onEachSide(1)->links() }}
    </div>
</main>
