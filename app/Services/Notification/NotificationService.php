<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Models\WorkReport;
use App\Models\Notification;

class NotificationService
{
    public function send(
        User $user,
        string $title,
        string $message,
        string $type,
        ?WorkReport $workReport = null,
        ?string $icon = null,
        ?string $actionUrl = null
    ) {
        return Notification::create([
            'user_id' => $user->id,
            'work_report_id' => $workReport?->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'action_url' => $actionUrl,
            'is_read' => false,
        ]);
    }

    public function markAsRead(
        int $notificationId
    ) {
        $notification = Notification::findOrFail(
            $notificationId
        );

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAllAsRead(
        User $user
    ) {
        Notification::where(
            'user_id',
            $user->id
        )->where(
            'is_read',
            false
        )->update([

            'is_read' => true,

            'read_at' => now(),

        ]);
    }
}
