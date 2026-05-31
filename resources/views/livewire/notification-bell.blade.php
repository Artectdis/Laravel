<?php

use function Livewire\Volt\{state, on, mount};

state([
    'unreadCount' => 0,
    'userId' => null,
    'notifications' => fn() => auth()->user()?->notifications()->latest()->take(10)->get() ?? collect(),
]);

mount(function () {
    $this->userId = auth()->id();
    $this->unreadCount = auth()->user()?->unreadNotifications()->count() ?? 0;
});

on([
    'echo-private:App.Models.User.{userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => function ($event) {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->notifications()->latest()->take(10)->get();
        $this->dispatch('notify-unread-count', count: $this->unreadCount);
    },
]);

$markAllAsRead = function () {
    auth()->user()->unreadNotifications->markAsRead();
    $this->unreadCount = 0;
    $this->notifications = auth()->user()->notifications()->latest()->take(10)->get();
    $this->dispatch('notify-unread-count', count: 0);
};

$markAsRead = function ($notificationId) {
    $notification = auth()->user()->notifications()->find($notificationId);

    if ($notification) {
        $notification->markAsRead();
    }

    $this->unreadCount = auth()->user()->unreadNotifications()->count();
    $this->notifications = auth()->user()->notifications()->latest()->take(10)->get();
    $this->dispatch('notify-unread-count', count: $this->unreadCount);
};

?>

<div x-data="{ unreadCount: {{ $unreadCount }} }" x-on:notify-unread-count.window="unreadCount = $event.detail.count">
    <x-ts-dropdown position="bottom-end">
        <x-slot:action>
            <button x-cloak x-data="{ notifs: localStorage.getItem('notifications') !== 'false' }" class="relative p-2 focus:outline-none group transition-opacity"
                :class="notifs ? '' : 'opacity-40'"
                @click="
        show = !show;
        notifs = !notifs;
        localStorage.setItem('notifications', notifs ? 'true' : 'false');
        $dispatch('notifications-changed', { enabled: notifs });
    "
                :title="notifs ? 'Disable notifications' : 'Enable notifications'">
                <x-ts-icon name="bell"
                    class="size-6 text-[#808080] group-hover:text-[#4697E7] transition-colors !cursor-pointer" />

                <span x-show="notifs && unreadCount > 0"
                    class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white border-2 border-white !pointer-events-none"
                    x-text="unreadCount">
                </span>
            </button>

        </x-slot:action>

        <x-ts-dropdown.items static separator class="!p-0 !bg-transparent !border-0 !cursor-default">
            <div class="w-100 !bg-transparent">
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-700 mr-6">Notifications</h3>
                    <button x-show="unreadCount > 0" wire:click="markAllAsRead"
                        class="mr-auto text-xs text-blue-400 dark:text-gray-300 dark:hover:!text-blue-800 !cursor-pointer pr-2 hover:text-blue-800 font-medium keep-color">
                        Mark all as read
                    </button>
                </div>

                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($notifications as $notification)
                        <div
                            class="group border-b border-gray-50 dark:border-gray-300 dark:bg-red mark-w-full dark:hover:!bg-gray-50/80 px-4 py-3 text-sm hover:bg-gray-50 transition-colors relative flex items-center justify-between {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30' }}">
                            <a href="{{ $notification->data['url'] ?? '/' }}" class="flex-1 pr-4 max-w-full">
                                <div class="text-gray-800 leading-tight flex flex-col items-center max-w-full">
                                    <span title="{{ $notification->data['user'] ?? 'User' }}"
                                        class="truncate max-w-full mr-auto font-semibold">
                                        {{ Str::limit($notification->data['user'] ?? 'User', 12) }}
                                    </span>
                                    <span class="truncate mr-auto">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </a>

                            @if (!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')" title="Mark as read"
                                    class="invisible group-hover:visible text-gray-400 !-ml-12 mt-8 hover:text-blue-600 transition-colors !cursor-pointer p-1">
                                    <x-ts-icon name="check" class="size-4" />
                                </button>
                            @endif
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
