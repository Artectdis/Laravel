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
        <div class="card bg-base-100 shadow mt-8 min-h-[222px]">
            <div class="card-body">
                <form method="POST" action="/chirps" novalidate>
                    @csrf
                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message" value="{{ old('message') }}">

                        <div x-data="{
                            content: '{{ addslashes(old('message', '')) }}',
                            count: {{ $oldMessageLength ?? 0 }},
                            focused: false
                        }" @focusin="focused = true" @focusout="focused = false" x-cloak>

                            <div x-show="focused" x-transition x-cloak class="mb-2">
                                <trix-toolbar id="chirp_toolbar"></trix-toolbar>
                            </div>

                            <div class="min-h-[150px]">
                                <trix-editor input="message_hidden" toolbar="chirp_toolbar"
                                    placeholder="What's on your mind?" @trix-focus="focused = true"
                                    @trix-blur="focused = false"
                                    @trix-change="count = $event.target.editor.getDocument().toString().trim().length"
                                    class="trix-content textarea textarea-bordered h-auto min-h-[150px] w-full @error('message') textarea-error @enderror">
                                </trix-editor>
                            </div>

                            @error('message')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror

                            <div class="flex justify-between items-center mt-2">
                                <div x-show="focused" id="char-count" class="text-sm text-gray-500"
                                    :class="{ 'text-red-600 font-bold': count >= 255 }">
                                    <span x-text="count"></span><span>/255</span>
                                </div>

                                <div class="flex items-center justify-end ml-auto">
                                    <button type="submit" class="btn btn-primary btn-sm duration-0">
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
