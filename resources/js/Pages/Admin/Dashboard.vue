<!-- <script setup>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';
import AdminLayout from './AdminLayout.vue';

const loading = ref(true);
const error = ref('');
const stats = ref({ overall: {}, by_government_entity: [] });

const overall = computed(() => stats.value.overall || {});
const cards = computed(() => [
    { label: 'إجمالي الشكاوى', value: overall.value.total_complaints ?? 0, tone: 'blue', icon: '▦' },
    { label: 'شكاوى جديدة', value: `${overall.value.new_percentage ?? 0}%`, tone: 'amber', icon: '◷' },
    { label: 'قيد المعالجة', value: `${overall.value.in_progress_percentage ?? 0}%`, tone: 'violet', icon: '↻' },
    { label: 'مكتملة', value: `${overall.value.completed_percentage ?? 0}%`, tone: 'green', icon: '✓' },
]);

async function loadStats() {
    loading.value = true;
    error.value = '';
    try {
        const response = await axios.get('/admin/statistics');
        stats.value = response.data?.data ?? { overall: {}, by_government_entity: [] };
    } catch (exception) {
        error.value = 'تعذر تحميل الإحصائيات. تأكد من صلاحيات الدخول واتصال الخادم.';
    } finally {
        loading.value = false;
    }
}

onMounted(loadStats);
</script>

<template>
    <AdminLayout page-title="لوحة التحكم">
        <section class="dashboard-head">
            <div>
                <p class="eyebrow">نظرة عامة على المنظومة</p>
                <h2>أهلاً بك في مركز إدارة الشكاوى</h2>
                <p class="muted">تابع أداء الجهات الحكومية وحالة الشكاوى من مكان واحد.</p>
            </div>
            <a class="primary-button" href="/admin/complaints">عرض كل الشكاوى <span>←</span></a>
        </section>

        <div v-if="error" class="alert error-alert">{{ error }} <button @click="loadStats">إعادة المحاولة</button></div>
        <div v-if="loading" class="stats-grid loading-grid"><div v-for="i in 4" :key="i" class="skeleton card-skeleton"></div></div>
        <div v-else class="stats-grid">
            <article v-for="card in cards" :key="card.label" class="stat-card">
                <div class="stat-icon" :class="`tone-${card.tone}`">{{ card.icon }}</div>
                <div><p>{{ card.label }}</p><strong>{{ card.value }}</strong></div>
                <span class="stat-caption">مقارنة بالحالة الحالية</span>
            </article>
        </div>

        <section class="panel entity-panel">
            <div class="panel-heading"><div><p class="eyebrow">توزيع الأداء</p><h3>الشكاوى حسب الجهة الحكومية</h3></div><span class="panel-count">{{ stats.by_government_entity?.length ?? 0 }} جهات</span></div>
            <div v-if="!stats.by_government_entity?.length && !loading" class="empty-state">لا توجد بيانات كافية لعرض التوزيع بعد.</div>
            <div v-else class="entity-list">
                <div v-for="entity in stats.by_government_entity" :key="entity.government_entity" class="entity-row">
                    <div class="entity-name"><span class="entity-dot"></span><strong>{{ entity.government_entity }}</strong><small>{{ entity.total_complaints }} شكوى</small></div>
                    <div class="progress-wrap"><div class="progress"><span :style="{ width: `${entity.completed_percentage}%` }"></span></div><b>{{ entity.completed_percentage }}%</b></div>
                </div>
            </div>
        </section>
    </AdminLayout>
</template>

<style scoped>
.dashboard-head,.panel-heading,.entity-row,.entity-name,.progress-wrap{display:flex;align-items:center}.dashboard-head{justify-content:space-between;gap:1rem;margin-bottom:1.5rem}.eyebrow{margin:0 0 .35rem;color:#d6aa50;font-size:.72rem;font-weight:700;letter-spacing:.04em}.dashboard-head h2{margin:0;color:#fff;font-size:1.5rem}.muted{color:rgba(255,255,255,.48);font-size:.82rem;margin:.55rem 0 0}.primary-button{background:#d6aa50;color:#0a1c42;padding:.7rem 1rem;border-radius:9px;text-decoration:none;font-size:.8rem;font-weight:700}.primary-button span{margin-right:.45rem}.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.2rem}.stat-card,.panel{border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.045);border-radius:13px}.stat-card{position:relative;display:flex;align-items:center;gap:.8rem;padding:1rem}.stat-icon{width:39px;height:39px;display:grid;place-items:center;border-radius:10px;font-size:1.15rem;font-weight:700}.tone-blue{background:#173665;color:#8dbaff}.tone-amber{background:#4b3a1b;color:#f0c969}.tone-violet{background:#322650;color:#c2a9ff}.tone-green{background:#164536;color:#6ee7b7}.stat-card p{margin:0;color:rgba(255,255,255,.52);font-size:.72rem}.stat-card strong{display:block;color:#fff;font-size:1.35rem;margin-top:.2rem}.stat-caption{position:absolute;bottom:.8rem;left:1rem;color:rgba(255,255,255,.25);font-size:.62rem}.panel{padding:1.25rem}.panel-heading{justify-content:space-between;margin-bottom:1.2rem}.panel-heading h3{margin:0;color:#fff;font-size:1rem}.panel-count{font-size:.7rem;color:#d6aa50;background:rgba(214,170,80,.1);padding:.35rem .6rem;border-radius:6px}.entity-list{display:grid;gap:.8rem}.entity-row{justify-content:space-between;gap:1rem;padding:.8rem 0;border-bottom:1px solid rgba(255,255,255,.06)}.entity-row:last-child{border-bottom:0}.entity-name{gap:.55rem}.entity-name strong{font-size:.82rem;color:rgba(255,255,255,.82)}.entity-name small{color:rgba(255,255,255,.35);font-size:.68rem}.entity-dot{width:8px;height:8px;border-radius:50%;background:#d6aa50}.progress-wrap{gap:.7rem;width:42%}.progress{height:7px;flex:1;background:rgba(255,255,255,.09);border-radius:9px;overflow:hidden}.progress span{display:block;height:100%;background:linear-gradient(90deg,#d6aa50,#f3d68d);border-radius:9px}.progress-wrap b{width:38px;color:#d6aa50;font-size:.75rem}.empty-state{padding:2rem;text-align:center;color:rgba(255,255,255,.4);font-size:.8rem}.alert{padding:.8rem 1rem;border-radius:9px;font-size:.78rem;margin-bottom:1rem}.error-alert{background:rgba(248,113,113,.1);color:#fca5a5;border:1px solid rgba(248,113,113,.2)}.error-alert button{margin-right:.7rem;background:transparent;border:0;color:#fff;text-decoration:underline;cursor:pointer}.skeleton{background:linear-gradient(90deg,rgba(255,255,255,.04),rgba(255,255,255,.1),rgba(255,255,255,.04));background-size:200% 100%;animation:shine 1.3s infinite}.card-skeleton{height:106px;border-radius:13px}@keyframes shine{to{background-position:-200% 0}}@media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.dashboard-head{align-items:flex-start;flex-direction:column}.stats-grid{grid-template-columns:1fr}.progress-wrap{width:45%}.entity-name{flex-wrap:wrap}}
</style> -->


<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from './AdminLayout.vue';

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const loading = ref(true);
const error = ref('');

const overall = ref({
    total_complaints: 0,
    new_percentage: 0,
    in_progress_percentage: 0,
    completed_percentage: 0,
    rejected_percentage: 0,
});

const byEntity = ref([]);

/*
|--------------------------------------------------------------------------
| Fetch statistics
|--------------------------------------------------------------------------
*/

const loadStatistics = async () => {
    loading.value = true;
    error.value = '';

    try {
        const response = await fetch('/admin/statistics', {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        const payload = await response.json();

        if (!response.ok) {
            error.value = payload?.message || 'تعذر تحميل الإحصائيات.';
            return;
        }

        overall.value = payload?.data?.overall ?? overall.value;
        byEntity.value = payload?.data?.by_government_entity ?? [];
    } catch (e) {
        error.value = 'تعذر الاتصال بالخادم.';
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadStatistics();
});

/*
|--------------------------------------------------------------------------
| KPI cards
|--------------------------------------------------------------------------
*/

const kpis = computed(() => [
    {
        key: 'new',
        label: 'شكاوى جديدة',
        value: overall.value.new_percentage,
        color: 'amber',
    },
    {
        key: 'progress',
        label: 'قيد المعالجة',
        value: overall.value.in_progress_percentage,
        color: 'blue',
    },
    {
        key: 'completed',
        label: 'مكتملة',
        value: overall.value.completed_percentage,
        color: 'green',
    },
    {
        key: 'rejected',
        label: 'مرفوضة',
        value: overall.value.rejected_percentage,
        color: 'red',
    },
]);
</script>

<template>
    <Head title="لوحة التحكم — إدارة الشكاوى" />

    <AdminLayout page-title="لوحة التحكم">

        <!-- Error -->
        <div v-if="error" class="alert">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ error }}
        </div>

        <!-- ═══ Overview ═══ -->
        <section class="kpi-row">

            <div class="kpi-card kpi-card--total">
                <div class="kpi-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="kpi-body">
                    <span class="kpi-value">
                        {{ loading ? '—' : overall.total_complaints }}
                    </span>
                    <span class="kpi-label">إجمالي الشكاوى في النظام</span>
                </div>
            </div>

            <div
                v-for="kpi in kpis"
                :key="kpi.key"
                class="kpi-card"
            >
                <div class="kpi-body">
                    <span class="kpi-value" :class="`text--${kpi.color}`">
                        {{ loading ? '—' : `${kpi.value}%` }}
                    </span>
                    <span class="kpi-label">{{ kpi.label }}</span>
                </div>
                <div class="kpi-bar-track">
                    <div
                        class="kpi-bar-fill"
                        :class="`fill--${kpi.color}`"
                        :style="`width: ${loading ? 0 : kpi.value}%`"
                    ></div>
                </div>
            </div>

        </section>

        <!-- ═══ Breakdown by government entity ═══ -->
        <section class="panel">

            <div class="panel-header">
                <h2 class="panel-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                    الشكاوى حسب الجهة الحكومية
                </h2>
            </div>

            <div v-if="loading" class="empty-state">جارٍ التحميل...</div>

            <div v-else-if="byEntity.length === 0" class="empty-state">
                لا توجد بيانات لعرضها
            </div>

            <div v-else class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>الجهة الحكومية</th>
                            <th>الإجمالي</th>
                            <th>جديدة</th>
                            <th>قيد المعالجة</th>
                            <th>مكتملة</th>
                            <th>مرفوضة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in byEntity" :key="row.government_entity">
                            <td class="entity-name">{{ row.government_entity }}</td>
                            <td class="entity-total">{{ row.total_complaints }}</td>
                            <td>
                                <span class="status-badge status--amber">
                                    {{ row.new }} · {{ row.new_percentage }}%
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status--blue">
                                    {{ row.in_progress }} · {{ row.in_progress_percentage }}%
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status--green">
                                    {{ row.completed }} · {{ row.completed_percentage }}%
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status--red">
                                    {{ row.rejected }} · {{ row.rejected_percentage }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

    </AdminLayout>
</template>

<style scoped>
* { box-sizing: border-box; }

/* ═════════════ Alert ═════════════ */

.alert {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 1rem;
    padding: 10px 14px;
    color: #f87171;
    background: rgba(239, 68, 68, .08);
    border: 1px solid rgba(239, 68, 68, .18);
    border-radius: 10px;
    font-size: .8rem;
}

/* ═════════════ KPI row ═════════════ */

.kpi-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: .85rem;
    margin-bottom: 1.4rem;
}

.kpi-card {
    padding: 1rem 1.1rem;
    background: rgba(255, 255, 255, .035);
    border: 1px solid rgba(255, 255, 255, .07);
    border-radius: 12px;
}

.kpi-card--total {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(212, 168, 67, .06);
    border-color: rgba(212, 168, 67, .18);
}

.kpi-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d4a843;
    background: rgba(212, 168, 67, .12);
    border-radius: 9px;
}

.kpi-body { display: flex; flex-direction: column; gap: 3px; }

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.kpi-label {
    font-size: .68rem;
    color: rgba(255, 255, 255, .4);
}

.kpi-bar-track {
    margin-top: 10px;
    height: 4px;
    background: rgba(255, 255, 255, .06);
    border-radius: 3px;
    overflow: hidden;
}

.kpi-bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width .5s ease;
}

.text--amber, .fill--amber { color: #facc15; background: #facc15; }
.text--blue,  .fill--blue  { color: #93c5fd; background: #93c5fd; }
.text--green, .fill--green { color: #4ade80; background: #4ade80; }
.text--red,   .fill--red   { color: #f87171; background: #f87171; }

/* ═════════════ Panel ═════════════ */

.panel {
    background: rgba(255, 255, 255, .035);
    border: 1px solid rgba(255, 255, 255, .07);
    border-radius: 13px;
    overflow: hidden;
}

.panel-header {
    padding: .9rem 1.2rem;
    border-bottom: 1px solid rgba(255, 255, 255, .06);
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .82);
}

.empty-state {
    padding: 2.5rem;
    text-align: center;
    color: rgba(255, 255, 255, .3);
    font-size: .8rem;
}

/* ═════════════ Table ═════════════ */

.table-wrap { overflow-x: auto; }

.data-table { width: 100%; border-collapse: collapse; min-width: 640px; }

.data-table th {
    padding: .7rem 1.2rem;
    font-size: .7rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .35);
    text-align: right;
    white-space: nowrap;
    border-bottom: 1px solid rgba(255, 255, 255, .06);
}

.data-table td {
    padding: .75rem 1.2rem;
    font-size: .78rem;
    color: rgba(255, 255, 255, .75);
    border-bottom: 1px solid rgba(255, 255, 255, .04);
    white-space: nowrap;
}

.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: rgba(255, 255, 255, .02); }

.entity-name { font-weight: 500; color: #fff; }
.entity-total { font-family: monospace; color: #d4a843; }

.status-badge {
    display: inline-flex;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
}

.status--amber { background: rgba(234, 179, 8, .15);  color: #facc15; }
.status--blue  { background: rgba(59, 130, 246, .15); color: #93c5fd; }
.status--green { background: rgba(34, 197, 94, .15);  color: #4ade80; }
.status--red   { background: rgba(239, 68, 68, .15);  color: #f87171; }

/* ═════════════ Responsive ═════════════ */

@media (max-width: 1200px) {
    .kpi-row { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 700px) {
    .kpi-row { grid-template-columns: repeat(2, 1fr); }
}
</style>
