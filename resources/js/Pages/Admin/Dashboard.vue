<script setup>
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
</style>
