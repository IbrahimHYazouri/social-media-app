<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class UserRoleChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Group $group,
        public string $role
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Your role in this group {$this->group->name} has been changed to {$this->role}",
            'target_route' => 'groups.show',
            'target_params' => ['group' => $this->group->slug]
        ];
    }
}
