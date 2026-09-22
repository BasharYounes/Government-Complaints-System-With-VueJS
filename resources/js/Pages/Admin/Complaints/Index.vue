<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import AdminLayout from '../AdminLayout.vue';

const complaints = ref([]), loading = ref(true), error = ref(''), query = ref(''), status = ref('all');
const statusLabels = { new: 'جديدة', in_progress: 'قيد المعالجة', completed: 'مكتملة', rejected: 'مرفوضة' };
const visibleComplaints = computed(() => complaints.value.filter(item => {
    const matchesStatus = status.value === 'all' || item.status === status.value;
    const text = `${item.reference_number ?? ''} ${item.description ?? ''} ${item.user?.name ?? ''}`.toLowerCase();
    return matchesStatus && text.includes(query.value.toLowerCase());
}));
const formatDate = value => value ? new Date(value).toLocaleDateString('ar', { day:'numeric', month:'short', year:'numeric' }) : '—';
async function loadComplaints() { loading.value = true; error.value = ''; try { const response = await axios.get('/admin/complaints'); complaints.value = response.data?.data ?? []; } catch (e) { error.value = 'تعذر تحميل قائمة الشكاوى.'; } finally { loading.value = false; } }
onMounted(loadComplaints);
</script>
<template>
<AdminLayout page-title="الشكاوى">
    <section class="page-head"><div><p class="eyebrow">مركز المتابعة</p><h2>إدارة الشكاوى</h2><p class="muted">راجع الطلبات وتابع توزيعها على الجهات الحكومية.</p></div><span class="total-badge">{{ visibleComplaints.length }} شكوى</span></section>
    <div v-if="error" class="alert">{{ error }} <button @click="loadComplaints">إعادة المحاولة</button></div>
    <section class="panel filters"><label class="search-box">⌕<input v-model="query" placeholder="ابحث بالرقم أو اسم المواطن..." /></label><select v-model="status"><option value="all">كل الحالات</option><option v-for="(label,key) in statusLabels" :key="key" :value="key">{{ label }}</option></select></section>
    <section class="panel table-panel"><div v-if="loading" class="empty">جاري تحميل الشكاوى...</div><div v-else-if="!visibleComplaints.length" class="empty">لا توجد شكاوى مطابقة للبحث الحالي.</div><div v-else class="table-wrap"><table><thead><tr><th>المرجع</th><th>المواطن</th><th>الجهة الحكومية</th><th>الحالة</th><th>تاريخ الإنشاء</th><th></th></tr></thead><tbody><tr v-for="item in visibleComplaints" :key="item.id"><td><strong class="reference">{{ item.reference_number || `#${item.id}` }}</strong></td><td>{{ item.user?.name || 'غير متوفر' }}</td><td>{{ item.government_entity?.name || 'غير محددة' }}</td><td><span class="status" :class="`status-${item.status}`">{{ statusLabels[item.status] || item.status }}</span></td><td class="date">{{ formatDate(item.created_at) }}</td><td><button class="details" title="عرض التفاصيل">عرض</button></td></tr></tbody></table></div></section>
</AdminLayout>
</template>
<style scoped>
.page-head,.filters,.search-box{display:flex;align-items:center}.page-head{justify-content:space-between;margin-bottom:1.4rem}.eyebrow{margin:0 0 .3rem;color:#d6aa50;font-size:.72rem;font-weight:700}.page-head h2{margin:0;color:#fff;font-size:1.45rem}.muted{margin:.45rem 0 0;color:rgba(255,255,255,.45);font-size:.8rem}.total-badge{background:rgba(214,170,80,.12);color:#e3bd68;padding:.55rem .8rem;border-radius:8px;font-size:.75rem}.panel{background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.08);border-radius:12px}.filters{padding:.75rem;gap:.7rem;margin-bottom:1rem}.search-box{gap:.5rem;flex:1;color:rgba(255,255,255,.4);background:rgba(0,0,0,.13);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:0 .75rem}.search-box input,select{width:100%;background:transparent;border:0;outline:0;color:#fff;padding:.65rem;font:inherit;font-size:.78rem}.filters select{width:170px;background:#122952;border-radius:8px;border:1px solid rgba(255,255,255,.08)}select option{background:#122952}.table-panel{overflow:hidden}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;text-align:right;min-width:720px}th{color:rgba(255,255,255,.36);font-size:.68rem;font-weight:600;background:rgba(0,0,0,.12)}th,td{padding:1rem;border-bottom:1px solid rgba(255,255,255,.06)}td{color:rgba(255,255,255,.7);font-size:.76rem}.reference{color:#e3bd68}.date{color:rgba(255,255,255,.43)}.status{padding:.35rem .55rem;border-radius:6px;font-size:.66rem}.status-new{background:rgba(96,165,250,.14);color:#93c5fd}.status-in_progress{background:rgba(251,191,36,.14);color:#fcd34d}.status-completed{background:rgba(52,211,153,.14);color:#6ee7b7}.status-rejected{background:rgba(248,113,113,.14);color:#fca5a5}.details{background:transparent;color:#d6aa50;border:0;cursor:pointer;font-size:.7rem}.empty{text-align:center;padding:3.5rem;color:rgba(255,255,255,.4);font-size:.8rem}.alert{padding:.8rem 1rem;margin-bottom:1rem;border-radius:8px;background:rgba(248,113,113,.1);color:#fca5a5;font-size:.78rem}.alert button{margin-right:.5rem;background:none;border:0;color:#fff;text-decoration:underline;cursor:pointer}@media(max-width:600px){.filters{align-items:stretch;flex-direction:column}.filters select{width:100%}.page-head{align-items:flex-start;gap:1rem;flex-direction:column}}
</style>
