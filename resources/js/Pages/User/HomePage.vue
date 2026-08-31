<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({
            total: 12,
            pending: 3,
            resolved: 8,
            rejected: 1,
        }),
    },
    recentComplaints: {
        type: Array,
        default: () => [],
    },
    notifications: {
        type: Array,
        default: () => [],
    },
});

const unreadCount = computed(() => props.notifications.filter(n => !n.read).length);
const showNotifications = ref(false);

const statusMap = {
    pending:    { label: 'قيد المراجعة',  color: 'amber' },
    processing: { label: 'جارٍ المعالجة', color: 'blue'  },
    resolved:   { label: 'تمت المعالجة',  color: 'green' },
    rejected:   { label: 'مرفوضة',        color: 'red'   },
};

const logout = () => router.post('/logout');
</script>

<template>
    <Head title="الواجهة الرئيسية — نظام إدارة الشكاوى" />

    <div class="app">
        <div class="bg-shape s1"></div>
        <div class="bg-shape s2"></div>
        <div class="bg-grid"></div>

        <!-- ═══ TOPBAR ═══ -->
        <header class="topbar">
            <div class="topbar-brand">
                <div class="brand-seal">
                    <svg width="22" height="22" viewBox="0 0 56 56" fill="none">
                        <circle cx="28" cy="28" r="26" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                        <path d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z" fill="rgba(255,255,255,0.95)"/>
                    </svg>
                </div>
                <span class="brand-name">نظام الشكاوى الحكومية</span>
            </div>

            <div class="topbar-actions">
                <!-- Notifications -->
                <div class="notif-wrapper">
                    <button class="icon-btn" @click="showNotifications = !showNotifications">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span v-if="unreadCount > 0" class="notif-dot">{{ unreadCount }}</span>
                    </button>

                    <Transition name="dropdown">
                        <div v-if="showNotifications" class="notif-dropdown">
                            <div class="dropdown-header">الإشعارات</div>
                            <div v-if="notifications.length === 0" class="dropdown-empty">لا توجد إشعارات</div>
                            <div v-for="n in notifications" :key="n.id" class="notif-item" :class="{ unread: !n.read }">
                                <div class="notif-dot-indicator"></div>
                                <div class="notif-content">
                                    <p class="notif-text">{{ n.message }}</p>
                                    <span class="notif-time">{{ n.time }}</span>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- User menu -->
                <div class="user-chip">
                    <div class="user-avatar">{{ user?.name?.charAt(0) ?? 'م' }}</div>
                    <div class="user-info">
                        <span class="user-name">{{ user?.name ?? 'المواطن' }}</span>
                        <span class="user-role">مواطن</span>
                    </div>
                </div>

                <button class="logout-btn" @click="logout" title="تسجيل الخروج">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- ═══ MAIN ═══ -->
        <main class="main">

            <!-- Hero: Welcome + CTA -->
            <section class="hero">
                <div class="hero-text">
                    <p class="hero-greeting">مرحباً، {{ user?.name?.split(' ')[0] ?? 'عزيزي المواطن' }} 👋</p>
                    <h1 class="hero-title">ما الذي تحتاج مساعدة فيه اليوم؟</h1>
                    <p class="hero-sub">يمكنك تقديم شكوى جديدة أو متابعة شكاواك السابقة من هنا</p>
                </div>
                <Link href="/create-complaint" class="cta-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    تقديم شكوى جديدة
                </Link>
            </section>

            <!-- Stats cards -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon--total">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="stat-value">{{ stats.total }}</span>
                        <span class="stat-label">إجمالي الشكاوى</span>
                    </div>
                    <div class="stat-bar" style="--p: 100%; --c: rgba(255,255,255,0.15)"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--pending">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="stat-value">{{ stats.pending }}</span>
                        <span class="stat-label">قيد المراجعة</span>
                    </div>
                    <div class="stat-bar" :style="`--p: ${(stats.pending/stats.total*100).toFixed(0)}%; --c: rgba(234,179,8,0.35)`"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--resolved">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="stat-value">{{ stats.resolved }}</span>
                        <span class="stat-label">تمت المعالجة</span>
                    </div>
                    <div class="stat-bar" :style="`--p: ${(stats.resolved/stats.total*100).toFixed(0)}%; --c: rgba(34,197,94,0.35)`"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon--rejected">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="stat-value">{{ stats.rejected }}</span>
                        <span class="stat-label">مرفوضة</span>
                    </div>
                    <div class="stat-bar" :style="`--p: ${(stats.rejected/stats.total*100).toFixed(0)}%; --c: rgba(239,68,68,0.35)`"></div>
                </div>
            </section>

            <!-- Bottom grid: Table + Quick actions -->
            <section class="bottom-grid">

                <!-- Recent complaints table -->
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            </svg>
                            آخر الشكاوى
                        </h2>
                        <Link href="/complaints" class="panel-link">عرض الكل</Link>
                    </div>

                    <div v-if="recentComplaints.length === 0" class="empty-state">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                        </svg>
                        <p>لم تقدّم أي شكوى بعد</p>
                        <Link href="/complaints/create" class="empty-cta">قدّم شكوى الآن</Link>
                    </div>

                    <table v-else class="complaints-table">
                        <thead>
                            <tr>
                                <th>رقم التتبع</th>
                                <th>الموضوع</th>
                                <th>الجهة</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in recentComplaints" :key="c.id" class="table-row">
                                <td class="tracking-num">#{{ c.tracking_number }}</td>
                                <td class="complaint-subject">{{ c.subject }}</td>
                                <td class="complaint-dept">{{ c.department }}</td>
                                <td>
                                    <span class="status-badge" :class="`status--${statusMap[c.status]?.color ?? 'amber'}`">
                                        {{ statusMap[c.status]?.label ?? c.status }}
                                    </span>
                                </td>
                                <td class="complaint-date">{{ c.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Quick actions + Track -->
                <div class="sidebar-panels">

                    <!-- Quick actions -->
                    <div class="panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                                إجراءات سريعة
                            </h2>
                        </div>
                        <div class="quick-actions">
                            <Link href="/complaints/create" class="quick-action primary">
                                <div class="qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </div>
                                <span>شكوى جديدة</span>
                            </Link>
                            <Link href="/complaints" class="quick-action">
                                <div class="qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                                </div>
                                <span>شكاواي</span>
                            </Link>
                            <Link href="/profile" class="quick-action">
                                <div class="qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <span>ملفي الشخصي</span>
                            </Link>
                            <Link href="/support" class="quick-action">
                                <div class="qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                </div>
                                <span>الدعم والمساعدة</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Track complaint -->
                    <div class="panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                تتبع شكوى
                            </h2>
                        </div>
                        <div class="track-form">
                            <div class="track-input-wrap">
                                <input type="text" class="track-input" placeholder="أدخل رقم التتبع..." dir="ltr"/>
                            </div>
                            <button class="track-btn">بحث</button>
                        </div>
                        <p class="track-hint">مثال: GC-2024-00123</p>
                    </div>

                </div>
            </section>
        </main>
    </div>
</template>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.app {
    min-height: 100vh;
    background: #05112b;
    direction: rtl;
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    position: relative;
    overflow-x: hidden;
}

/* ── Background ── */
.bg-shape { position: fixed; border-radius: 50%; filter: blur(100px); pointer-events: none; z-index: 0; }
.s1 { width: 600px; height: 600px; background: radial-gradient(circle, #1a4a8a 0%, transparent 70%); top: -200px; right: -150px; opacity: .5; }
.s2 { width: 400px; height: 400px; background: radial-gradient(circle, #c9952a 0%, transparent 70%); bottom: -100px; left: 20%; opacity: .15; }
.bg-grid {
    position: fixed; inset: 0; z-index: 0;
    background-image: linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
    background-size: 48px 48px; pointer-events: none;
}

/* ── Topbar ── */
.topbar {
    position: sticky; top: 0; z-index: 50;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 2rem; height: 60px;
    background: rgba(5,17,43,0.85);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.topbar-brand { display: flex; align-items: center; gap: 10px; }
.brand-seal {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #1a4a8a, #0d2d5e);
    border: 1px solid rgba(255,255,255,.15); border-radius: 9px;
}
.brand-name { font-size: .85rem; font-weight: 600; color: rgba(255,255,255,.85); letter-spacing: .2px; }
.topbar-actions { display: flex; align-items: center; gap: 10px; }

/* Notifications */
.notif-wrapper { position: relative; }
.icon-btn {
    position: relative; width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 9px; color: rgba(255,255,255,.6); cursor: pointer; transition: all .2s;
}
.icon-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
.notif-dot {
    position: absolute; top: -4px; left: -4px;
    width: 16px; height: 16px; background: #d4a843;
    border-radius: 50%; font-size: .6rem; font-weight: 700;
    color: #05112b; display: flex; align-items: center; justify-content: center;
}
.notif-dropdown {
    position: absolute; top: calc(100% + 8px); left: 0;
    width: 280px; background: #0d2050;
    border: 1px solid rgba(255,255,255,.1); border-radius: 12px;
    overflow: hidden; z-index: 100;
    box-shadow: 0 16px 40px rgba(0,0,0,.5);
}
.dropdown-header { padding: 10px 14px; font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.5); border-bottom: 1px solid rgba(255,255,255,.07); }
.dropdown-empty { padding: 16px 14px; font-size: .78rem; color: rgba(255,255,255,.3); text-align: center; }
.notif-item { display: flex; gap: 10px; padding: 10px 14px; border-bottom: 1px solid rgba(255,255,255,.05); transition: background .15s; }
.notif-item:hover { background: rgba(255,255,255,.04); }
.notif-item.unread { background: rgba(212,168,67,.05); }
.notif-dot-indicator { width: 7px; height: 7px; min-width: 7px; background: #d4a843; border-radius: 50%; margin-top: 4px; }
.notif-text { font-size: .77rem; color: rgba(255,255,255,.75); line-height: 1.4; }
.notif-time { font-size: .68rem; color: rgba(255,255,255,.3); margin-top: 2px; display: block; }
.dropdown-enter-active, .dropdown-leave-active { transition: all .2s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-8px); }

/* User chip */
.user-chip { display: flex; align-items: center; gap: 8px; padding: 4px 10px 4px 4px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 30px; }
.user-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #1a4a8a, #d4a843); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; color: #fff; }
.user-info { display: flex; flex-direction: column; }
.user-name { font-size: .76rem; font-weight: 600; color: #fff; line-height: 1.2; }
.user-role { font-size: .62rem; color: #d4a843; }
.logout-btn { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2); border-radius: 9px; color: rgba(239,68,68,.7); cursor: pointer; transition: all .2s; }
.logout-btn:hover { background: rgba(239,68,68,.15); color: #f87171; }

/* ── Main ── */
.main { position: relative; z-index: 1; padding: 2rem; max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }

/* ── Hero ── */
.hero {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    padding: 1.75rem 2rem;
    background: linear-gradient(135deg, rgba(26,74,138,0.4) 0%, rgba(13,45,94,0.3) 100%);
    border: 1px solid rgba(255,255,255,.08); border-radius: 16px;
    backdrop-filter: blur(8px);
}
.hero-greeting { font-size: .85rem; color: #d4a843; font-weight: 500; margin-bottom: 4px; }
.hero-title { font-size: 1.4rem; font-weight: 700; color: #fff; letter-spacing: -.3px; margin-bottom: 5px; }
.hero-sub { font-size: .82rem; color: rgba(255,255,255,.42); }
.cta-btn {
    display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;
    padding: 12px 22px; font-size: .88rem; font-weight: 700; color: #05112b;
    background: linear-gradient(135deg, #d4a843, #f0c862);
    border-radius: 10px; text-decoration: none;
    box-shadow: 0 4px 20px rgba(212,168,67,.35);
    transition: all .2s;
}
.cta-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(212,168,67,.5); }

/* ── Stats ── */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.stat-card {
    position: relative; overflow: hidden;
    padding: 1.25rem 1.25rem 1rem;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08); border-radius: 14px;
    backdrop-filter: blur(8px); transition: border-color .2s;
}
.stat-card:hover { border-color: rgba(255,255,255,.15); }
.stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: .85rem; }
.stat-icon--total   { background: rgba(255,255,255,.08);  color: rgba(255,255,255,.7); }
.stat-icon--pending { background: rgba(234,179,8,.15);    color: #facc15; }
.stat-icon--resolved{ background: rgba(34,197,94,.15);    color: #4ade80; }
.stat-icon--rejected{ background: rgba(239,68,68,.15);    color: #f87171; }
.stat-value { display: block; font-size: 1.9rem; font-weight: 700; color: #fff; line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: .75rem; color: rgba(255,255,255,.4); }
.stat-bar {
    position: absolute; bottom: 0; right: 0;
    height: 3px; width: var(--p);
    background: var(--c); border-radius: 2px 0 0 0;
    transition: width .6s ease;
}

/* ── Bottom grid ── */
.bottom-grid { display: grid; grid-template-columns: 1fr 320px; gap: 1rem; align-items: start; }

/* ── Panel ── */
.panel {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08); border-radius: 14px;
    overflow: hidden; backdrop-filter: blur(8px);
}
.panel-header { display: flex; align-items: center; justify-content: space-between; padding: .9rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.07); }
.panel-title { display: flex; align-items: center; gap: 7px; font-size: .85rem; font-weight: 600; color: rgba(255,255,255,.8); }
.panel-link { font-size: .75rem; color: #d4a843; text-decoration: none; transition: opacity .2s; }
.panel-link:hover { opacity: .75; }

/* ── Empty state ── */
.empty-state { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 3rem 2rem; color: rgba(255,255,255,.3); font-size: .82rem; }
.empty-cta {
    margin-top: 4px; padding: 7px 18px;
    font-size: .78rem; font-weight: 600; color: #d4a843;
    background: rgba(212,168,67,.1); border: 1px solid rgba(212,168,67,.25);
    border-radius: 20px; text-decoration: none; transition: all .2s;
}
.empty-cta:hover { background: rgba(212,168,67,.18); }

/* ── Table ── */
.complaints-table { width: 100%; border-collapse: collapse; }
.complaints-table th { padding: .65rem 1.25rem; font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.35); text-align: right; white-space: nowrap; border-bottom: 1px solid rgba(255,255,255,.06); }
.table-row td { padding: .85rem 1.25rem; font-size: .8rem; color: rgba(255,255,255,.7); border-bottom: 1px solid rgba(255,255,255,.04); white-space: nowrap; transition: background .15s; }
.table-row:hover td { background: rgba(255,255,255,.03); }
.table-row:last-child td { border-bottom: none; }
.tracking-num { font-family: monospace; color: #d4a843 !important; font-weight: 600; font-size: .78rem !important; }
.complaint-subject { max-width: 180px; overflow: hidden; text-overflow: ellipsis; }
.complaint-dept { color: rgba(255,255,255,.45) !important; }
.complaint-date { color: rgba(255,255,255,.35) !important; font-size: .73rem !important; }

/* Status badges */
.status-badge { display: inline-flex; padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600; white-space: nowrap; }
.status--amber { background: rgba(234,179,8,.15);  color: #facc15; }
.status--blue  { background: rgba(59,130,246,.15); color: #93c5fd; }
.status--green { background: rgba(34,197,94,.15);  color: #4ade80; }
.status--red   { background: rgba(239,68,68,.15);  color: #f87171; }

/* ── Sidebar panels ── */
.sidebar-panels { display: flex; flex-direction: column; gap: 1rem; }

/* ── Quick actions ── */
.quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 1rem; }
.quick-action {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 12px; border-radius: 10px;
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
    color: rgba(255,255,255,.7); text-decoration: none; font-size: .78rem; font-weight: 500;
    transition: all .2s; cursor: pointer;
}
.quick-action:hover { background: rgba(255,255,255,.09); border-color: rgba(255,255,255,.15); color: #fff; }
.quick-action.primary { background: rgba(212,168,67,.1); border-color: rgba(212,168,67,.25); color: #d4a843; grid-column: 1 / -1; justify-content: center; font-weight: 700; }
.quick-action.primary:hover { background: rgba(212,168,67,.18); }
.qa-icon { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.07); border-radius: 7px; }
.quick-action.primary .qa-icon { background: rgba(212,168,67,.15); }

/* ── Track form ── */
.track-form { display: flex; gap: 8px; padding: 1rem 1rem .5rem; }
.track-input-wrap { flex: 1; }
.track-input {
    width: 100%; padding: 9px 12px; font-size: .8rem;
    color: #fff; background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1); border-radius: 9px; outline: none;
    font-family: inherit; transition: all .2s;
}
.track-input:focus { border-color: rgba(59,130,246,.5); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
.track-input::placeholder { color: rgba(255,255,255,.2); }
.track-btn {
    padding: 9px 14px; font-size: .78rem; font-weight: 600;
    color: #05112b; background: linear-gradient(135deg, #d4a843, #f0c862);
    border: none; border-radius: 9px; cursor: pointer; white-space: nowrap;
    font-family: inherit; transition: opacity .2s;
}
.track-btn:hover { opacity: .88; }
.track-hint { padding: 0 1rem .85rem; font-size: .67rem; color: rgba(255,255,255,.25); }

/* ── Responsive ── */
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .bottom-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .main { padding: 1rem; }
    .hero { flex-direction: column; align-items: flex-start; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .topbar { padding: 0 1rem; }
    .brand-name { display: none; }
}
</style>
