<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const form = ref({ email: '', password: '' });
const loading = ref(false);
const error = ref('');
const fieldErrors = ref({});

async function login() {
    loading.value = true;
    error.value = '';
    fieldErrors.value = {};
    try {
        await axios.post('/loginAdmin', form.value);
        router.visit('/admin/dashboard');
    } catch (exception) {
        const response = exception.response;
        if (response?.status === 422) {
            fieldErrors.value = response.data.errors || {};
            error.value = 'يرجى التأكد من صحة البيانات المدخلة.';
        } else if (response?.status === 401) {
            error.value = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
        } else {
            error.value = 'حدث خطأ أثناء تسجيل الدخول. حاول مرة أخرى.';
        }
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <main class="auth-shell" dir="rtl">
        <section class="brand-side">
            <div class="seal">★</div>
            <p class="brand-kicker">نظام الخدمات الحكومية</p>
            <h1>مركز إدارة<br><span>الشكاوى الحكومية</span></h1>
            <p class="brand-copy">منصة موحدة لمتابعة الشكاوى، تحسين الخدمات، وتعزيز الثقة بين المواطن والجهات الحكومية.</p>
            <div class="brand-line"></div>
        </section>
        <section class="form-side">
            <div class="form-card">
                <div class="mobile-logo"><div class="seal small">★</div><span>الشكاوى الحكومية</span></div>
                <p class="eyebrow">بوابة الإدارة</p>
                <h2>تسجيل دخول المسؤول</h2>
                <p class="subtitle">أدخل بيانات حسابك للوصول إلى لوحة التحكم.</p>
                <div v-if="error" class="error-box">{{ error }}</div>
                <form @submit.prevent="login" novalidate>
                    <label>البريد الإلكتروني<input v-model="form.email" type="email" autocomplete="username" placeholder="admin@example.com" /><small v-if="fieldErrors.email">{{ fieldErrors.email[0] }}</small></label>
                    <label>كلمة المرور<input v-model="form.password" type="password" autocomplete="current-password" placeholder="••••••••" /><small v-if="fieldErrors.password">{{ fieldErrors.password[0] }}</small></label>
                    <button :disabled="loading" type="submit">{{ loading ? 'جاري تسجيل الدخول...' : 'دخول إلى لوحة التحكم' }} <span v-if="!loading">←</span></button>
                </form>
                <p class="security-note">هذه الصفحة مخصصة للمسؤولين والموظفين المخولين فقط.</p>
            </div>
        </section>
    </main>
</template>

<style scoped>
*{box-sizing:border-box}.auth-shell{min-height:100vh;display:grid;grid-template-columns:1fr 1fr;background:#f6f8fc;color:#10244b;font-family:'Segoe UI',Tahoma,system-ui,sans-serif}.brand-side{position:relative;display:flex;flex-direction:column;justify-content:center;padding:5rem clamp(2rem,8vw,8rem);overflow:hidden;background:linear-gradient(145deg,#061738,#0d2c61 70%,#16457e);color:#fff}.brand-side:after{content:'';position:absolute;width:450px;height:450px;border:1px solid rgba(255,255,255,.08);border-radius:50%;left:-180px;bottom:-190px;box-shadow:0 0 0 35px rgba(255,255,255,.025),0 0 0 70px rgba(255,255,255,.02)}.seal{display:grid;place-items:center;width:52px;height:52px;margin-bottom:1.5rem;border:1px solid rgba(255,255,255,.35);border-radius:15px;background:rgba(255,255,255,.1);color:#e1b958;font-size:1.4rem}.brand-kicker,.eyebrow{color:#dbb55b;font-size:.72rem;font-weight:700;letter-spacing:.08em}.brand-kicker{margin:0 0 1rem}.brand-side h1{margin:0;font-size:clamp(2rem,4vw,3.5rem);line-height:1.2;letter-spacing:-.04em}.brand-side h1 span{color:#e1b958}.brand-copy{max-width:430px;margin:1.5rem 0 0;color:rgba(255,255,255,.58);font-size:.9rem;line-height:1.9}.brand-line{width:70px;height:3px;margin-top:2rem;background:#e1b958;border-radius:4px}.form-side{display:grid;place-items:center;padding:2rem}.form-card{width:min(100%,420px)}.mobile-logo{display:none}.eyebrow{margin:0 0 .45rem}.form-card h2{margin:0;font-size:1.7rem;color:#10244b}.subtitle{margin:.55rem 0 2rem;color:#71809a;font-size:.82rem}.error-box{padding:.75rem .9rem;margin-bottom:1rem;border:1px solid #fecaca;border-radius:8px;background:#fff1f2;color:#be123c;font-size:.78rem}form{display:grid;gap:1.15rem}label{display:grid;gap:.45rem;color:#34496f;font-size:.76rem;font-weight:600}input{width:100%;padding:.82rem 1rem;border:1px solid #dbe3ef;border-radius:8px;outline:0;background:#fff;color:#10244b;font:inherit;font-size:.82rem;transition:border-color .2s,box-shadow .2s}input:focus{border-color:#1b5eaa;box-shadow:0 0 0 3px rgba(27,94,170,.1)}small{color:#be123c;font-weight:400}button{padding:.85rem 1rem;border:0;border-radius:8px;background:#d6aa50;color:#11244a;cursor:pointer;font:inherit;font-size:.82rem;font-weight:700;transition:transform .2s,opacity .2s}button:hover:not(:disabled){transform:translateY(-1px)}button:disabled{opacity:.55;cursor:not-allowed}.security-note{margin-top:2rem;color:#9aa6b8;text-align:center;font-size:.68rem}.small{width:32px;height:32px;margin:0;border-radius:9px;font-size:.8rem}@media(max-width:700px){.auth-shell{display:block}.brand-side{display:none}.form-side{min-height:100vh;padding:1.5rem}.mobile-logo{display:flex;align-items:center;gap:.6rem;margin-bottom:3rem;color:#10244b;font-weight:700;font-size:.85rem}}
</style>
