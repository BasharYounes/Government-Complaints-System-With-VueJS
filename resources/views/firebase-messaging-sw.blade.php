importScripts(
    'https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js'
);

importScripts(
    'https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js'
);


firebase.initializeApp({

    apiKey:
        @json(config('services.firebase_web.api_key')),

    authDomain:
        @json(config('services.firebase_web.auth_domain')),

    projectId:
        @json(config('services.firebase_web.project_id')),

    storageBucket:
        @json(config('services.firebase_web.storage_bucket')),

    messagingSenderId:
        @json(config('services.firebase_web.messaging_sender_id')),

    appId:
        @json(config('services.firebase_web.app_id')),
});


firebase.messaging();
