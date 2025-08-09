<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\GroupUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class InvitationAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected GroupUser $groupUser,
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
            'message' => "{$this->groupUser->user->name} accepted your invitation and joined {$this->groupUser->group->name}.",
            'target_route' => 'groups.show',
            'target_params' => ['group' => $this->groupUser->group->slug],
        ];
    }
}
