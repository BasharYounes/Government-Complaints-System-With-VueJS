<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class NotificationRepository
{
    public function latestForUser(
        User $user,
        int $limit = 20
    ): Collection {

        return $user
            ->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }


    public function findForUser(
        int $notificationId,
        User $user
    ): Notification {

        return $user
            ->notifications()
            ->whereKey($notificationId)
            ->firstOrFail();
    }


    public function unreadCount(
        User $user
    ): int {

        return $user
            ->notifications()
            ->where('is_read', false)
            ->count();
    }


    public function markAsRead(
        Notification $notification
    ): void {

        if ($notification->is_read) {
            return;
        }


        $notification->update([
            'is_read' => true,
        ]);
    }


    public function markAllAsRead(
        User $user
    ): void {

        $user
            ->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);
    }
}
