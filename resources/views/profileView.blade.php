<x-layout>
    <main class="p-8">
        <div>
            <div class="flex flex-col gap-4 mt-4">
                <div class="flex flex-row justify-between">
                    <img loading="lazy" src="{{ $user->avatar_url }}"
                        class="size-30 rounded-full justify-self-end self-top outline-white outline-3">
                    @if ($editPermission)
                        <button class="btn btn-ghost py-2 px-4"><a href="/profile">Edit Profile</a></button>
                    @endif
                </div>
                <div>
                    <div class="min-w-0">
                        <div class="flex flex-row gap-2 items-center">
                            <h1 class="text-3xl font-bold break-words self-center w-auto text-wrap">{{ $user->name }}
                            </h1>
                            @if ($user->email_verified_at)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-[2em] hover:text-[#4A40A9] hover:scale-110 origin-center text-[#4697E7] transition-all duration-200 ease-in-out">
                                    <path fill-rule="evenodd"
                                        d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                        clip-rule="evenodd" />
                                    <title>Verified</title>
                                </svg>
                            @endif
                        </div>

                        <h1
                            class="ml-0.5 -mt-1 text-sm text-gray-500 font-bold break-words self-center text-wrap w-full">
                            {{ $user->email }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="mt">
                <div class="flex flex-col text-sm text-gray-500 mt-2">
                    <div class="flex flex-row gap-1 items-center">
                        @if ($user->phone_number)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 size-[1em]">
                                <path fill-rule="evenodd"
                                    d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $user->phone_number }}
                        @endif
                    </div>
                    <div class="flex flex-row gap-1 items-center text-gray-500">
                        @if ($user->birthday)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="size-[1em]">
                                <path
                                    d="m4.75 1-.884.884a1.25 1.25 0 1 0 1.768 0L4.75 1ZM11.25 1l-.884.884a1.25 1.25 0 1 0 1.768 0L11.25 1ZM8.884 1.884 8 1l-.884.884a1.25 1.25 0 1 0 1.768 0ZM4 7a2 2 0 0 0-2 2v1.034c.347 0 .694-.056 1.028-.167l.47-.157a4.75 4.75 0 0 1 3.004 0l.47.157a3.25 3.25 0 0 0 2.056 0l.47-.157a4.75 4.75 0 0 1 3.004 0l.47.157c.334.111.681.167 1.028.167V9a2 2 0 0 0-2-2V5.75a.75.75 0 0 0-1.5 0V7H8.75V5.75a.75.75 0 0 0-1.5 0V7H5.5V5.75a.75.75 0 0 0-1.5 0V7ZM14 11.534a4.749 4.749 0 0 1-1.502-.244l-.47-.157a3.25 3.25 0 0 0-2.056 0l-.47.157a4.75 4.75 0 0 1-3.004 0l-.47-.157a3.25 3.25 0 0 0-2.056 0l-.47.157A4.748 4.748 0 0 1 2 11.534V13a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-1.466Z" />
                            </svg>

                            {{ $user->birthday }}
                        @endif
                    </div>
                    <div class="flex flex-row gap-1 items-center text-gray-500"><svg xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16" fill="currentColor" class="size-[1em]">
                            <path
                                d="M5.75 7.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM5 10.25a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0ZM10.25 7.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM7.25 8.25a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0ZM8 9.5A.75.75 0 1 0 8 11a.75.75 0 0 0 0-1.5Z" />
                            <path fill-rule="evenodd"
                                d="M4.75 1a.75.75 0 0 0-.75.75V3a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2V1.75a.75.75 0 0 0-1.5 0V3h-5V1.75A.75.75 0 0 0 4.75 1ZM3.5 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v4.5a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7Z"
                                clip-rule="evenodd" />
                        </svg>Joined {{ $user->created_at->format('F d, Y') }}</div>
                    <div class="flex flex-row gap-1 items-center text-gray-500">
                        @if ($user->email_verified_at)
                            Verified on: {{ $user->email_verified_at?->format('F d, Y') }}
                        @else
                            Unverified
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end"></div>
            </div>
            <h2 id="chirps" class="text-2xl font-bold my-2">Chirps</h2>
            <hr class="border-gray-300 mb-4" />
            <div class="flex flex-col gap-y-2">
                @foreach ($chirps as $chirp)
                    <x-chirp :chirp="$chirp" />
                @endforeach
            </div>
</x-layout>
