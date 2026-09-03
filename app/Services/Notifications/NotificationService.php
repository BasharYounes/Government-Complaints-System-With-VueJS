<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Throwable;


class NotificationService
{
    public function __construct(
        private readonly Messaging $messaging
    ) {}


    public function send(
        User $user,
        string $type,
        array $data = []
    ): Notification {

        $content = $this->resolveContent(
            $type,
            $data
        );


        $url = $this->resolveUrl(
            $type,
            $data
        );


        $notification = $this->store(
            $user,
            $type,
            $content,
            [
                ...$data,
                'url' => $url,
            ]
        );


        $this->sendWebPush(
            $user,
            $notification,
            $content,
            $url
        );


        return $notification;
    }


    private function resolveContent(
        string $type,
        array $data
    ): array {

        $template = config(
            "notifications.templates.{$type}",
            config('notifications.fallback')
        );


        return [

            'title' =>
                $this->replacePlaceholders(
                    $template['title'],
                    $data
                ),

            'body' =>
                $this->replacePlaceholders(
                    $template['body'],
                    $data
                ),
        ];
    }


    private function replacePlaceholders(
        string $text,
        array $data
    ): string {

        foreach ($data as $key => $value) {

            if (
                ! is_scalar($value) &&
                $value !== null
            ) {
                continue;
            }


            $text = str_replace(
                '{{'.$key.'}}',
                (string) $value,
                $text
            );
        }


        return $text;
    }


    private function resolveUrl(
        string $type,
        array $data
    ): string {

        if (
            in_array(
                $type,
                [
                    'complaint_status_changed',
                    'RequestAdditionalInformation',
                ],
                true
            )
            &&
            isset($data['complaint_id'])
        ) {

            return url(
                '/complaints/'
                .$data['complaint_id']
            );
        }


        if ($type === 'account_locked') {

            return url(
                '/user-log-in'
            );
        }


        return url('/home');
    }


    private function store(
        User $user,
        string $type,
        array $content,
        array $data
    ): Notification {

        return Notification::create([

            'user_id' =>
                $user->id,

            'type' =>
                $type,

            'title' =>
                $content['title'],

            'body' =>
                $content['body'],

            'data' =>
                $data,

            'is_read' =>
                false,
        ]);
    }


    private function sendWebPush(
        User $user,
        Notification $notification,
        array $content,
        string $url
    ): void {

        if (blank($user->fcm_token)) {
            return;
        }


        try {

            $message = CloudMessage::fromArray([

                'token' =>
                    $user->fcm_token,


                'data' => [

                    'notification_id' =>
                        (string) $notification->id,

                    'type' =>
                        (string) $notification->type,

                    'url' =>
                        $url,

                ],


                'webpush' => [

                    'notification' => [

                        'title' =>
                            $content['title'],

                        'body' =>
                            $content['body'],

                    ],

                    'fcm_options' => [

                        'link' =>
                            $url,

                    ],
                ],
            ]);


            $this->messaging->send(
                $message
            );

        } catch (Throwable $exception) {

            Log::error(
                'Failed to send Firebase notification.',
                [
                    'user_id' =>
                        $user->id,

                    'notification_id' =>
                        $notification->id,

                    'error' =>
                        $exception->getMessage(),
                ]
            );
        }
    }
}
