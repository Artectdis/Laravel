<?php
use function Livewire\Volt\{state, computed, layout};
use App\Models\User;

// 1. Tell Volt which layout to use (standard x-layout)
layout('components.layout');

// 2. Grab 'query' from the URL (?query=abc)
state(['query' => fn() => request('query')]);

// 3. Perform the database search
$users = computed(function () {
    return User::where('name', 'ilike', "%{$this->query}%")
        ->orWhere('email', 'ilike', "%{$this->query}%")
        ->paginate(10);
});
?>

<div class="px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-row mb-4 border-b border-gray-300">
            <a class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !border-b-2 !text-xl !border-[#5580d2]">Users</a>
            <a href="/search/chirps/?query={{ $query }}"
                class="btn btn-ghost !px-8 !py-8 !rounded-none !border-0 !text-xl">Chirps</a>
        </div>
        <h2 class="text-xl">Results for: <strong>{{ $query }}</strong></h2>
        <p class="text-gray-500 mb-6">{{ $this->users->total() }} Users Found.</p>
        <div class="flex flex-col gap-2">
            @forelse ($this->users as $user)
                <div class="card bg-base-100 cursor-pointer relative hover:!bg-gray-200 !transition-colors !duration-500 !ease-in-out"
                    onclick="if(!window.getSelection().toString()) { Livewire.navigate('/profile/{{ $user->id }}') }">
                    <div class="card-body">
                        <div class="flex space-x-3">
                            @php $profileUrl = $user->name ? "/profile/{$user->id}" : "#"; @endphp

                            <div class="avatar relative z-20">
                                <a href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                                    <div class="size-10 rounded-full">
                                        <img loading="lazy" src="{{ $user->avatar_url }}"
                                            alt="{{ $user->name }}'s avatar" class="rounded-full" />
                                    </div>
                                </a>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex justify-between w-full">
                                    <div class="flex gap-1 flex-wrap">
                                        <a class="text-sm font-semibold hover:underline !text-black"
                                            href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                                            {{ $user->name }}
                                        </a>
                                        @if ($user?->email_verified_at)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                fill="currentColor"
                                                class="size-4 hover:text-[#4A40A9] hover:scale-110 origin-center text-[#4697E7] transition-all duration-200 ease-in-out mt-0.5 flex-shrink-0">
                                                <path fill-rule="evenodd"
                                                    d="M15 8c0 .982-.472 1.854-1.202 2.402a2.995 2.995 0 0 1-.848 2.547 2.995 2.995 0 0 1-2.548.849A2.996 2.996 0 0 1 8 15a2.996 2.996 0 0 1-2.402-1.202 2.995 2.995 0 0 1-2.547-.848 2.995 2.995 0 0 1-.849-2.548A2.996 2.996 0 0 1 1 8c0-.982.472-1.854 1.202-2.402a2.995 2.995 0 0 1 .848-2.547 2.995 2.995 0 0 1 2.548-.849A2.995 2.995 0 0 1 8 1c.982 0 1.854.472 2.402 1.202a2.995 2.995 0 0 1 2.547.848c.695.695.978 1.645.849 2.548A2.996 2.996 0 0 1 15 8Zm-3.291-2.843a.75.75 0 0 1 .135 1.052l-4.25 5.5a.75.75 0 0 1-1.151.043l-2.25-2.5a.75.75 0 1 1 1.114-1.004l1.65 1.832 3.7-4.789a.75.75 0 0 1 1.052-.134Z"
                                                    clip-rule="evenodd" />
                                                <title>Verified</title>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-full trix-content flex">
                                    <div class="flex-1 text-sm text-base-content/60">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-lg">No users found matching your search.</p>
            @endforelse
            {{ $this->users->withQueryString()->onEachSide(1)->links() }}
        </div>
    </div>
</div>
