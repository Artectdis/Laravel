<x-layout>
    {{-- @if ($errors->any())
        {{ dd($errors->all())}}
    @endif --}}
    <x-slot:title>
        Home Feed
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold">Latest Chirps</h1>

        <!-- Chirp Form -->
        <div class="card bg-base-100 shadow mt-8 min-h-[104px]">
            <div class="card-body !py-0 justify-center">
                <form method="POST" action="/chirps" novalidate>
                    @csrf
                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message" value="{{ old('message') }}">

                        <div x-data="{
                            content: '{{ addslashes(old('message', '')) }}',
                            count: {{ $oldMessageLength ?? 0 }},
                            focused: {{ old('message') ? 'true' : 'false' }}
                        }" @focusin="focused = true" @focusout="focused = false" x-cloak>

                            <div x-show="focused || count > 0" x-transition x-cloak class="mt-2">
                                <trix-toolbar id="chirp_toolbar"></trix-toolbar>
                            </div>

                            <div class="transition-all duration-200 ease-in-out">

                                <trix-editor input="message_hidden" toolbar="chirp_toolbar"
                                    placeholder="What's on your mind?" @trix-focus="focused = true"
                                    @trix-blur="if(count === 0) focused = false"
                                    @trix-change="count = $event.target.editor.getDocument().toString().length - 1"
                                    class="!pt-5 !pl-4 trix-reply-editor trix-content textarea align-middle textarea-bordered w-full transition-all duration-200
                        {{ $errors->has('message') ? 'textarea-error' : '' }}"
                                    :class="focused ?
                                        'rounded-lg h-auto !min-h-[8rem] focus:outline-none focus:ring-2 focus:ring-blue-200 [.dark-mode-filter_&]:focus:ring-blue-600' :
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

                            <div class="flex justify-between items-center">
                                <div x-show="focused || count > 0" id="char-count" class="-mt-2 text-sm text-gray-500"
                                    :class="{ 'text-red-600 font-bold': count >= 255 }">
                                    <span x-text="count"></span><span>/255</span>
                                </div>

                                <div x-show="focused || count > 0" class="flex items-center mt-2 justify-end ml-auto">
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

        <!-- Feed -->
        <div class="space-y-4 mt-8">
            <div class="max-w-2xl mx-auto">
                <!-- ... any other headers or forms you have ... -->

                <!-- Feed -->
                <div class="mt-8">
                    @livewire('chirp-list')
                </div>
            </div>
        </div>
    </div>
</x-layout>
<script>
    function formatTimeAgo(utcDateString) {
        // Parse the ISO string with UTC timezone (+00:00)
        const utcDate = new Date(utcDateString);
        const now = new Date();
        console.log('UTC Date:', utcDate);
        console.log('Now:', now);
        // Both getTime() returns epoch in UTC, so direct subtraction works
        const secondsAgo = Math.floor((now - utcDate) / 1000);

        if (secondsAgo < 0) return 'in the future';
        if (secondsAgo < 60) return 'just now';

        const minutesAgo = Math.floor(secondsAgo / 60);
        if (minutesAgo < 60) return `${minutesAgo}m ago`;

        const hoursAgo = Math.floor(minutesAgo / 60);
        if (hoursAgo < 24) return `${hoursAgo}h ago`;

        const daysAgo = Math.floor(hoursAgo / 24);
        return `${daysAgo}d ago`;
    }

    document.querySelectorAll('.chirp-time').forEach(el => {
        const dateTime = el.getAttribute('datetime');
        el.textContent = formatTimeAgo(dateTime);
    });

    document.addEventListener("trix-change", function(event) {
        const editorElement = event.target;
        const editor = editorElement.editor;
        const countDisplay = document.getElementById("char-count");

        const text = editor.getDocument().toString().trim();

        if (text.length > 255) {
            const truncatedText = text.substring(0, 255);
            editor.setSelectedRange([0, editor.getDocument().getLength()]);
            editor.insertString(truncatedText);
        }
    });
</script>
