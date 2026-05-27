<x-layout>
    <x-slot:title>
        Home Feed
    </x-slot:title>

    <livewire:home-page :availableTags="$availableTags" :oldMessageLength="$oldMessageLength" />

    <script>
        function formatTimeAgo(utcDateString) {
            const utcDate = new Date(utcDateString);
            const now = new Date();
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
            el.textContent = formatTimeAgo(el.getAttribute('datetime'));
        });

        document.addEventListener("trix-change", function(event) {
            const editor = event.target.editor;
            const text = editor.getDocument().toString().trim();
            if (text.length > 255) {
                editor.setSelectedRange([0, editor.getDocument().getLength()]);
                editor.insertString(text.substring(0, 255));
            }
        });
    </script>
</x-layout>
