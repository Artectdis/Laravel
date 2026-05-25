<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="card p-4">
            @if ($chirp->parent_id)
                <a href="/chirps/{{ $chirp->parent->id }}"
                    class="bg-gray-50 p-2 mb-4 border-b-1 border-gray-200 rounded-[0.5rem] hover:bg-blue-50 transition-colors duration-500 ease-in-out dark:bg-[#ddd] dark:hover:bg-[#dde] inline-block">
                    <div class="text-blue-400">
                        <div class="flex gap-2 items-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-[1em] shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>

                            <span>Replying to <span
                                    class="font-semibold">{{ $chirp->parentUser->user->name }}</span></span>
                        </div>
                        <div class="ml-6 text-gray-500 text-sm italic">
                            {{ Str::limit(strip_tags($chirp->parent->message), 60) }}
                        </div>
                    </div>
                </a>
            @else
                <a href="/" class="inline-block size-fit mb-4">
                    <div
                        class="flex gap-2 items-center text-blue-400 hover:text-blue-600 transition-colors duration-200 ease-in-out h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-[1em] shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        Return
                    </div>
                </a>
            @endif
            <div class="flex space-x-3">

                @php $profileUrl = $chirp->user ? "/profile/{$chirp->user->id}" : "#"; @endphp
                <div class="flex gap-4 w-full">
                    <div class="avatar relative z-20">
                        <a href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                            <div class="size-12 rounded-full">
                                <img loading="lazy" src="{{ $chirp->user->avatar_url }}"
                                    alt="{{ $chirp->user->name }}'s avatar" class="rounded-full" />
                            </div>
                        </a>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between w-full">
                            <div class="flex gap-2 flex-wrap">
                                <div class="flex flex-col">
                                    <div class="flex gap-2 items-center">
                                        <a class="text-lg font-semibold hover:underline !text-black w-fit"
                                            href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                                            {{ $chirp->user ? $chirp->user->name : 'Anonymous' }}
                                        </a>
                                        @if ($chirp->user?->email_verified_at)
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
                                    <span
                                        class="text-sm text-base-content/60 -mt-0.5">{{ $chirp->created_at->diffForHumans() }}
                                        @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                                            (edited)
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <!-- Only show edit/delete if user owns the chirp -->
                            <div class="relative z-20 !ml-auto" onclick="event.stopPropagation()">
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
                                        <livewire:follow :user="$chirp->user_id" />
                                    @endif
                                </x-ts-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-wrap break-words">@safeHtml($chirp->message)</div>
            <div class="py-4">
                <livewire:like :chirp="$chirp" />
            </div>
        </div>
        <form method="POST" action="/chirps" novalidate>
            @csrf
            <div class="form-control w-full mt-4">
                <input id="message_hidden" type="hidden" name="message" value="{{ old('message') }}">
                <input type="hidden" name="parent_id" value="{{ $chirp->id }}">

                <div x-data="{
                    content: '{{ addslashes(old('message', '')) }}',
                    count: {{ strlen(old('message', '')) }},
                    focused: {{ old('message') ? 'true' : 'false' }}
                }" @focusin="focused = true" @focusout="focused = false" x-cloak>

                    <div x-show="focused || count > 0" x-transition x-cloak>
                        <trix-toolbar id="chirp_toolbar" class="mt-4"></trix-toolbar>
                    </div>

                    <div class="transition-all duration-200 ease-in-out">

                        <trix-editor input="message_hidden" toolbar="chirp_toolbar" placeholder="Chirp your reply"
                            @trix-focus="focused = true" @trix-blur="if(count === 0) focused = false"
                            @trix-change="count = $event.target.editor.getDocument().toString().length - 1"
                            class="!pt-5 !pl-4 trix-reply-editor trix-content textarea align-middle textarea-bordered w-full transition-all duration-200
                  {{ $errors->has('message') ? 'textarea-error' : '' }}"
                            :class="focused ? 'rounded-lg h-auto !min-h-[8rem]' :
                                '!min-h-[4rem] overflow-hidden py-2 leading-[28px]'">
                        </trix-editor>
                    </div>


                    @if ($errors->has('message') || $errors->has('message_count'))
                        <div class="label">
                            <span class="label-text-alt text-error">
                                {{ $errors->first('message') ?: $errors->first('message_count') }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mt-2">
                        <div x-show="focused || count > 0" id="char-count" class="-mt-4 text-sm text-gray-500"
                            :class="{ 'text-red-600 font-bold': count >= 255 }">
                            <span x-text="count"></span><span>/255</span>
                        </div>

                        <div x-show="focused || count > 0" class="flex items-center justify-end ml-auto mb-2">
                            <button type="submit" class="btn btn-primary btn-sm duration-0">
                                Chirp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="-mt-2" :class>
            @forelse ($chirp->replies as $reply)
                @include('chirps._reply', ['reply' => $reply, 'depth' => 1])
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="mt-4 text-base-content/60">No replies yet. Be the first to reply!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
