<?php
use function Livewire\Volt\{state, mount};

// Define URL-tracked states first
state(['feed' => 'global'])->url();
state(['tag' => ''])->url();

// Define internal states (passed from controller)
state(['availableTags' => [], 'oldMessageLength' => 0]);

mount(function ($availableTags, $oldMessageLength) {
    $this->availableTags = $availableTags;
    $this->oldMessageLength = $oldMessageLength;
});
?>

<div>
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Latest Chirps</h1>

            <div class="flex bg-gray-800 p-1 rounded-lg">
                <button wire:click="$set('feed', 'global')"
                    :class="$wire.feed === 'global' ? 'bg-gray-700 text-white' : 'text-gray-400'"
                    class="px-4 py-1 rounded-md transition-colors">
                    Global
                </button>
                <button wire:click="$set('feed', 'following')"
                    :class="$wire.feed === 'following' ? 'bg-gray-700 text-white' : 'text-gray-400'"
                    class="px-4 py-1 rounded-md transition-colors">
                    Following
                </button>
            </div>
        </div>
        <!-- Write START -->

        <div class="card bg-base-100 shadow mt-8 min-h-[104px]">
            <div class="card-body !py-0 justify-center">
                <form method="POST" action="/chirps" novalidate>
                    @csrf
                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message" value="{{ old('message') }}">

                        <div x-cloak x-data="{
                            content: '{{ addslashes(old('message', '')) }}',
                            count: {{ $oldMessageLength ?? 0 }},
                            focused: {{ old('message') ? 'true' : 'false' }},
                            showTagInput: false,
                            tagInput: '',
                            tags: [],
                            addTag() {
                                const name = this.tagInput.trim().toLowerCase().replace(/\s+/g, '-');
                                if (name && !this.tags.includes(name) && this.tags.length < 5) {
                                    this.tags.push(name);
                                }
                                this.tagInput = '';
                            },
                            removeTag(tag) {
                                this.tags = this.tags.filter(t => t !== tag);
                            },
                            isActive() {
                                return this.focused || this.count > 0 || this.showTagInput;
                            }
                        }"
                            @focusout.away="focused = false; if(count === 0 && tags.length === 0) showTagInput = false"
                            @focusin="focused = true">

                            <div x-show="isActive()" x-transition x-cloak class="mt-2">
                                <trix-toolbar id="chirp_toolbar"></trix-toolbar>
                            </div>

                            <div class="transition-all duration-200 ease-in-out">
                                <trix-editor input="message_hidden" toolbar="chirp_toolbar"
                                    placeholder="What's on your mind?" @trix-focus="focused = true"
                                    @trix-blur="if(count === 0) focused = false"
                                    @trix-change="count = $event.target.editor.getDocument().toString().length - 1"
                                    class="!pt-5 !pl-4 trix-reply-editor trix-content textarea align-middle textarea-bordered w-full transition-all duration-200
                                        {{ $errors->has('message') ? 'textarea-error' : '' }}"
                                    :class="isActive() ?
                                        'rounded-lg h-auto !min-h-[8rem] focus:outline-none focus:ring-2 focus:ring-blue-200 [.dark-mode-filter_&]:focus:ring-blue-600' :
                                        '!min-h-[4rem] overflow-hidden py-2 leading-[28px]'">
                                </trix-editor>

                                <div class="mt-2 flex flex-col gap-2 w-full">

                                    <div x-show="isActive()" class="flex items-center gap-2">
                                        <button type="button" @mousedown.prevent @click="showTagInput = !showTagInput"
                                            class="btn btn-ghost btn-circle !h-8 !w-8 !min-h-0 !p-1 flex items-center justify-center transition-colors"
                                            :class="tags.length > 0 || showTagInput ? '!text-primary' : '!text-base-content/50'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#636363"
                                                class="!size-5">
                                                <path fill-rule="evenodd"
                                                    d="M11.097 1.515a.75.75 0 0 1 .589.882L10.666 7.5h4.47l1.079-5.397a.75.75 0 1 1 1.47.294L16.665 7.5h3.585a.75.75 0 0 1 0 1.5h-3.885l-1.2 6h3.585a.75.75 0 0 1 0 1.5h-3.885l-1.08 5.397a.75.75 0 1 1-1.47-.294l1.02-5.103h-4.47l-1.08 5.397a.75.75 0 1 1-1.47-.294l1.02-5.103H3.75a.75.75 0 0 1 0-1.5h3.885l1.2-6H5.25a.75.75 0 0 1 0-1.5h3.885l1.08-5.397a.75.75 0 0 1 .882-.588ZM10.365 9l-1.2 6h4.47l1.2-6h-4.47Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </button>

                                        <template x-for="tag in tags" :key="tag">
                                            <span
                                                class="badge badge-sm dark:text-black bg-primary text-white border-none px-2 py-1 rounded-full flex items-center text-xs">
                                                #<span x-text="tag" class="-mx-2"></span>
                                                <button type="button" @mousedown.prevent @click="removeTag(tag)"
                                                    class="ml-1 opacity-60 hover:opacity-100 leading-none">×</button>
                                            </span>
                                        </template>
                                    </div>

                                    <div x-show="showTagInput && isActive()" x-transition x-cloak
                                        class="bg-base-200 rounded-lg p-3 flex flex-col gap-1">

                                        @if ($availableTags->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 items-center">
                                                @foreach ($availableTags as $tag)
                                                    <button type="button" @mousedown.prevent
                                                        @click="tags.includes('{{ $tag->name }}') ? removeTag('{{ $tag->name }}') : (tags.length < 5 && tags.push('{{ $tag->name }}'))"
                                                        :class="tags.includes('{{ $tag->name }}') ?
                                                            'opacity-100 ring-2' :
                                                            'opacity-40 hover:opacity-70'"
                                                        class="badge badge-sm px-2 py-1 rounded-full transition-all cursor-pointer text-xs
                                                        text-white 
                                                        dark:text-black !border-none !outline-none"
                                                        style="background-color: {{ $tag->color }}">
                                                        #{{ $tag->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- New tag input --}}
                                        <div class="flex gap-2 items-center">
                                            <input type="text" x-model="tagInput" @keydown.enter.prevent="addTag()"
                                                @keydown.comma.prevent="addTag()" placeholder="New tag..."
                                                class="input input-sm input-bordered w-40 text-sm" maxlength="30">
                                            <button type="button" @mousedown.prevent @click="addTag()"
                                                class="btn btn-ghost !h-8 !min-h-0 btn-xs">
                                                + Add
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Hidden inputs submitted with the form --}}
                                    <template x-for="tag in tags" :key="tag">
                                        <input type="hidden" name="tags[]" :value="tag">
                                    </template>
                                </div>
                            </div>

                            @if ($errors->has('message') || $errors->has('message_count'))
                                <div class="label">
                                    <span class="label-text-alt text-error">
                                        {{ $errors->first('message') ?: $errors->first('message_count') }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <div x-show="isActive()" class="-mt-2 text-sm text-gray-500"
                                    :class="{ 'text-red-600 font-bold': count >= 255 }">
                                    <span x-text="count"></span><span>/255</span>
                                </div>
                                <div x-show="isActive()" class="flex items-center mt-2 justify-end ml-auto">
                                    <button type="submit" class="btn btn-primary btn-sm duration-0 mb-2">
                                        Chirp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Write END --}}

        <!-- Feed -->
        <div class="space-y-4 mt-8">
            <div class="max-w-2xl mx-auto">
                <div class="mt-8">
                    @livewire('chirp-list')
                </div>
            </div>
        </div>
    </div>
</div>
