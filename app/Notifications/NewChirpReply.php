<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewChirpReply extends Notification implements ShouldQueue
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
            'message' => "{$this->chirp->user->name} posted a reply on your chirp!",
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
