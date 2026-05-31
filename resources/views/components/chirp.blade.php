@props(['chirp', 'replying' => null])

<div class="card bg-base-100 group cursor-pointer relative hover:!bg-gray-200 !transition-colors !duration-500 !ease-in-out z-2"
    onclick="if(!window.getSelection().toString()) { Livewire.navigate('/chirps/{{ $chirp->id }}') }">
    <div class="card-body">
        @if ($replying)
            <a href="/chirps/{{ $replying->id }}" onclick="event.stopPropagation()"
                class="!-mt-1 !mb-0.2 text-xs text-blue-400 font-medium bg-white dark:bg-gray-200 dark:hover:!text-black rounded-full py-1 px-2 w-fit hover:bg-blue-500 hover:!text-white transition-colors ease-in-out">
                <span>Replying to <span class="font-semibold">{{ $replying->user->name }}</span></span>
            </a>
        @endif
        <div class="flex space-x-3">
            @php $profileUrl = $chirp->user ? "/profile/{$chirp->user->id}" : "#"; @endphp

            <div class="avatar relative z-20 h-fit">
                <a href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                    <div class="size-10 rounded-full">
                        <img loading="lazy" src="{{ $chirp->user->avatar_url }}" alt="{{ $chirp->user->name }}'s avatar"
                            class="rounded-full" />
                    </div>
                </a>
            </div>
            {{-- @else
                <div class="avatar placeholder">
                    <div class="size-10 rounded-full">
                        <img src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth"
                            alt="Anonymous User" class="rounded-full" />
                    </div>
                </div>
            @endif --}}

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex gap-1 flex-wrap min-w-0">
                        <a class="text-sm font-semibold hover:underline !text-black truncate max-w-[140px] sm:max-w-none"
                            href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                            {{ $chirp->user->name }}
                        </a>
                        @if ($chirp->user?->email_verified_at)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-4 hover:text-[#4A40A9] hover:scale-110 origin-center text-[#4697E7] transition-all duration-200 ease-in-out mt-0.5 flex-shrink-0">
                                <path fill-rule="evenodd"
                                    d="M15 8c0 .982-.472 1.854-1.202 2.402a2.995 2.995 0 0 1-.848 2.547 2.995 2.995 0 0 1-2.548.849A2.996 2.996 0 0 1 8 15a2.996 2.996 0 0 1-2.402-1.202 2.995 2.995 0 0 1-2.547-.848 2.995 2.995 0 0 1-.849-2.548A2.996 2.996 0 0 1 1 8c0-.982.472-1.854 1.202-2.402a2.995 2.995 0 0 1 .848-2.547 2.995 2.995 0 0 1 2.548-.849A2.995 2.995 0 0 1 8 1c.982 0 1.854.472 2.402 1.202a2.995 2.995 0 0 1 2.547.848c.695.695.978 1.645.849 2.548A2.996 2.996 0 0 1 15 8Zm-3.291-2.843a.75.75 0 0 1 .135 1.052l-4.25 5.5a.75.75 0 0 1-1.151.043l-2.25-2.5a.75.75 0 1 1 1.114-1.004l1.65 1.832 3.7-4.789a.75.75 0 0 1 1.052-.134Z"
                                    clip-rule="evenodd" />
                                <title>Verified</title>
                            </svg>
                        @endif
                        <span
                            class="flex gap-1 -mt-2 mr-2 items-center whitespace-nowrap text-sm text-base-content/60 leading-8">
                            <span>·</span>
                            <span>{{ $chirp->created_at->diffForHumans() }}</span>
                            @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                                <span>(edited)</span>
                            @endif
                        </span>
                    </div>
                    {{-- Mobile: show 1 tag + N --}}
                    <div class="flex sm:hidden gap-1 -mt-2 items-center ml-auto mr-4 min-w-0 max-w-[120px]">
                        @if ($chirp->tags->isNotEmpty())
                            @php
                                $firstTag = $chirp->tags->first();
                                $extraCount = $chirp->tags->count() - 1;
                            @endphp
                            <a href="/?tag={{ $firstTag->id }}" title="{{ $firstTag->name }}"
                                onclick="event.stopPropagation()"
                                class="badge badge-sm border-none !text-xs !px-2 !py-1 dark:!text-black rounded-full min-w-0 flex items-center overflow-hidden flex-shrink"
                                style="background-color: {{ $firstTag->color }}; color: white;">
                                <span class="truncate block min-w-0">#{{ $firstTag->name }}</span>
                            </a>
                            @if ($extraCount > 0)
                                <span
                                    class="badge badge-sm border border-base-300 bg-base-200 text-base-content/60 !text-xs !px-2 !py-1 rounded-full whitespace-nowrap flex-none">
                                    +{{ $extraCount }}
                                </span>
                            @endif
                        @endif
                    </div>

                    {{-- Desktop: show up to 3 tags + N --}}
                    <div
                        class="hidden sm:flex gap-1 -mt-2 items-center justify-end mr-4 min-w-0 w-[240px] shrink ml-auto">
                        @if ($chirp->tags->isNotEmpty())
                            @php
                                $nameLength = strlen($chirp->user->name);
                                $isNameLong = $nameLength > 20;

                                $visible = $isNameLong ? $chirp->tags->take(1) : $chirp->tags->take(3);
                                $extraCount = $chirp->tags->count() - $visible->count();

                                $tagMaxW = match ($visible->count()) {
                                    1 => 'max-w-[200px]',
                                    2 => 'max-w-[96px]',
                                    default => 'max-w-[64px]',
                                };
                            @endphp

                            @foreach ($visible as $tag)
                                <a href="/?tag={{ $tag->id }}" title="{{ $tag->name }}"
                                    onclick="event.stopPropagation()"
                                    class="badge badge-sm border-none !text-xs !px-2 !py-1 dark:!text-black rounded-full min-w-0 flex items-center overflow-hidden flex-shrink {{ $tagMaxW }}"
                                    style="background-color: {{ $tag->color }}; color: white;">
                                    <span class="truncate block min-w-0">#{{ $tag->name }}</span>
                                </a>
                            @endforeach
                            @if ($extraCount > 0)
                                <span
                                    class="badge badge-sm border border-base-300 bg-base-200 text-base-content/60 !text-xs !px-2 !py-1 rounded-full whitespace-nowrap flex-none">
                                    +{{ $extraCount }}
                                </span>
                            @endif
                        @endif
                    </div>
                    <!-- Only show edit/delete if user owns the chirp -->
                    <div class="relative z-20 {{ $replying ? '-mt-8' : 'mt-0.5' }}" onclick="event.stopPropagation()">
                        <x-ts-dropdown icon="ellipsis-horizontal" static>
                            @if (auth()->check() && auth()->id() === $chirp->user_id)
                                <a href="/chirps/{{ $chirp->id }}/edit">
                                    <x-ts-dropdown.items text="Edit" />
                                </a>
                                <x-ts-dropdown.items separator
                                    x-on:click="if (confirm('Are you sure you want to delete this chirp? ‼️🐥‼️')) {$refs.deleteForm{{ $chirp->id }}.submit()}">
                                    <span class="text-red-400">Delete</span>
                                </x-ts-dropdown.items>

                                <form x-ref="deleteForm{{ $chirp->id }}" method="POST"
                                    action="/chirps/{{ $chirp->id }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <livewire:follow :user="$chirp->user" />
                                <livewire:block :user="$chirp->user" />
                            @endif
                        </x-ts-dropdown>
                    </div>
                    {{-- <div class="flex gap-1">
                            <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="/chirps/{{ $chirp->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this chirp?')"
                                    class="btn btn-ghost btn-xs text-error">
                                    Delete
                                </button>
                            </form>
                        </div> --}}
                </div>
                <div class="w-full trix-content {{ $replying ? 'mt-0.5' : '-mt-1' }} flex">
                    <div class="flex-1 chirp-body-clamp">
                        @safeHtml($chirp->message)
                    </div>

                    <div onclick="event.stopPropagation()"
                        class="relative z-20 flex flex-col justify-end !pl-4 ml-4 opacity-0 group-hover:opacity-100 transition-all duration-500 ease-in-out">
                        <livewire:like :chirp="$chirp" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
