<script setup>

import {
    computed,
    ref,
} from 'vue';

import {
    Head,
    Link,
    useForm,
} from '@inertiajs/vue3';


const props = defineProps({

    complaint: {
        type: Object,
        required: true,
    },

});


/*
|--------------------------------------------------------------------------
| Edit State
|--------------------------------------------------------------------------
*/

const isEditing = ref(false);


const canEdit = computed(() => {
    return props.complaint.status === 'new';
});


/*
|--------------------------------------------------------------------------
| Edit Form
|--------------------------------------------------------------------------
*/

const form = useForm({

    type:
        props.complaint.type ?? '',

    description:
        props.complaint.description ?? '',

    location: {

        address:
            props.complaint.location?.address ?? '',

        details:
            props.complaint.location?.details ?? '',

    },

});


const startEditing = () => {

    form.clearErrors();

    isEditing.value = true;

};


const cancelEditing = () => {

    form.clearErrors();

    form.type =
        props.complaint.type ?? '';

    form.description =
        props.complaint.description ?? '';

    form.location = {

        address:
            props.complaint.location?.address ?? '',

        details:
            props.complaint.location?.details ?? '',

    };

    isEditing.value = false;

};


const updateComplaint = () => {

    form.patch(
        `/complaints/${props.complaint.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                isEditing.value = false;
            },
        }
    );

};


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

const statusMap = {

    new: {
        label: 'جديدة',
        class: 'status-new',
    },

    in_progress: {
        label: 'قيد المعالجة',
        class: 'status-progress',
    },

    completed: {
        label: 'تمت المعالجة',
        class: 'status-completed',
    },

    rejected: {
        label: 'مرفوضة',
        class: 'status-rejected',
    },

};


const currentStatus = computed(() => {

    return statusMap[props.complaint.status] ?? {

        label:
            props.complaint.status
            ?? 'غير محدد',

        class:
            'status-default',

    };

});


/*
|--------------------------------------------------------------------------
| Location
|--------------------------------------------------------------------------
*/

const hasLocation = computed(() => {

    return Boolean(

        props.complaint.location?.address
        ||
        props.complaint.location?.details

    );

});


/*
|--------------------------------------------------------------------------
| Attachments
|--------------------------------------------------------------------------
*/

const attachments = computed(() => {

    return Array.isArray(
        props.complaint.attachments
    )
        ? props.complaint.attachments
        : [];

});


const formatFileSize = (bytes) => {

    if (!bytes) {
        return '';
    }

    const size =
        Number(bytes);


    if (size < 1024) {

        return `${size} B`;

    }


    if (size < 1024 * 1024) {

        return `${(size / 1024).toFixed(1)} KB`;

    }


    return `${(
        size / (1024 * 1024)
    ).toFixed(1)} MB`;

};

</script>


<template>

    <Head
        :title="`تفاصيل الشكوى ${complaint.reference_number}`"
    />


    <div class="app">

        <div class="bg-shape s1"></div>
        <div class="bg-shape s2"></div>
        <div class="bg-grid"></div>


        <!-- ═════════════ TOP BAR ═════════════ -->

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
                href="/complaints"
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

                    <path
                        d="M12 19l-7-7 7-7"
                    />

                </svg>

                العودة إلى شكاواي

            </Link>

        </header>


        <!-- ═════════════ MAIN ═════════════ -->

        <main class="main">


            <!-- ═════════════ PAGE HEADER ═════════════ -->

            <section class="page-header">

                <div>

                    <p class="page-kicker">
                        تفاصيل الشكوى
                    </p>


                    <h1 class="page-title">
                        {{ complaint.type }}
                    </h1>


                    <div class="reference">

                        #{{ complaint.reference_number }}

                    </div>

                </div>


                <div class="header-actions">


                    <!-- EDIT BUTTON -->

                    <button
                        v-if="canEdit && !isEditing"
                        type="button"
                        class="edit-btn"
                        @click="startEditing"
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
                                d="M12 20h9"
                            />

                            <path
                                d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"
                            />

                        </svg>

                        تعديل الشكوى

                    </button>


                    <!-- NOT EDITABLE -->

                    <span
                        v-if="!canEdit"
                        class="edit-disabled-note"
                    >

                        لا يمكن تعديل الشكوى بعد بدء معالجتها

                    </span>


                    <!-- STATUS -->

                    <span
                        class="status"
                        :class="currentStatus.class"
                    >

                        {{ currentStatus.label }}

                    </span>

                </div>

            </section>


            <!-- ═════════════ CONTENT ═════════════ -->

            <div class="content-grid">


                <!-- ═════════ MAIN COMPLAINT PANEL ═════════ -->

                <section class="panel main-panel">

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
                                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                                />

                                <polyline
                                    points="14 2 14 8 20 8"
                                />

                            </svg>

                        </div>


                        <h2>
                            معلومات الشكوى
                        </h2>

                    </div>


                    <!-- ═════════ VIEW MODE ═════════ -->

                    <template v-if="!isEditing">

                        <div class="section">

                            <span class="section-label">
                                وصف الشكوى
                            </span>


                            <p class="description">

                                {{ complaint.description }}

                            </p>

                        </div>


                        <div class="details-grid">

                            <div class="detail-item">

                                <span class="detail-label">
                                    الجهة الحكومية
                                </span>


                                <span class="detail-value">

                                    {{
                                        complaint.government_entity?.name
                                        ?? 'غير محددة'
                                    }}

                                </span>

                            </div>


                            <div class="detail-item">

                                <span class="detail-label">
                                    نوع الشكوى
                                </span>


                                <span class="detail-value">

                                    {{ complaint.type }}

                                </span>

                            </div>


                            <div class="detail-item">

                                <span class="detail-label">
                                    تاريخ تقديم الشكوى
                                </span>


                                <span class="detail-value">

                                    {{ complaint.created_at }}

                                </span>

                            </div>


                            <div class="detail-item">

                                <span class="detail-label">
                                    آخر تحديث
                                </span>


                                <span class="detail-value">

                                    {{ complaint.updated_at }}

                                </span>

                            </div>

                        </div>

                    </template>


                    <!-- ═════════ EDIT MODE ═════════ -->

                    <form
                        v-else
                        class="edit-form"
                        @submit.prevent="updateComplaint"
                    >


                        <!-- General error -->

                        <div
                            v-if="form.errors.complaint"
                            class="general-error"
                        >

                            {{ form.errors.complaint }}

                        </div>


                        <!-- Type -->

                        <div class="form-group">

                            <label for="complaint-type">
                                نوع الشكوى
                            </label>


                            <input
                                id="complaint-type"
                                v-model="form.type"
                                type="text"
                                class="form-input"
                                placeholder="نوع الشكوى"
                            />


                            <p
                                v-if="form.errors.type"
                                class="field-error"
                            >

                                {{ form.errors.type }}

                            </p>

                        </div>


                        <!-- Description -->

                        <div class="form-group">

                            <label for="complaint-description">
                                وصف الشكوى
                            </label>


                            <textarea
                                id="complaint-description"
                                v-model="form.description"
                                class="form-textarea"
                                rows="6"
                                placeholder="أدخل وصف الشكوى"
                            ></textarea>


                            <p
                                v-if="form.errors.description"
                                class="field-error"
                            >

                                {{ form.errors.description }}

                            </p>

                        </div>


                        <!-- Address -->

                        <div class="form-group">

                            <label for="location-address">
                                العنوان
                            </label>


                            <input
                                id="location-address"
                                v-model="form.location.address"
                                type="text"
                                class="form-input"
                                placeholder="عنوان موقع الشكوى"
                            />


                            <p
                                v-if="form.errors['location.address']"
                                class="field-error"
                            >

                                {{
                                    form.errors[
                                        'location.address'
                                    ]
                                }}

                            </p>

                        </div>


                        <!-- Location details -->

                        <div class="form-group">

                            <label for="location-details">
                                تفاصيل الموقع
                            </label>


                            <input
                                id="location-details"
                                v-model="form.location.details"
                                type="text"
                                class="form-input"
                                placeholder="تفاصيل إضافية عن الموقع"
                            />


                            <p
                                v-if="form.errors['location.details']"
                                class="field-error"
                            >

                                {{
                                    form.errors[
                                        'location.details'
                                    ]
                                }}

                            </p>

                        </div>


                        <!-- Actions -->

                        <div class="edit-actions">

                            <button
                                type="button"
                                class="cancel-edit-btn"
                                :disabled="form.processing"
                                @click="cancelEditing"
                            >

                                إلغاء

                            </button>


                            <button
                                type="submit"
                                class="save-edit-btn"
                                :disabled="form.processing"
                            >

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


                <!-- ═════════ SIDE INFORMATION ═════════ -->

                <aside class="side-column">


                    <!-- STATUS -->

                    <section class="panel">

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

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="10"
                                    />

                                    <polyline
                                        points="12 6 12 12 16 14"
                                    />

                                </svg>

                            </div>


                            <h2>
                                حالة الشكوى
                            </h2>

                        </div>


                        <div class="status-box">

                            <span
                                class="status large"
                                :class="currentStatus.class"
                            >

                                {{ currentStatus.label }}

                            </span>


                            <p>

                                يمكنك متابعة حالة معالجة
                                شكواك من هذه الصفحة.

                            </p>

                        </div>

                    </section>


                    <!-- REFERENCE -->

                    <section class="panel">

                        <div class="panel-header">

                            <div class="panel-icon">
                                #
                            </div>


                            <h2>
                                رقم التتبع
                            </h2>

                        </div>


                        <div class="reference-box">

                            {{ complaint.reference_number }}

                        </div>

                    </section>

                </aside>

            </div>


            <!-- ═════════════ LOCATION ═════════════ -->

            <section
                v-if="hasLocation && !isEditing"
                class="panel location-panel"
            >

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
                                d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0z"
                            />

                            <circle
                                cx="12"
                                cy="10"
                                r="3"
                            />

                        </svg>

                    </div>


                    <h2>
                        موقع الشكوى
                    </h2>

                </div>


                <div class="details-grid location-content">

                    <div
                        v-if="complaint.location?.address"
                        class="detail-item"
                    >

                        <span class="detail-label">
                            العنوان
                        </span>


                        <span class="detail-value">

                            {{
                                complaint.location.address
                            }}

                        </span>

                    </div>


                    <div
                        v-if="complaint.location?.details"
                        class="detail-item"
                    >

                        <span class="detail-label">
                            تفاصيل إضافية
                        </span>


                        <span class="detail-value">

                            {{
                                complaint.location.details
                            }}

                        </span>

                    </div>

                </div>

            </section>


            <!-- ═════════════ ATTACHMENTS ═════════════ -->

            <section class="panel attachments-panel">

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
                                d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"
                            />

                        </svg>

                    </div>


                    <h2>
                        المرفقات
                    </h2>

                </div>


                <div
                    v-if="attachments.length === 0"
                    class="empty-attachments"
                >

                    لا توجد مرفقات لهذه الشكوى

                </div>


                <div
                    v-else
                    class="attachments-list"
                >

                    <a
                        v-for="attachment in attachments"
                        :key="attachment.id"
                        :href="attachment.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="attachment"
                    >

                        <div class="attachment-icon">

                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                                />

                                <polyline
                                    points="14 2 14 8 20 8"
                                />

                            </svg>

                        </div>


                        <div class="attachment-info">

                            <span class="attachment-name">

                                {{
                                    attachment.file_name
                                    ?? 'مرفق'
                                }}

                            </span>


                            <span class="attachment-meta">

                                {{ attachment.mime_type }}

                                <template
                                    v-if="attachment.file_size"
                                >

                                    ·
                                    {{
                                        formatFileSize(
                                            attachment.file_size
                                        )
                                    }}

                                </template>

                            </span>

                        </div>


                        <div class="attachment-open">

                            فتح المرفق

                        </div>

                    </a>

                </div>

            </section>


            <!-- ═════════════ BOTTOM ACTIONS ═════════════ -->

            <div class="bottom-actions">

                <Link
                    href="/complaints"
                    class="secondary-btn"
                >

                    العودة إلى قائمة الشكاوى

                </Link>


                <Link
                    href="/create-complaint"
                    class="primary-btn"
                >

                    تقديم شكوى جديدة

                </Link>

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


/* ═════════════ Background ═════════════ */

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

    width: 420px;
    height: 420px;

    background:
        radial-gradient(
            circle,
            #c9952a 0%,
            transparent 70%
        );

    bottom: -120px;
    left: 10%;

    opacity: .12;
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


/* ═════════════ Topbar ═════════════ */

.topbar {

    height: 60px;

    padding:
        0 2rem;

    display: flex;

    align-items: center;

    justify-content: space-between;

    position: sticky;

    top: 0;

    z-index: 50;

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

    border:
        1px solid
        rgba(255,255,255,.1);

    background:
        rgba(255,255,255,.05);

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


/* ═════════════ Main ═════════════ */

.main {

    max-width: 1150px;

    margin:
        0 auto;

    padding:
        2rem;

    position: relative;

    z-index: 1;
}


/* ═════════════ Page Header ═════════════ */

.page-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom:
        1.5rem;
}


.page-kicker {

    color: #d4a843;

    font-size: .74rem;

    margin-bottom:
        5px;
}


.page-title {

    color: #fff;

    font-size:
        1.65rem;

    margin-bottom:
        7px;
}


.reference {

    font-family:
        monospace;

    color:
        rgba(255,255,255,.45);

    font-size:
        .76rem;

    direction:
        ltr;

    display:
        inline-block;
}


.header-actions {

    display:
        flex;

    align-items:
        center;

    justify-content:
        flex-end;

    gap:
        10px;

    flex-wrap:
        wrap;
}


/* ═════════════ Edit Button ═════════════ */

.edit-btn {

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        7px;

    padding:
        8px 14px;

    color:
        #d4a843;

    background:
        rgba(212,168,67,.1);

    border:
        1px solid
        rgba(212,168,67,.3);

    border-radius:
        8px;

    cursor:
        pointer;

    font-family:
        inherit;

    font-size:
        .75rem;

    font-weight:
        600;

    transition:
        all .2s;
}


.edit-btn:hover {

    background:
        rgba(212,168,67,.18);

    border-color:
        rgba(212,168,67,.5);

    transform:
        translateY(-1px);
}


.edit-disabled-note {

    max-width:
        230px;

    color:
        rgba(255,255,255,.35);

    font-size:
        .68rem;

    text-align:
        center;
}


/* ═════════════ Layout ═════════════ */

.content-grid {

    display:
        grid;

    grid-template-columns:
        minmax(0,1fr)
        300px;

    gap:
        1rem;

    align-items:
        start;

    margin-bottom:
        1rem;
}


.side-column {

    display:
        flex;

    flex-direction:
        column;

    gap:
        1rem;
}


/* ═════════════ Panel ═════════════ */

.panel {

    background:
        rgba(255,255,255,.04);

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius:
        14px;

    overflow:
        hidden;

    backdrop-filter:
        blur(8px);
}


.panel-header {

    display:
        flex;

    align-items:
        center;

    gap:
        9px;

    padding:
        .9rem 1.2rem;

    border-bottom:
        1px solid
        rgba(255,255,255,.07);
}


.panel-header h2 {

    color:
        rgba(255,255,255,.82);

    font-size:
        .85rem;
}


.panel-icon {

    width:
        30px;

    height:
        30px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    color:
        #d4a843;

    background:
        rgba(212,168,67,.1);

    border-radius:
        8px;

    font-size:
        .8rem;

    font-weight:
        700;
}


/* ═════════════ Description ═════════════ */

.section {

    padding:
        1.25rem;
}


.section-label,
.detail-label {

    display:
        block;

    margin-bottom:
        6px;

    color:
        rgba(255,255,255,.3);

    font-size:
        .68rem;
}


.description {

    color:
        rgba(255,255,255,.7);

    line-height:
        1.9;

    font-size:
        .84rem;

    white-space:
        pre-line;
}


/* ═════════════ Edit Form ═════════════ */

.edit-form {

    display:
        flex;

    flex-direction:
        column;

    gap:
        1rem;

    padding:
        1.25rem;
}


.form-group label {

    display:
        block;

    margin-bottom:
        6px;

    font-size:
        .72rem;

    color:
        rgba(255,255,255,.55);
}


.form-input,
.form-textarea {

    width:
        100%;

    padding:
        10px 12px;

    color:
        #fff;

    background:
        rgba(255,255,255,.05);

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius:
        9px;

    outline:
        none;

    font-family:
        inherit;

    font-size:
        .8rem;

    transition:
        all .2s;
}


.form-input:focus,
.form-textarea:focus {

    border-color:
        rgba(212,168,67,.45);

    box-shadow:
        0 0 0 3px
        rgba(212,168,67,.07);
}


.form-textarea {

    resize:
        vertical;

    min-height:
        130px;
}


.edit-actions {

    display:
        flex;

    justify-content:
        flex-end;

    gap:
        8px;

    margin-top:
        5px;
}


.cancel-edit-btn,
.save-edit-btn {

    min-height:
        38px;

    padding:
        8px 14px;

    border-radius:
        8px;

    font-family:
        inherit;

    cursor:
        pointer;

    font-size:
        .75rem;

    transition:
        all .2s;
}


.cancel-edit-btn {

    background:
        rgba(255,255,255,.05);

    border:
        1px solid
        rgba(255,255,255,.1);

    color:
        rgba(255,255,255,.65);
}


.cancel-edit-btn:hover:not(:disabled) {

    background:
        rgba(255,255,255,.09);

    color:
        #fff;
}


.save-edit-btn {

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        7px;

    border:
        0;

    background:
        linear-gradient(
            135deg,
            #d4a843,
            #f0c862
        );

    color:
        #05112b;

    font-weight:
        700;
}


.save-edit-btn:hover:not(:disabled) {

    transform:
        translateY(-1px);
}


.cancel-edit-btn:disabled,
.save-edit-btn:disabled {

    opacity:
        .5;

    cursor:
        not-allowed;
}


.field-error {

    margin-top:
        5px;

    color:
        #f87171;

    font-size:
        .68rem;
}


.general-error {

    padding:
        10px 12px;

    color:
        #f87171;

    background:
        rgba(239,68,68,.08);

    border:
        1px solid
        rgba(239,68,68,.18);

    border-radius:
        8px;

    font-size:
        .72rem;
}


.spinner {

    width:
        13px;

    height:
        13px;

    border:
        2px solid
        rgba(5,17,43,.3);

    border-top-color:
        #05112b;

    border-radius:
        50%;

    animation:
        spin .7s linear infinite;
}


@keyframes spin {

    to {
        transform:
            rotate(360deg);
    }

}


/* ═════════════ Details ═════════════ */

.details-grid {

    display:
        grid;

    grid-template-columns:
        repeat(
            2,
            minmax(0,1fr)
        );

    gap:
        1rem;

    padding:
        0 1.25rem
        1.25rem;
}


.detail-item {

    padding:
        12px;

    background:
        rgba(255,255,255,.03);

    border:
        1px solid
        rgba(255,255,255,.05);

    border-radius:
        10px;
}


.detail-value {

    color:
        rgba(255,255,255,.75);

    font-size:
        .78rem;

    word-break:
        break-word;
}


/* ═════════════ Status ═════════════ */

.status {

    display:
        inline-flex;

    padding:
        5px 11px;

    border-radius:
        20px;

    white-space:
        nowrap;

    font-size:
        .7rem;

    font-weight:
        600;
}


.status.large {

    padding:
        7px 14px;

    font-size:
        .76rem;
}


.status-new {

    color:
        #facc15;

    background:
        rgba(234,179,8,.15);
}


.status-progress {

    color:
        #93c5fd;

    background:
        rgba(59,130,246,.15);
}


.status-completed {

    color:
        #4ade80;

    background:
        rgba(34,197,94,.15);
}


.status-rejected {

    color:
        #f87171;

    background:
        rgba(239,68,68,.15);
}


.status-default {

    color:
        rgba(255,255,255,.65);

    background:
        rgba(255,255,255,.08);
}


.status-box {

    padding:
        1.25rem;
}


.status-box p {

    margin-top:
        12px;

    color:
        rgba(255,255,255,.35);

    font-size:
        .72rem;

    line-height:
        1.6;
}


/* ═════════════ Reference ═════════════ */

.reference-box {

    padding:
        1.25rem;

    color:
        #d4a843;

    font-family:
        monospace;

    font-size:
        .82rem;

    direction:
        ltr;

    text-align:
        center;

    word-break:
        break-all;
}


/* ═════════════ Location ═════════════ */

.location-panel {

    margin-bottom:
        1rem;
}


.location-content {

    padding-top:
        1.25rem;
}


/* ═════════════ Attachments ═════════════ */

.attachments-panel {

    margin-bottom:
        1rem;
}


.empty-attachments {

    padding:
        2rem;

    text-align:
        center;

    color:
        rgba(255,255,255,.3);

    font-size:
        .78rem;
}


.attachments-list {

    display:
        grid;

    gap:
        8px;

    padding:
        1rem 1.25rem;
}


.attachment {

    display:
        flex;

    align-items:
        center;

    gap:
        11px;

    padding:
        10px 12px;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid
        rgba(255,255,255,.06);

    border-radius:
        10px;

    text-decoration:
        none;

    transition:
        all .2s;
}


.attachment:hover {

    background:
        rgba(255,255,255,.06);

    border-color:
        rgba(212,168,67,.25);
}


.attachment-icon {

    width:
        34px;

    height:
        34px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    flex-shrink:
        0;

    background:
        rgba(59,130,246,.1);

    color:
        #93c5fd;

    border-radius:
        8px;
}


.attachment-info {

    min-width:
        0;

    flex:
        1;

    display:
        flex;

    flex-direction:
        column;

    gap:
        2px;
}


.attachment-name {

    color:
        rgba(255,255,255,.72);

    font-size:
        .76rem;

    overflow:
        hidden;

    text-overflow:
        ellipsis;

    white-space:
        nowrap;
}


.attachment-meta {

    color:
        rgba(255,255,255,.3);

    font-size:
        .64rem;
}


.attachment-open {

    color:
        #d4a843;

    font-size:
        .7rem;

    font-weight:
        600;

    white-space:
        nowrap;
}


/* ═════════════ Bottom Actions ═════════════ */

.bottom-actions {

    display:
        flex;

    justify-content:
        flex-end;

    gap:
        10px;

    margin-top:
        1.2rem;
}


.secondary-btn,
.primary-btn {

    padding:
        10px 17px;

    border-radius:
        9px;

    text-decoration:
        none;

    font-size:
        .78rem;

    font-weight:
        600;

    transition:
        all .2s;
}


.secondary-btn {

    color:
        rgba(255,255,255,.65);

    background:
        rgba(255,255,255,.05);

    border:
        1px solid
        rgba(255,255,255,.1);
}


.secondary-btn:hover {

    color:
        #fff;

    background:
        rgba(255,255,255,.09);
}


.primary-btn {

    color:
        #05112b;

    background:
        linear-gradient(
            135deg,
            #d4a843,
            #f0c862
        );
}


.primary-btn:hover {

    transform:
        translateY(-1px);
}


/* ═════════════ Responsive ═════════════ */

@media (max-width: 800px) {

    .content-grid {

        grid-template-columns:
            1fr;

    }

}


@media (max-width: 640px) {

    .main {

        padding:
            1rem;

    }


    .topbar {

        padding:
            0 1rem;

    }


    .brand-name {

        display:
            none;

    }


    .page-header {

        align-items:
            flex-start;

        flex-direction:
            column;

    }


    .header-actions {

        width:
            100%;

        justify-content:
            flex-start;

    }


    .details-grid {

        grid-template-columns:
            1fr;

    }


    .edit-actions {

        flex-direction:
            column-reverse;

    }


    .cancel-edit-btn,
    .save-edit-btn {

        width:
            100%;

    }


    .bottom-actions {

        flex-direction:
            column;

    }


    .secondary-btn,
    .primary-btn {

        text-align:
            center;

    }

}

</style>
