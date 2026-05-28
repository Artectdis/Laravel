<?php

use function Livewire\Volt\{state, on, mount};

state([
    'unreadCount' => 0,
    'notifications' => fn() => auth()->user()?->notifications()->latest()->take(10)->get() ?? collect(),
]);

mount(function () {
    $this->unreadCount = auth()->user()?->unreadNotifications()->count() ?? 0;
});

on([
    'echo-private:App.Models.User.{auth.id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => function () {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->notifications()->latest()->take(10)->get();
    },
]);

$markAsRead = function () {
    auth()->user()->unreadNotifications->markAsRead();
    $this->unreadCount = 0;
    $this->notifications = auth()->user()->notifications()->latest()->take(10)->get();
};

?>

<div>
    <x-ts-dropdown position="bottom-end">
        <x-slot:action>
            <button class="relative p-2 cursor-pointer focus:outline-none group" x-on:click="show = !show">
                <x-ts-icon name="bell" class="size-6 text-[#808080] group-hover:text-[#4697E7] transition-colors" />
                @if ($unreadCount > 0)
                    <span
                        class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white border-2 border-white">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>
        </x-slot:action>

        <x-ts-dropdown.items static separator class="!p-0 !bg-transparent">
            <div class="w-80">
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                    @if ($unreadCount > 0)
                        <button wire:click="markAsRead" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Mark all as read
                        </button>
                    @endif
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <div
                            class="border-b border-gray-50 px-4 py-3 text-sm hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30' }}">
                            <p class="text-gray-800 leading-tight">
                                {{ $notification->data['message'] ?? 'New notification' }}</p>
                            <span
                                class="text-[10px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <x-ts-icon name="bell-slash" class="size-8 mx-auto text-gray-200 mb-2" />
                            <p class="text-xs text-gray-400">Your inbox is empty</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </x-ts-dropdown.items>
    </x-ts-dropdown>
</div>
