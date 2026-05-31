<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChirp extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $chirp) {}

    public function via($notifiable): array
    {
        // Use 'database' for the inbox list and 'broadcast' for real-time updates
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'user' => $this->chirp->user->name,
            'message' => "posted a new chirp!",
            'url' => url("/chirps/{$this->chirp->id}"),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'count' => $notifiable->unreadNotifications()->count(),
        ]);
    }
}
