<script setup>
import {
    ref,
    onBeforeUnmount,
} from 'vue';

import {
    Head,
    Link,
    useForm,
} from '@inertiajs/vue3';


const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});


const imageInput = ref(null);

const previewUrl = ref(
    props.user.image ?? null
);

const successMessage = ref('');


const form = useForm({

    name:
        props.user.name ?? '',

    phone:
        props.user.phone ?? '',

    image:
        null,
});


const avatarLetter = () => {

    return props.user.name
        ?.trim()
        ?.charAt(0)
        ?? 'م';
};


const selectImage = () => {

    imageInput.value?.click();
};


const handleImageChange = (event) => {

    const file =
        event.target.files?.[0];


    if (!file) {
        return;
    }


    form.image = file;


    if (
        previewUrl.value &&
        previewUrl.value.startsWith('blob:')
    ) {
        URL.revokeObjectURL(
            previewUrl.value
        );
    }


    previewUrl.value =
        URL.createObjectURL(file);
};


const submit = () => {

    successMessage.value = '';


    form.post(
        '/edit-profile',
        {
            preserveScroll: true,

            forceFormData: true,

            onSuccess: () => {

                successMessage.value =
                    'تم تحديث معلومات الملف الشخصي بنجاح';

                form.image = null;
            },
        }
    );
};


onBeforeUnmount(() => {

    if (
        previewUrl.value &&
        previewUrl.value.startsWith('blob:')
    ) {
        URL.revokeObjectURL(
            previewUrl.value
        );
    }
});
</script>


<template>

    <Head title="الملف الشخصي — نظام الشكاوى الحكومية" />


    <div class="app">

        <div class="bg-shape s1"></div>
        <div class="bg-shape s2"></div>
        <div class="bg-grid"></div>


        <!-- ═════════ TOP BAR ═════════ -->

        <header class="topbar">

            <div class="topbar-brand">

                <div class="brand-seal">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 56 56"
                        fill="none"
                    >

                        <circle
                            cx="28"
                            cy="28"
                            r="26"
                            stroke="rgba(255,255,255,0.3)"
                            stroke-width="1.5"
                        />

                        <path
                            d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z"
                            fill="rgba(255,255,255,0.95)"
                        />

                    </svg>

                </div>


                <span class="brand-name">
                    نظام الشكاوى الحكومية
                </span>

            </div>


            <Link
                href="/home"
                class="back-btn"
            >

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path d="M19 12H5"/>

                    <path d="M12 19l-7-7 7-7"/>

                </svg>

                الرئيسية

            </Link>

        </header>


        <!-- ═════════ MAIN ═════════ -->

        <main class="main">


            <!-- Page heading -->

            <section class="page-header">

                <div>

                    <p class="page-kicker">
                        حساب المواطن
                    </p>

                    <h1 class="page-title">
                        الملف الشخصي
                    </h1>

                    <p class="page-subtitle">
                        عرض وتحديث معلومات حسابك الشخصي
                    </p>

                </div>

            </section>


            <!-- Success -->

            <div
                v-if="successMessage"
                class="success-alert"
            >

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        d="M22 11.08V12a10 10 0 1 1-5.93-9.14"
                    />

                    <polyline
                        points="22 4 12 14.01 9 11.01"
                    />

                </svg>

                {{ successMessage }}

            </div>


            <div class="profile-grid">


                <!-- ═════════ Profile summary ═════════ -->

                <aside class="profile-card">


                    <!-- Avatar -->

                    <div class="avatar-wrapper">

                        <div class="avatar">

                            <img
                                v-if="previewUrl"
                                :src="previewUrl"
                                alt="الصورة الشخصية"
                            />

                            <span v-else>
                                {{ avatarLetter() }}
                            </span>

                        </div>


                        <button
                            type="button"
                            class="change-image-btn"
                            @click="selectImage"
                        >

                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"
                                />

                                <circle
                                    cx="12"
                                    cy="13"
                                    r="4"
                                />

                            </svg>

                            تغيير الصورة

                        </button>


                        <input
                            ref="imageInput"
                            type="file"
                            accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                            hidden
                            @change="handleImageChange"
                        />

                    </div>


                    <div class="profile-name">

                        <h2>
                            {{ user.name }}
                        </h2>

                        <span>
                            مواطن
                        </span>

                    </div>


                    <div class="profile-divider"></div>


                    <!-- Email -->

                    <div class="summary-item">

                        <div class="summary-icon">

                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                                />

                                <polyline
                                    points="22,6 12,13 2,6"
                                />

                            </svg>

                        </div>


                        <div>

                            <span class="summary-label">
                                البريد الإلكتروني
                            </span>

                            <span class="summary-value">
                                {{ user.email }}
                            </span>

                        </div>

                    </div>


                    <!-- Verification -->

                    <div class="summary-item">

                        <div class="summary-icon">

                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M9 12l2 2 4-4"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                />

                            </svg>

                        </div>


                        <div>

                            <span class="summary-label">
                                حالة البريد
                            </span>

                            <span
                                class="verification"
                                :class="{
                                    verified:
                                        user.email_verified
                                }"
                            >

                                {{
                                    user.email_verified
                                        ? 'موثّق'
                                        : 'غير موثّق'
                                }}

                            </span>

                        </div>

                    </div>

                </aside>


                <!-- ═════════ Edit Form ═════════ -->

                <section class="form-panel">


                    <div class="panel-header">

                        <div class="panel-icon">

                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                />

                                <circle
                                    cx="12"
                                    cy="7"
                                    r="4"
                                />

                            </svg>

                        </div>


                        <div>

                            <h2>
                                المعلومات الشخصية
                            </h2>

                            <p>
                                يمكنك تعديل معلومات حسابك من هنا
                            </p>

                        </div>

                    </div>


                    <form
                        class="profile-form"
                        @submit.prevent="submit"
                    >


                        <!-- Name -->

                        <div class="form-group">

                            <label for="name">

                                الاسم الكامل

                            </label>


                            <div class="input-wrapper">

                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    />

                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="4"
                                    />

                                </svg>


                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    autocomplete="name"
                                    placeholder="أدخل الاسم الكامل"
                                />

                            </div>


                            <p
                                v-if="form.errors.name"
                                class="field-error"
                            >

                                {{ form.errors.name }}

                            </p>

                        </div>


                        <!-- Email -->

                        <div class="form-group">

                            <label>
                                البريد الإلكتروني
                            </label>


                            <div class="input-wrapper disabled">

                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                                    />

                                    <polyline
                                        points="22,6 12,13 2,6"
                                    />

                                </svg>


                                <input
                                    :value="user.email"
                                    type="email"
                                    disabled
                                />

                            </div>


                            <p class="field-hint">
                                البريد الإلكتروني مرتبط بعملية التحقق من الحساب.
                            </p>

                        </div>


                        <!-- Phone -->

                        <div class="form-group">

                            <label for="phone">
                                رقم الهاتف
                            </label>


                            <div class="input-wrapper">

                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2
                                           19.79 19.79 0 0 1-8.63-3.07
                                           19.5 19.5 0 0 1-6-6
                                           19.79 19.79 0 0 1-3.07-8.67
                                           A2 2 0 0 1 3.89 2h3
                                           a2 2 0 0 1 2 1.72
                                           c.12.9.33 1.78.62 2.63
                                           a2 2 0 0 1-.45 2.11
                                           L7.79 9.73
                                           a16 16 0 0 0 6 6
                                           l1.27-1.27
                                           a2 2 0 0 1 2.11-.45
                                           c.85.29 1.73.5 2.63.62
                                           A2 2 0 0 1 22 16.92z"
                                    />

                                </svg>


                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="text"
                                    autocomplete="tel"
                                    placeholder="أدخل رقم الهاتف"
                                    dir="ltr"
                                />

                            </div>


                            <p
                                v-if="form.errors.phone"
                                class="field-error"
                            >

                                {{ form.errors.phone }}

                            </p>

                        </div>


                        <!-- Image error -->

                        <p
                            v-if="form.errors.image"
                            class="field-error image-error"
                        >

                            {{ form.errors.image }}

                        </p>


                        <!-- Footer -->

                        <div class="form-footer">

                            <Link
                                href="/home"
                                class="cancel-btn"
                            >
                                إلغاء
                            </Link>


                            <button
                                type="submit"
                                class="save-btn"
                                :disabled="form.processing"
                            >

                                <svg
                                    v-if="!form.processing"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        d="M19 21H5a2 2 0 0 1-2-2V5
                                           a2 2 0 0 1 2-2h11l5 5v11
                                           a2 2 0 0 1-2 2z"
                                    />

                                    <polyline
                                        points="17 21 17 13 7 13 7 21"
                                    />

                                    <polyline
                                        points="7 3 7 8 15 8"
                                    />

                                </svg>


                                <span
                                    v-if="form.processing"
                                    class="spinner"
                                ></span>


                                {{
                                    form.processing
                                        ? 'جاري الحفظ...'
                                        : 'حفظ التعديلات'
                                }}

                            </button>

                        </div>

                    </form>

                </section>

            </div>

        </main>

    </div>

</template>


<style scoped>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


.app {

    min-height: 100vh;

    background: #05112b;

    direction: rtl;

    font-family:
        'Segoe UI',
        Tahoma,
        system-ui,
        sans-serif;

    color:
        rgba(255,255,255,.85);

    position: relative;

    overflow-x: hidden;
}


/* ═════════ Background ═════════ */

.bg-shape {

    position: fixed;

    border-radius: 50%;

    filter: blur(100px);

    pointer-events: none;

    z-index: 0;
}


.s1 {

    width: 600px;

    height: 600px;

    background:
        radial-gradient(
            circle,
            #1a4a8a 0%,
            transparent 70%
        );

    top: -200px;

    right: -150px;

    opacity: .5;
}


.s2 {

    width: 450px;

    height: 450px;

    background:
        radial-gradient(
            circle,
            #c9952a 0%,
            transparent 70%
        );

    bottom: -150px;

    left: 10%;

    opacity: .13;
}


.bg-grid {

    position: fixed;

    inset: 0;

    z-index: 0;

    background-image:

        linear-gradient(
            rgba(255,255,255,.02) 1px,
            transparent 1px
        ),

        linear-gradient(
            90deg,
            rgba(255,255,255,.02) 1px,
            transparent 1px
        );

    background-size:
        48px 48px;

    pointer-events: none;
}


/* ═════════ Top Bar ═════════ */

.topbar {

    position: sticky;

    top: 0;

    z-index: 50;

    height: 60px;

    padding:
        0 2rem;

    display: flex;

    align-items: center;

    justify-content: space-between;

    background:
        rgba(5,17,43,.88);

    backdrop-filter:
        blur(16px);

    border-bottom:
        1px solid
        rgba(255,255,255,.07);
}


.topbar-brand {

    display: flex;

    align-items: center;

    gap: 10px;
}


.brand-seal {

    width: 36px;

    height: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #1a4a8a,
            #0d2d5e
        );

    border:
        1px solid
        rgba(255,255,255,.15);

    border-radius: 9px;
}


.brand-name {

    font-size: .85rem;

    font-weight: 600;

    color:
        rgba(255,255,255,.85);
}


.back-btn {

    display: flex;

    align-items: center;

    gap: 7px;

    padding:
        8px 13px;

    color:
        rgba(255,255,255,.65);

    background:
        rgba(255,255,255,.05);

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius: 9px;

    text-decoration: none;

    font-size: .78rem;

    transition:
        all .2s;
}


.back-btn:hover {

    color: #fff;

    background:
        rgba(255,255,255,.1);
}


/* ═════════ Main ═════════ */

.main {

    max-width: 1100px;

    margin: 0 auto;

    padding: 2rem;

    position: relative;

    z-index: 1;
}


.page-header {

    margin-bottom:
        1.5rem;
}


.page-kicker {

    color: #d4a843;

    font-size: .75rem;

    margin-bottom: 5px;
}


.page-title {

    color: #fff;

    font-size: 1.65rem;

    margin-bottom: 6px;
}


.page-subtitle {

    color:
        rgba(255,255,255,.4);

    font-size: .8rem;
}


/* ═════════ Alert ═════════ */

.success-alert {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom:
        1rem;

    padding:
        11px 14px;

    color: #4ade80;

    background:
        rgba(34,197,94,.1);

    border:
        1px solid
        rgba(34,197,94,.25);

    border-radius: 10px;

    font-size: .78rem;
}


/* ═════════ Grid ═════════ */

.profile-grid {

    display: grid;

    grid-template-columns:
        300px
        minmax(0,1fr);

    gap: 1rem;

    align-items: start;
}


/* ═════════ Profile Card ═════════ */

.profile-card {

    padding:
        1.6rem;

    text-align: center;

    background:
        rgba(255,255,255,.04);

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius: 15px;

    backdrop-filter:
        blur(8px);
}


.avatar-wrapper {

    display: flex;

    flex-direction: column;

    align-items: center;
}


.avatar {

    width: 110px;

    height: 110px;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #1a4a8a,
            #d4a843
        );

    border:
        3px solid
        rgba(212,168,67,.25);

    border-radius: 50%;

    font-size: 2.4rem;

    font-weight: 700;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,.3);
}


.avatar img {

    width: 100%;

    height: 100%;

    object-fit: cover;
}


.change-image-btn {

    display: flex;

    align-items: center;

    gap: 6px;

    margin-top: 12px;

    padding:
        7px 12px;

    color: #d4a843;

    background:
        rgba(212,168,67,.08);

    border:
        1px solid
        rgba(212,168,67,.2);

    border-radius: 8px;

    cursor: pointer;

    font-family: inherit;

    font-size: .72rem;

    transition:
        all .2s;
}


.change-image-btn:hover {

    background:
        rgba(212,168,67,.15);
}


.profile-name {

    margin-top: 18px;
}


.profile-name h2 {

    color: #fff;

    font-size: 1rem;

    margin-bottom: 4px;
}


.profile-name > span {

    color: #d4a843;

    font-size: .7rem;
}


.profile-divider {

    height: 1px;

    margin:
        1.3rem 0;

    background:
        rgba(255,255,255,.07);
}


.summary-item {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-top: 13px;

    text-align: right;
}


.summary-icon {

    width: 34px;

    height: 34px;

    min-width: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    color:
        rgba(255,255,255,.55);

    background:
        rgba(255,255,255,.05);

    border-radius: 8px;
}


.summary-label {

    display: block;

    color:
        rgba(255,255,255,.3);

    font-size: .62rem;

    margin-bottom: 2px;
}


.summary-value {

    display: block;

    max-width: 190px;

    overflow: hidden;

    color:
        rgba(255,255,255,.7);

    font-size: .72rem;

    text-overflow: ellipsis;

    white-space: nowrap;

    direction: ltr;
}


.verification {

    display: inline-flex;

    padding:
        3px 8px;

    color: #facc15;

    background:
        rgba(234,179,8,.12);

    border-radius: 20px;

    font-size: .65rem;
}


.verification.verified {

    color: #4ade80;

    background:
        rgba(34,197,94,.12);
}


/* ═════════ Form Panel ═════════ */

.form-panel {

    background:
        rgba(255,255,255,.04);

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius: 15px;

    overflow: hidden;

    backdrop-filter:
        blur(8px);
}


.panel-header {

    display: flex;

    align-items: center;

    gap: 11px;

    padding:
        1.1rem 1.4rem;

    border-bottom:
        1px solid
        rgba(255,255,255,.07);
}


.panel-icon {

    width: 38px;

    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #d4a843;

    background:
        rgba(212,168,67,.1);

    border-radius: 9px;
}


.panel-header h2 {

    color: #fff;

    font-size: .9rem;

    margin-bottom: 3px;
}


.panel-header p {

    color:
        rgba(255,255,255,.3);

    font-size: .68rem;
}


.profile-form {

    display: flex;

    flex-direction: column;

    gap: 1.25rem;

    padding:
        1.5rem;
}


.form-group label {

    display: block;

    margin-bottom: 7px;

    color:
        rgba(255,255,255,.62);

    font-size: .74rem;

    font-weight: 500;
}


.input-wrapper {

    display: flex;

    align-items: center;

    gap: 9px;

    padding:
        0 12px;

    background:
        rgba(255,255,255,.045);

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius: 10px;

    transition:
        all .2s;
}


.input-wrapper:focus-within {

    border-color:
        rgba(212,168,67,.45);

    box-shadow:
        0 0 0 3px
        rgba(212,168,67,.08);
}


.input-wrapper > svg {

    min-width: 16px;

    color:
        rgba(255,255,255,.28);
}


.input-wrapper input {

    width: 100%;

    padding:
        11px 0;

    color: #fff;

    background:
        transparent;

    border: 0;

    outline: 0;

    font-family: inherit;

    font-size: .8rem;
}


.input-wrapper input::placeholder {

    color:
        rgba(255,255,255,.18);
}


.input-wrapper.disabled {

    opacity: .55;

    cursor:
        not-allowed;
}


.input-wrapper.disabled input {

    cursor:
        not-allowed;
}


.field-error {

    margin-top: 6px;

    color: #f87171;

    font-size: .68rem;
}


.image-error {

    margin-top: -8px;
}


.field-hint {

    margin-top: 6px;

    color:
        rgba(255,255,255,.25);

    font-size: .65rem;
}


/* ═════════ Footer ═════════ */

.form-footer {

    display: flex;

    justify-content: flex-end;

    gap: 9px;

    padding-top: 6px;
}


.cancel-btn,
.save-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    min-height: 39px;

    padding:
        9px 17px;

    border-radius: 9px;

    font-family: inherit;

    font-size: .76rem;

    font-weight: 600;

    text-decoration: none;

    transition:
        all .2s;
}


.cancel-btn {

    color:
        rgba(255,255,255,.58);

    background:
        rgba(255,255,255,.045);

    border:
        1px solid
        rgba(255,255,255,.1);
}


.cancel-btn:hover {

    color: #fff;

    background:
        rgba(255,255,255,.08);
}


.save-btn {

    color: #05112b;

    background:
        linear-gradient(
            135deg,
            #d4a843,
            #f0c862
        );

    border: 0;

    cursor: pointer;
}


.save-btn:hover:not(:disabled) {

    transform:
        translateY(-1px);

    box-shadow:
        0 6px 18px
        rgba(212,168,67,.25);
}


.save-btn:disabled {

    opacity: .6;

    cursor:
        not-allowed;
}


.spinner {

    width: 14px;

    height: 14px;

    border:
        2px solid
        rgba(5,17,43,.25);

    border-top-color:
        #05112b;

    border-radius: 50%;

    animation:
        spin .7s linear infinite;
}


@keyframes spin {

    to {
        transform:
            rotate(360deg);
    }
}


/* ═════════ Responsive ═════════ */

@media (max-width: 800px) {

    .profile-grid {

        grid-template-columns:
            1fr;
    }


    .profile-card {

        max-width:
            none;
    }
}


@media (max-width: 640px) {

    .main {

        padding: 1rem;
    }


    .topbar {

        padding:
            0 1rem;
    }


    .brand-name {

        display: none;
    }


    .form-footer {

        flex-direction:
            column-reverse;
    }


    .cancel-btn,
    .save-btn {

        width: 100%;
    }
}

</style>
