import { initializeApp } from 'firebase/app';

import {
    getMessaging,
    getToken,
    isSupported,
    onMessage,
} from 'firebase/messaging';


const firebaseConfig = {

    apiKey:
        import.meta.env.VITE_FIREBASE_API_KEY,

    authDomain:
        import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,

    projectId:
        import.meta.env.VITE_FIREBASE_PROJECT_ID,

    storageBucket:
        import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,

    messagingSenderId:
        import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,

    appId:
        import.meta.env.VITE_FIREBASE_APP_ID,
};


const firebaseApp =
    initializeApp(firebaseConfig);


let foregroundUnsubscribe = null;

export async function initializeWebNotifications({
    requestPermission = false,
    onForegroundMessage = null,
} = {}) {

    console.log('[FCM] Starting initialization');


    if (!('Notification' in window)) {

        console.warn(
            '[FCM] Notifications API is not supported'
        );

        return {
            supported: false,
        };
    }


    if (!('serviceWorker' in navigator)) {

        console.warn(
            '[FCM] Service Worker is not supported'
        );

        return {
            supported: false,
        };
    }


    const supported =
        await isSupported();


    console.log(
        '[FCM] Firebase supported:',
        supported
    );


    if (!supported) {

        return {
            supported: false,
        };
    }


    let permission =
        Notification.permission;


    console.log(
        '[FCM] Current permission:',
        permission
    );


    if (
        permission === 'default' &&
        requestPermission
    ) {

        permission =
            await Notification.requestPermission();


        console.log(
            '[FCM] Permission after request:',
            permission
        );
    }


    if (permission !== 'granted') {

        console.warn(
            '[FCM] Notification permission not granted:',
            permission
        );


        return {
            supported: true,
            permission,
        };
    }


    console.log(
        '[FCM] Registering service worker...'
    );


    const registration =
        await navigator.serviceWorker.register(
            '/firebase-messaging-sw.js'
        );


    console.log(
        '[FCM] Service worker registered:',
        registration.scope
    );


    const messaging =
        getMessaging(firebaseApp);


    console.log(
        '[FCM] Requesting token...'
    );


    const token =
        await getToken(
            messaging,
            {

                vapidKey:
                    import.meta.env
                        .VITE_FIREBASE_VAPID_KEY,

                serviceWorkerRegistration:
                    registration,

            }
        );

    if (!token) {

        console.warn(
            '[FCM] Firebase returned no token'
        );


        return {
            supported: true,
            permission,
            token: null,
        };
    }


    console.log(
        '[FCM] Saving token to Laravel...'
    );


    const response =
        await window.axios.post(
            '/store-fcm-token',
            {
                fcm_token: token,
            }
        );


    console.log(
        '[FCM] Laravel response:',
        response.data
    );


    if (
        onForegroundMessage &&
        !foregroundUnsubscribe
    ) {

        foregroundUnsubscribe =
            onMessage(
                messaging,
                onForegroundMessage
            );
    }


    return {

        supported: true,

        permission,

        token,

    };
}

