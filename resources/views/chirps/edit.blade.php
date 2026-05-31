<x-layout>
    <x-slot:title>Edit Chirp</x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Edit Chirp</h1>

        <div class="card bg-base-100 mt-8 min-h-[104px]" x-cloak x-data="{
            count: {{ $oldMessageLength ?? 0 }},
            focused: true,
            showTagInput: false,
            tagInput: '',
            tags: @js($chirpTags),
            availableTags: @js($availableTags),
            _trixDialogOpen: false,
            addTag() {
                const name = this.tagInput.trim().toLowerCase().replace(/\s+/g, '-');
                if (name && !this.tags.some(t => t.name === name) && this.tags.length < 5) {
                    this.tags.push({ name: name, color: '#6b7280' });
                }
                this.tagInput = '';
            },
            removeTag(tag) {
                this.tags = this.tags.filter(t => t.name !== tag);
            },
            isActive() {
                return this.focused || this.count > 0 || this.showTagInput;
            }
        }"
            @mousedown.prevent="focused = true" @mousedown.self.prevent="focused = true"
            @trix-toolbar-dialog-show.window="focused = true; _trixDialogOpen = true"
            @trix-toolbar-dialog-hide.window="focused = true; _trixDialogOpen = false"
            @focus.capture="if($event.target.closest('trix-toolbar')) { focused = true; }"
            @focusout="
                setTimeout(() => {
                    const card = $el;
                    const active = document.activeElement;
                    if (!card.contains(active) && !_trixDialogOpen) {
                        focused = false;
                        if (count === 0 && tags.length === 0) showTagInput = false;
                    }
                }, 200)"
            @focusin="focused = true">

            <div class="card-body !py-0 justify-center">
                <form method="POST" action="/chirps/{{ $chirp->id }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message"
                            value="{{ old('message', $chirp->message) }}">

                        <div>
                            <div class="mt-2" :class="isActive() ? '' : 'invisible h-0 overflow-hidden'">
                                <trix-toolbar id="chirp_toolbar"></trix-toolbar>
                            </div>

                            <div class="transition-all duration-200 ease-in-out" @mousedown.stop>
                                <trix-editor input="message_hidden" toolbar="chirp_toolbar"
                                    placeholder="What's on your mind?" @trix-focus="focused = true"
                                    @trix-change="count = $event.target.editor.getDocument().toString().length - 1"
                                    class="!pt-5 !pl-4 trix-reply-editor trix-content textarea align-middle textarea-bordered w-full transition-all duration-200
                                        {{ $errors->has('message') ? 'textarea-error' : '' }}"
                                    :class="isActive() ?
                                        'rounded-lg h-auto !min-h-[8rem] focus:outline-none focus:ring-2 focus:ring-blue-200' :
                                        '!min-h-[4rem] overflow-hidden py-2 leading-[28px]'">
                                </trix-editor>

                                <div class="mt-2 flex flex-col gap-2 w-full">

                                    {{-- Tag toggle + selected tag badges --}}
                                    <div x-show="isActive()" class="flex items-center gap-2" @mousedown.prevent>
                                        <button type="button" @click="showTagInput = !showTagInput"
                                            class="btn btn-ghost btn-circle !h-8 !w-8 !min-h-0 !p-1 flex items-center justify-center transition-colors"
                                            :class="tags.length > 0 || showTagInput ? '!text-primary' : '!text-base-content/50'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#636363"
                                                class="!size-5">
                                                <path fill-rule="evenodd"
                                                    d="M11.097 1.515a.75.75 0 0 1 .589.882L10.666 7.5h4.47l1.079-5.397a.75.75 0 1 1 1.47.294L16.665 7.5h3.585a.75.75 0 0 1 0 1.5h-3.885l-1.2 6h3.585a.75.75 0 0 1 0 1.5h-3.885l-1.08 5.397a.75.75 0 1 1-1.47-.294l1.02-5.103h-4.47l-1.08 5.397a.75.75 0 1 1-1.47-.294l1.02-5.103H3.75a.75.75 0 0 1 0-1.5h3.885l1.2-6H5.25a.75.75 0 0 1 0-1.5h3.885l1.08-5.397a.75.75 0 0 1 .882-.588ZM10.365 9l-1.2 6h4.47l1.2-6h-4.47Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <template x-for="tag in tags" :key="tag.name">
                                            <span
                                                class="badge badge-sm dark:text-black text-white border-none px-2 py-1 rounded-full flex items-center text-xs"
                                                :style="`background-color: ${tag.color}`">
                                                #<span x-text="tag.name" class="-mx-2"></span>
                                                <button type="button" @click="removeTag(tag.name)"
                                                    class="ml-1 opacity-60 hover:opacity-100 leading-none">×</button>
                                            </span>
                                        </template>
                                    </div>

                                    {{-- Tag picker panel --}}
                                    <div x-show="showTagInput && isActive()" x-transition x-cloak
                                        x-data="{ search: '' }"
                                        class="bg-white rounded-lg p-3 flex flex-col gap-2 dark:bg-[#ccc]"
                                        @mousedown="if (!['INPUT', 'BUTTON', 'TEXTAREA'].includes($event.target.tagName)) $event.preventDefault()">

                                        <input type="text" x-model="search" placeholder="Search tags..."
                                            class="input input-sm input-bordered w-full text-sm" />

                                        <div class="flex flex-col max-h-60 overflow-y-auto rounded-lg custom-scrollbar [&::-webkit-scrollbar]:!w-2"
                                            @mousedown="if (!['INPUT', 'BUTTON', 'TEXTAREA'].includes($event.target.tagName)) $event.preventDefault()">

                                            <template
                                                x-for="tag in availableTags.filter(t => t.name.includes(search.toLowerCase().trim()))"
                                                :key="tag.name">
                                                <div class="flex items-center justify-between p-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-300 transition-colors border-b border-gray-100 dark:border-gray-400 last:border-0"
                                                    @mousedown.prevent
                                                    @click="tags.some(t => t.name === tag.name)
                                                        ? removeTag(tag.name)
                                                        : (tags.length < 5 && tags.push({ name: tag.name, color: tag.color }))"
                                                    :class="tags.some(t => t.name === tag.name) ?
                                                        'bg-blue-100 hover:!bg-blue-200' :
                                                        ''">

                                                    <div class="flex items-center gap-3">
                                                        <span
                                                            class="px-2 py-0.5 rounded-full text-[11px] text-white dark:text-black transition-all"
                                                            :class="tags.some(t => t.name === tag.name) ?
                                                                'opacity-100 scale-105' : 'opacity-70'"
                                                            :style="`background-color: ${tag.color}`">
                                                            #<span x-text="tag.name"></span>
                                                        </span>
                                                        <span class="text-xs text-gray-400 dark:text-gray-500"
                                                            x-text="tag.caption"></span>
                                                    </div>

                                                    <template x-if="tags.some(t => t.name === tag.name)">
                                                        <x-ts-icon name="check" class="h-4 w-4 text-primary-500" />
                                                    </template>
                                                    <template x-if="!tags.some(t => t.name === tag.name)">
                                                        <x-ts-icon name="plus" class="h-4 w-4 text-gray-300" />
                                                    </template>
                                                </div>
                                            </template>

                                            <template
                                                x-if="availableTags.filter(t => t.name.includes(search.toLowerCase().trim())).length === 0">
                                                <div class="flex flex-col items-center gap-2 py-4">
                                                    <p class="text-sm text-gray-400">No tag found.</p>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- New tag input, only when no results --}}
                                        <div class="flex gap-2 items-center pt-2 border-t border-gray-100"
                                            x-show="availableTags.filter(t => t.name.includes(search.toLowerCase().trim())).length === 0">
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
                                    <template x-for="tag in tags" :key="tag.name">
                                        <input type="hidden" name="tags[]" :value="tag.name">
                                    </template>

                                </div>
                            </div>

                            @if ($errors->has('message') || $errors->has('message_count') || $errors->has('message_lines'))
                                <div class="label">
                                    <span class="label-text-alt text-error">
                                        {{ $errors->first('message') ?: ($errors->first('message_count') ?: $errors->first('message_lines')) }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <div class="-mt-2 text-sm text-gray-500"
                                    :class="{ 'text-red-600 font-bold': count >= 255 }">
                                    <span x-text="count"></span><span>/255</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2 justify-end ml-auto mb-2">
                                    <a href="javascript:history.back()" class="btn btn-ghost btn-sm">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-sm duration-0">
                                        Update Chirp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
