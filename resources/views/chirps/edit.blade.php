<x-layout>
    <x-slot:title>
        Edit Chirp
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Edit Chirp</h1>

        <div class="card bg-base-100 mt-8">
            <div class="card-body">
                <form method="POST" action="/chirps/{{ $chirp->id }}">
                    @csrf
                    @method('PUT')

                    <div class="form-control w-full">
                        <input id="message_hidden" type="hidden" name="message" value="{{ $chirp->message }}">
                        <div x-data="{ content: '{{ addslashes(old('message', $chirp->message)) }}', count: {{ $oldMessageLength ?? 0 }} }">
                            <!-- Trix Editor -->
                            <trix-editor input="message_hidden"
                                @trix-change="count = $event.target.editor.getDocument().toString().trim().length"
                                class="trix-content textarea textarea-bordered h-auto min-h-[150px] w-full @error('message') textarea-error @enderror">
                            </trix-editor>

                            @error('message')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror

                            <div class="flex justify-between">
                                <div class="flex justify-between">
                                    <div id="char-count" class="text-sm text-gray-500"
                                        :class="{ 'text-red-600 font-bold': count >= 255 }">
                                        <span x-text="count"></span><span>/255</span>
                                    </div>
                                </div>

                                <div class="card-actions justify-between mt-4">
                                    <a href="javascript:history.back()" class="btn btn-ghost btn-sm">
                                        Cancel
                                    </a>
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
