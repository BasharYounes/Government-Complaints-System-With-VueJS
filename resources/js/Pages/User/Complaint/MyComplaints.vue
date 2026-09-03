<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    complaints: {
        type: Array,
        default: () => [],
    },
});

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

const totalComplaints = computed(() => props.complaints.length);

const getStatus = (status) => {
    return statusMap[status] ?? {
        label: status ?? 'غير محدد',
        class: 'status-default',
    };
};
</script>

<template>
    <Head title="شكاواي — نظام الشكاوى الحكومية" />

    <div class="app">

        <div class="bg-shape s1"></div>
        <div class="bg-shape s2"></div>
        <div class="bg-grid"></div>

        <!-- ═══ TOP BAR ═══ -->
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


        <!-- ═══ MAIN ═══ -->
        <main class="main">

            <!-- Page Header -->
            <section class="page-header">

                <div>

                    <p class="page-kicker">
                        مركز متابعة الشكاوى
                    </p>

                    <h1 class="page-title">
                        شكاواي
                    </h1>

                    <p class="page-subtitle">
                        يمكنك متابعة جميع الشكاوى التي قمت بتقديمها وحالتها الحالية.
                    </p>

                </div>

                <Link
                    href="/create-complaint"
                    class="new-btn"
                >
                    <svg
                        width="17"
                        height="17"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <line
                            x1="12"
                            y1="5"
                            x2="12"
                            y2="19"
                        />

                        <line
                            x1="5"
                            y1="12"
                            x2="19"
                            y2="12"
                        />
                    </svg>

                    شكوى جديدة
                </Link>

            </section>


            <!-- Summary -->
            <section class="summary-card">

                <div class="summary-icon">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>

                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>

                <div>
                    <span class="summary-number">
                        {{ totalComplaints }}
                    </span>

                    <span class="summary-label">
                        إجمالي الشكاوى المقدمة
                    </span>
                </div>

            </section>


            <!-- Empty State -->
            <section
                v-if="complaints.length === 0"
                class="empty-panel"
            >

                <div class="empty-icon">

                    <svg
                        width="48"
                        height="48"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.4"
                    >
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>

                        <polyline points="14 2 14 8 20 8"/>

                        <line
                            x1="16"
                            y1="13"
                            x2="8"
                            y2="13"
                        />

                        <line
                            x1="16"
                            y1="17"
                            x2="8"
                            y2="17"
                        />
                    </svg>

                </div>

                <h2>
                    لا توجد شكاوى بعد
                </h2>

                <p>
                    لم تقم بتقديم أي شكوى حتى الآن.
                </p>

                <Link
                    href="/create-complaint"
                    class="empty-btn"
                >
                    تقديم أول شكوى
                </Link>

            </section>


            <!-- Complaints -->
            <section
                v-else
                class="complaints-grid"
            >

                <Link
                    v-for="complaint in complaints"
                    :key="complaint.id"
                    :href="`/complaints/${complaint.id}`"
                    class="complaint-card-link"
                >
                    <article class="complaint-card">

                        <div class="card-header">

                            <div>
                                <span class="reference">
                                    #{{ complaint.reference_number }}
                                </span>

                                <h2 class="complaint-title">
                                    {{ complaint.type }}
                                </h2>
                            </div>

                            <span
                                class="status"
                                :class="getStatus(complaint.status).class"
                            >
                                {{ getStatus(complaint.status).label }}
                            </span>

                        </div>

                        <p class="description">
                            {{ complaint.description }}
                        </p>

                        <div class="info-grid">

                            <div class="info-item">
                                <span class="info-label">
                                    الجهة الحكومية
                                </span>

                                <span class="info-value">
                                    {{
                                        complaint.government_entity?.name
                                        ?? 'غير محددة'
                                    }}
                                </span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">
                                    تاريخ التقديم
                                </span>

                                <span class="info-value">
                                    {{ complaint.created_at }}
                                </span>
                            </div>

                        </div>

                        <div class="card-footer">
                            <span>عرض التفاصيل</span>

                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </div>

                    </article>
                </Link>

            </section>

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
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    color: rgba(255, 255, 255, .88);
    position: relative;
    overflow-x: hidden;
}


/* Background */

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
    background: radial-gradient(
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
    background: radial-gradient(
        circle,
        #c9952a 0%,
        transparent 70%
    );
    bottom: -150px;
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

    background-size: 48px 48px;
    pointer-events: none;
}


/* Topbar */

.topbar {
    height: 60px;

    padding: 0 2rem;

    display: flex;
    align-items: center;
    justify-content: space-between;

    position: sticky;
    top: 0;
    z-index: 50;

    background: rgba(5, 17, 43, .88);

    backdrop-filter: blur(16px);

    border-bottom:
        1px solid rgba(255,255,255,.07);
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
        1px solid rgba(255,255,255,.15);

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

    padding: 8px 13px;

    color:
        rgba(255,255,255,.65);

    border:
        1px solid rgba(255,255,255,.1);

    background:
        rgba(255,255,255,.05);

    border-radius: 9px;

    text-decoration: none;

    font-size: .78rem;

    transition: .2s;
}

.back-btn:hover {
    background:
        rgba(255,255,255,.1);

    color: #fff;
}


/* Main */

.main {
    max-width: 1150px;

    margin: 0 auto;

    padding: 2rem;

    position: relative;

    z-index: 1;
}


/* Header */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 1.7rem 0 1.4rem;
}

.page-kicker {
    color: #d4a843;

    font-size: .74rem;

    margin-bottom: 5px;
}

.page-title {
    color: #fff;

    font-size: 1.65rem;

    margin-bottom: 6px;
}

.page-subtitle {
    font-size: .82rem;

    color:
        rgba(255,255,255,.4);
}

.new-btn {
    display: flex;
    align-items: center;
    gap: 7px;

    white-space: nowrap;

    padding: 11px 18px;

    background:
        linear-gradient(
            135deg,
            #d4a843,
            #f0c862
        );

    color: #05112b;

    font-weight: 700;

    font-size: .82rem;

    border-radius: 10px;

    text-decoration: none;

    transition: .2s;
}

.new-btn:hover {
    transform: translateY(-2px);
}


/* Summary */

.summary-card {
    width: fit-content;

    display: flex;
    align-items: center;

    gap: 12px;

    margin-bottom: 1.25rem;

    padding: 13px 18px;

    background:
        rgba(255,255,255,.04);

    border:
        1px solid rgba(255,255,255,.08);

    border-radius: 12px;
}

.summary-icon {
    width: 40px;
    height: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #d4a843;

    background:
        rgba(212,168,67,.1);

    border-radius: 10px;
}

.summary-number {
    display: block;

    color: #fff;

    font-size: 1.4rem;

    font-weight: 700;
}

.summary-label {
    display: block;

    margin-top: 2px;

    color:
        rgba(255,255,255,.4);

    font-size: .7rem;
}


/* Empty */

.empty-panel {
    padding: 5rem 2rem;

    text-align: center;

    background:
        rgba(255,255,255,.04);

    border:
        1px solid rgba(255,255,255,.08);

    border-radius: 16px;
}

.empty-icon {
    color:
        rgba(255,255,255,.2);

    margin-bottom: 15px;
}

.empty-panel h2 {
    color: #fff;

    font-size: 1rem;

    margin-bottom: 7px;
}

.empty-panel p {
    color:
        rgba(255,255,255,.38);

    font-size: .8rem;
}

.empty-btn {
    display: inline-block;

    margin-top: 18px;

    padding: 9px 20px;

    color: #d4a843;

    background:
        rgba(212,168,67,.1);

    border:
        1px solid rgba(212,168,67,.25);

    border-radius: 20px;

    text-decoration: none;

    font-size: .8rem;
}


/* Complaints grid */

.complaints-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 1rem;
}


/* Card */

.complaint-card {
    padding: 1.25rem;

    background:
        rgba(255,255,255,.04);

    border:
        1px solid rgba(255,255,255,.08);

    border-radius: 14px;

    transition: .2s;
}

.complaint-card:hover {
    transform: translateY(-2px);

    border-color:
        rgba(255,255,255,.15);

    background:
        rgba(255,255,255,.055);
}

.card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 15px;
}

.reference {
    display: block;

    font-family: monospace;

    color: #d4a843;

    font-size: .72rem;

    margin-bottom: 5px;
}

.complaint-title {
    color: #fff;

    font-size: .98rem;
}


/* Status */

.status {
    display: inline-flex;

    padding: 4px 10px;

    border-radius: 20px;

    white-space: nowrap;

    font-size: .68rem;

    font-weight: 600;
}

.status-new {
    color: #facc15;
    background: rgba(234,179,8,.15);
}

.status-progress {
    color: #93c5fd;
    background: rgba(59,130,246,.15);
}

.status-completed {
    color: #4ade80;
    background: rgba(34,197,94,.15);
}

.status-rejected {
    color: #f87171;
    background: rgba(239,68,68,.15);
}

.status-default {
    color:
        rgba(255,255,255,.6);

    background:
        rgba(255,255,255,.08);
}


/* Description */

.description {
    margin-top: 16px;

    padding-bottom: 15px;

    border-bottom:
        1px solid rgba(255,255,255,.06);

    color:
        rgba(255,255,255,.55);

    font-size: .79rem;

    line-height: 1.75;
}

.complaint-card-link {
    display: block;
    color: inherit;
    text-decoration: none;
}

.complaint-card-link .complaint-card {
    height: 100%;
    cursor: pointer;
}

.card-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;

    margin-top: 16px;
    padding-top: 12px;

    border-top: 1px solid rgba(255,255,255,.06);

    color: #d4a843;
    font-size: .72rem;
    font-weight: 600;
}

.complaint-card-link:hover .card-footer {
    gap: 10px;
}


/* Info */

.info-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 14px;

    margin-top: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;

    gap: 4px;
}

.info-label {
    color:
        rgba(255,255,255,.28);

    font-size: .65rem;
}

.info-value {
    color:
        rgba(255,255,255,.72);

    font-size: .75rem;

    word-break: break-word;
}


/* Responsive */

@media (max-width: 800px) {

    .complaints-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 640px) {

    .main {
        padding: 1rem;
    }

    .topbar {
        padding: 0 1rem;
    }

    .brand-name {
        display: none;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

}
</style>
