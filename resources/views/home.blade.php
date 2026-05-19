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
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="/chirps" novalidate>
                    @csrf
                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message" value="{{ old('message') }}">

                        <trix-editor input="message_hidden" placeholder="What's on your mind?"
                            class="trix-content textarea textarea-bordered h-auto min-h-[150px] w-full @error('message') textarea-error @enderror">
                        </trix-editor>

                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="flex justify-between">
                        <div class="flex justify-between">
                            <div id="char-count" class="text-sm text-gray-500">{{ mb_strlen(old('message')) }}</div>
                        </div>
                        <div class="mt-4 flex items-center justify-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Chirp
                            </button>
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

    document.addEventListener("trix-change", function() {
        const editor = document.querySelector("trix-editor");
        const countDisplay = document.getElementById("char-count");

        // Trix editor has a built-in method to get plain text
        // .replace(/\s+/g, '') can be chained if you want to exclude spaces
        const textLength = editor.editor.getDocument().toString().trim().length;

        countDisplay.textContent = textLength;
    });
</script>
