<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Notifications\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class SendNotificationJob implements ShouldQueue
{
    use Queueable;


    public function __construct(
        public readonly int $userId,
        public readonly string $type,
        public readonly array $data = [],
    ) {}


    public function handle(
        NotificationService $notificationService
    ): void {

        $user = User::find($this->userId);

        if (! $user) {
            return;
        }


        $notificationService->send(
            $user,
            $this->type,
            $this->data
        );
    }
}
