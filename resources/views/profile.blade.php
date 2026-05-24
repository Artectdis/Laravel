<x-layout>
    <div class="profile-page grid min-h-screen grid-cols-[280px_1fr]">

        <aside class="sticky card top-0 h-screen bg-[#f7f7f8] p-6 border-r border-gray-200">
            <nav class="space-y-2">
                <a href="#profile" class="block p-2 hover:bg-white rounded">
                    <div class="flex flex-row gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd" />
                        </svg>
                        Profile</div>
                </a>
                <a href="#chirps" class="block p-2 hover:bg-white rounded">
                    <div class="flex flex-row gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M5.337 21.718a6.707 6.707 0 0 1-.533-.074.75.75 0 0 1-.44-1.223 3.73 3.73 0 0 0 .814-1.686c.023-.115-.022-.317-.254-.543C3.274 16.587 2.25 14.41 2.25 12c0-5.03 4.428-9 9.75-9s9.75 3.97 9.75 9c0 5.03-4.428 9-9.75 9-.833 0-1.643-.097-2.417-.279a6.721 6.721 0 0 1-4.246.997Z"
                                clip-rule="evenodd" />
                        </svg>

                        Chirps</div>
                </a>
                <a href="#settings" class="block p-2 hover:bg-white rounded">
                    <div class="flex flex-row gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                                clip-rule="evenodd" />
                        </svg>

                        Settings</div>
                </a>
            </nav>
        </aside>

        <div class="p-8">

            <div>
                <div><a class="flex flex-row w-fit gap-2 !text-gray-500 items-center text-sm -mt-6 mb-8 hover:!text-blue-600 transition-colors duration-500 ease-in-out"
                        href="javascript:void(0)" {{-- cool way to back 1 or 2x depending on saved, while staying fast --}}
                        onclick="window.location.search.includes('saved=true') ? window.history.go(-2) : window.history.back()"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="size-[1em]">
                            <path fill-rule="evenodd"
                                d="M12.5 9.75A2.75 2.75 0 0 0 9.75 7H4.56l2.22 2.22a.75.75 0 1 1-1.06 1.06l-3.5-3.5a.75.75 0 0 1 0-1.06l3.5-3.5a.75.75 0 0 1 1.06 1.06L4.56 5.5h5.19a4.25 4.25 0 0 1 0 8.5h-1a.75.75 0 0 1 0-1.5h1a2.75 2.75 0 0 0 2.75-2.75Z"
                                clip-rule="evenodd" />
                        </svg>
                        Exit
                    </a></div>
                <div class="grid grid-cols-[2fr_1fr] gap-4 mt-2">
                    <div class="min-w-0">
                        <h1 class="text-3xl font-bold break-words text-wrap w-full">{{ $user->name }}</h1>
                        <p>Welcome to your settings!</p>
                    </div>
                    <div class="flex justify-end items-center gap-4">
                        <form action="/settings/avatar" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- The hidden real input -->
                            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*"
                                onchange="this.form.submit()">

                            <!-- Your styled button -->
                            <button type="button" onclick="document.getElementById('avatarInput').click()"
                                class="justify-self-end btn btn-ghost btn-sm">
                                Change
                            </button>
                        </form><img loading="lazy" src="{{ $user->avatar_url }}"
                            class="size-20 rounded-full justify-self-end self-start outline-white outline-3">
                    </div>
                </div>
            </div>
            <h2 id="profile" class="text-2xl font-bold mt-4">Your Profile</h2>
            <hr class="border-gray-300 mb-4" />
            <div>
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" class="contents" action="/settings/save/{{ $user->id }}" novalidate="">
                        @csrf
                        @method('PUT')
                        <div>Name: <input name="name" class="input input-bordered w-full resize-none "
                                maxlength="255" required="" value="{{ $user->name }}" /></div>
                        <div>Email: <input type="email" name="email"
                                class="input input-bordered w-full resize-none " maxlength="255" required=""
                                value="{{ $user->email }}" /></div>
                        <div>Phone Number: <input type="tel" name="phone_number"
                                class="input input-bordered w-full resize-none " maxlength="50"
                                placeholder="+1 555 555 5555" value="{{ $user->phone_number }}" /></div>
                        <div>Birthday: <input type="text" name="birthday"
                                class="input input-bordered w-full resize-none " max="{{ date('Y-m-d') }}"
                                placeholder="Birthday (YYYY-MM-DD)" onfocus="(this.type='date')"
                                onblur="if(!this.value) this.type='text'" value="{{ $user->birthday }}" />
                        </div>
                        <div>Joined {{ $user->created_at->format('F d, Y') }}</div>
                        <div>
                            @if ($user->verified_at)
                                Verified on: {{ $user->verified_at?->format('F d, Y') }}
                            @else
                                Unverified
                            @endif
                        </div>
                </div>
                <div class="mt-4 flex items-center justify-end"></div>
            </div>
            <div class><button type="submit" class="btn btn-primary btn-sm float-right duration-0">Save</button></div>
            </form>
            <h2 id="chirps" class="text-2xl font-bold mt-12 mb-2">Your Chirps</h2>
            <hr class="border-gray-300 mb-4" />
            <div class="flex flex-col gap-y-2">
                @forelse ($chirps as $chirp)
                    <x-chirp :chirp="$chirp" />
                @empty
                    <p class="text-gray-500 text-lg">No chirps have been created yet.</p>
                @endforelse
            </div>
            <h2 id="chirps" class="text-2xl font-bold mt-12 mb-2">Following</h2>
            <hr class="border-gray-300 mb-4" />
            <div class="flex flex-col gap-2">
                @forelse ($follows as $follow)
                    <div class="card bg-base-100 cursor-pointer relative hover:!bg-gray-200 !transition-colors !duration-500 !ease-in-out"
                        onclick="if(!window.getSelection().toString()) { Livewire.navigate('/profile/{{ $follow->id }}') }">
                        <div class="card-body">
                            <div class="flex space-x-3">
                                @php $profileUrl = $follow->name ? "/profile/{$follow->id}" : "#"; @endphp

                                <div class="avatar relative z-20">
                                    <a href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                                        <div class="size-10 rounded-full">
                                            <img loading="lazy" src="{{ $follow->avatar_url }}"
                                                alt="{{ $follow->name }}'s avatar" class="rounded-full" />
                                        </div>
                                    </a>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex justify-between w-full">
                                        <div class="flex gap-1 flex-wrap">
                                            <a class="text-sm font-semibold hover:underline !text-black"
                                                href="{{ $profileUrl }}" onclick="event.stopPropagation()">
                                                {{ $follow->name }}
                                            </a>
                                            @if ($follow?->email_verified_at)
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
                                    </div>
                                    <div class="w-full trix-content flex">
                                        <div class="flex-1 text-sm text-base-content/60">
                                            {{ $follow->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-lg">No users have been followed yet.</p>
                @endforelse
            </div>

            <h2 id="settings" class="text-2xl font-bold mt-12 mb-2">Your Settings</h2>
            <hr class="border-gray-300 mb-4" />
            <div class="flex flex-col gap-2">
                <div class="flex flex-row gap-2 w-56 card p-4 outline-0"><svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" class="size-6 fill-[#808080]">
                        <path fill-rule="evenodd"
                            d="M9.528 1.718a.75.75 0 0 1 .162.819A8.97 8.97 0 0 0 9 6a9 9 0 0 0 9 9 8.97 8.97 0 0 0 3.463-.69.75.75 0 0 1 .981.98 10.503 10.503 0 0 1-9.694 6.46c-5.799 0-10.5-4.7-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 0 1 .818.162Z"
                            clip-rule="evenodd" />
                    </svg>
                    Dark Mode <input type="checkbox" id="dark-mode-toggle" class="toggle toggle-primary ml-auto"
                        checked="checked" />
                </div>

                <div class="flex flex-row gap-2 w-56 card p-4 outline-0"><svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" class="size-6 fill-[#808080]">
                        <path
                            d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                        <path fill-rule="evenodd"
                            d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z"
                            clip-rule="evenodd" />
                    </svg>

                    Notifications <input type="checkbox" class="toggle toggle-primary ml-auto" checked="checked" />
                </div>
            </div>
            <form action="/settings/delete/{{ $user->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn hover:bg-red-500 bg-white mt-12 hover:text-white text-red-500"
                    onclick="return confirm('Are you sure you want to delete your account?')">
                    <div class="flex flex-row gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="size-6 fill-current">
                            <path fill-rule="evenodd"
                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="currentColor text-base">Delete Account</p>
                    </div>
                </button>
                <div class="my-96"> </div>
            </form>
        </div>
    </div>

    <script>
        const checkbox = document.getElementById('dark-mode-toggle');
        checkbox.checked = document.documentElement.classList.contains('dark-mode-filter');

        checkbox.addEventListener('change', () => {
            document.documentElement.classList.toggle('dark-mode-filter', checkbox.checked); // html +- dark
            localStorage.setItem('theme', checkbox.checked ? 'dark' : 'light'); // theme.dark
        });
    </script>
</x-layout>
