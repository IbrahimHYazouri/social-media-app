<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

final class InvitationToGroup extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Group $group,
        public GroupUser $groupUser,
        public int $expiresInHours,
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
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "You have been invited to join the group {$this->group->name} by user {$this->groupUser->creator->username}. Invitation link expires in {$this->expiresInHours} hours.",
            'target_route' => 'groups.invitations.accept',
            'target_params' => ['token' => $this->groupUser->token],
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("App.Models.User.{$this->groupUser->user_id}");
    }
}
