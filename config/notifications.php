<?php

return [

    'fallback' => [

        'title' =>
            'إشعار جديد',

        'body' =>
            'لديك إشعار جديد.',

    ],


    'templates' => [

        'complaint_status_changed' => [

            'title' =>
                'تحديث حالة الشكوى',

            'body' =>
                'تم تغيير حالة الشكوى {{reference_number}} من {{old_status}} إلى {{new_status}}.',

        ],


        'RequestAdditionalInformation' => [

            'title' =>
                'معلومات إضافية مطلوبة',

            'body' =>
                'تم طلب معلومات إضافية للشكوى {{reference_number}}: {{notes}}',

        ],


        'account_locked' => [

            'title' =>
                'تم إيقاف الحساب مؤقتًا',

            'body' =>
                'تم إيقاف حسابك بعد {{attempts}} محاولات تسجيل دخول غير ناجحة.',

        ],

    ],

];
