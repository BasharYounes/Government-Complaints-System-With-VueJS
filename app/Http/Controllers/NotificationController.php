<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepository $notificationRepository
    ) {}


    public function index(
        Request $request
    ) {

        $user = $request->user();


        $notifications =
            $this->notificationRepository
                ->latestForUser($user);


        return $this->success(

            'تم جلب الإشعارات بنجاح',

            [

                'notifications' =>
                    NotificationResource::collection(
                        $notifications
                    )->resolve(),

                'unread_count' =>
                    $this->notificationRepository
                        ->unreadCount($user),

            ]
        );
    }


    public function markAsRead(
        Request $request,
        int $id
    ) {

        $user = $request->user();


        $notification =
            $this->notificationRepository
                ->findForUser(
                    $id,
                    $user
                );


        $this->notificationRepository
            ->markAsRead(
                $notification
            );


        return $this->success(

            'تم تحديد الإشعار كمقروء',

            [

                'unread_count' =>
                    $this->notificationRepository
                        ->unreadCount($user),

            ]
        );
    }


    public function markAllAsRead(
        Request $request
    ) {

        $user = $request->user();


        $this->notificationRepository
            ->markAllAsRead($user);


        return $this->success(

            'تم تحديد جميع الإشعارات كمقروءة',

            [
                'unread_count' => 0,
            ]
        );
    }
}
