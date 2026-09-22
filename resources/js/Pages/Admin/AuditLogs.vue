<script setup>
import AdminLayout from './AdminLayout.vue';
</script>
<template><AdminLayout page-title="سجل التدقيق"><section class="head"><p class="eyebrow">الشفافية والمساءلة</p><h2>سجل التدقيق</h2><p class="muted">سيظهر هنا التسلسل الزمني لتغييرات الشكاوى وإجراءات الموظفين.</p></section><section class="empty panel">واجهة سجل التدقيق جاهزة للربط مع `/admin/complaints/{id}/audit-logs` في المرحلة التالية.</section></AdminLayout></template>
<style scoped>.head{margin-bottom:1.4rem}.eyebrow{margin:0 0 .3rem;color:#d6aa50;font-size:.72rem;font-weight:700}.head h2{margin:0;color:#fff;font-size:1.45rem}.muted{margin:.45rem 0 0;color:rgba(255,255,255,.45);font-size:.8rem}.panel{padding:3rem;text-align:center;background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.08);border-radius:12px;color:rgba(255,255,255,.45);font-size:.8rem}</style>
