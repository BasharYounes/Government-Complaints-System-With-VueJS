<script setup>
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const props = defineProps({
    email: { type: String, default: null },
    flash: { type: Object, default: () => ({}) },
    errors: { type: Object, default: () => ({}) }
});
const flash = ref(page.props.flash?.error || page.props.flash?.success);
const flashType = ref(page.props.flash?.error ? 'error' : 'success');

const form = useForm({
  name: '',
  mobile: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const Submit = () => {
  form.post('/register', {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => console.log('Registration successful'),
        onError: (errors) => {
      console.log('Registration failed', errors);
    },
  });
};

watch(() => page.props.flash, (newFlash) => {
    if (newFlash?.success || newFlash?.error) {
        flash.value = newFlash.success || newFlash.error;
        flashType.value = newFlash.success ? 'success' : 'error';
        setTimeout(() => flash.value = null, 5000);
    }
}, { deep: true });

// onMounted(() => {
//     if (props.email) form.email = props.email;
// });
</script>

<template>
    <div>
        <Head title="إنشاء حساب — نظام إدارة الشكاوى الحكومية" />

        <!-- Toast -->
        <Transition name="toast-slide">
            <div v-if="flash" class="toast" :class="flashType === 'error' ? 'toast--error' : 'toast--success'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <!-- Success Icon -->
                    <template v-if="flashType === 'success'">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </template>

                    <!-- Error Icon -->
                    <template v-else>
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </template>
                </svg>
                {{ flash }}
            </div>
        </Transition>

        <div class="page-wrapper">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
            <div class="bg-grid"></div>

            <!-- Left panel -->
            <div class="left-panel">
                <div class="left-content">
                    <div class="gov-seal">
                        <svg width="44" height="44" viewBox="0 0 56 56" fill="none">
                            <circle cx="28" cy="28" r="26" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                            <circle cx="28" cy="28" r="20" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                            <path d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z" fill="rgba(255,255,255,0.9)"/>
                        </svg>
                    </div>
                    <h2 class="gov-title">انضم إلى المنصة<br/>الحكومية الموحّدة</h2>
                    <p class="gov-desc">سجّل حسابك للوصول إلى خدمات تقديم<br/>الشكاوى ومتابعتها بشكل رسمي وآمن</p>

                    <div class="steps">
                        <div class="step">
                            <div class="step-num">١</div>
                            <div class="step-text">
                                <span class="step-title">أنشئ حسابك</span>
                                <span class="step-desc">أدخل بياناتك الشخصية</span>
                            </div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-num">٢</div>
                            <div class="step-text">
                                <span class="step-title">تحقّق من بريدك</span>
                                <span class="step-desc">سيُرسَل رمز التفعيل</span>
                            </div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step">
                            <div class="step-num">٣</div>
                            <div class="step-text">
                                <span class="step-title">ابدأ الاستخدام</span>
                                <span class="step-desc">قدّم شكواك بكل سهولة</span>
                            </div>
                        </div>
                    </div>

                    <a href="user-log-in" class="login-link">
                        لديك حساب بالفعل؟
                        <span>تسجيل الدخول</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right panel -->
            <div class="right-panel">
                <div class="register-card">
                    <div class="card-header">
                        <div class="badge-official">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            تسجيل رسمي آمن
                        </div>
                        <h1 class="card-title">إنشاء حساب جديد</h1>
                        <p class="card-subtitle">أكمل البيانات أدناه للتسجيل في المنصة</p>
                    </div>

                    <form @submit.prevent="Submit" novalidate>
                        <!-- Row: Name + Mobile -->
                        <div class="fields-row">
                            <div class="field" :class="{ 'field--error': form.errors.name }">
                                <label for="name" class="label">الاسم الكامل</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="name" v-model="form.name" class="input" placeholder="محمد أحمد" autocomplete="name"/>
                                </div>
                                <span v-if="form.errors.name" class="error-msg">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ form.errors.name }}
                                </span>
                            </div>

                            <div class="field" :class="{ 'field--error': form.errors.mobile }">
                                <label for="mobile" class="label">رقم الجوال</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
                                    </svg>
                                    <input type="text" id="mobile" v-model="form.mobile" class="input" placeholder="05xxxxxxxx" dir="ltr" autocomplete="tel"/>
                                </div>
                                <span v-if="form.errors.mobile" class="error-msg">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ form.errors.mobile }}
                                </span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="field" :class="{ 'field--error': form.errors.email }">
                            <label for="email" class="label">البريد الإلكتروني</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input type="email" id="email" v-model="form.email" class="input" placeholder="example@gov.sa" autocomplete="email" dir="ltr"/>
                            </div>
                            <span v-if="form.errors.email" class="error-msg">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ form.errors.email }}
                            </span>
                        </div>

                        <!-- Row: Password + Confirm -->
                        <div class="fields-row">
                            <div class="field" :class="{ 'field--error': form.errors.password }">
                                <label for="password" class="label">كلمة المرور</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="password" id="password" v-model="form.password" class="input" placeholder="••••••••" autocomplete="new-password" dir="ltr"/>
                                </div>
                                <span v-if="form.errors.password" class="error-msg">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ form.errors.password }}
                                </span>
                            </div>

                            <div class="field" :class="{ 'field--error': form.errors.password_confirmation }">
                                <label for="password_confirmation" class="label">تأكيد المرور</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                    <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="input" placeholder="••••••••" autocomplete="new-password" dir="ltr"/>
                                </div>
                                <span v-if="form.errors.password_confirmation" class="error-msg">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ form.errors.password_confirmation }}
                                </span>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-submit" :disabled="form.processing">
                            <span v-if="!form.processing">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                                </svg>
                                إنشاء الحساب
                            </span>
                            <span v-else class="btn-loading">
                                <svg class="spinner" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                </svg>
                                جارٍ إنشاء الحساب...
                            </span>
                        </button>
                    </form>

                    <p class="security-note">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        بياناتك محمية بتشفير SSL ولن تُشارَك مع أي جهة خارجية
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

/* ── Toast ── */
.toast {
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
    z-index: 100;
    display: flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    font-size: 0.82rem; font-weight: 600;
    border-radius: 30px;
    border: 1px solid;
    backdrop-filter: blur(12px);
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    direction: rtl;
}
.toast--success { color: #d4a843; background: rgba(212,168,67,0.12); border-color: rgba(212,168,67,0.3); }
.toast--error   { color: #f87171; background: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.3); }
.toast-slide-enter-active, .toast-slide-leave-active { transition: all 0.35s ease; }
.toast-slide-enter-from, .toast-slide-leave-to { opacity: 0; transform: translateX(-50%) translateY(-14px); }

/* ── Page wrapper ── */
.page-wrapper {
    min-height: 100vh; display: flex; direction: rtl;
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    background: #05112b; position: relative; overflow: hidden;
}

/* ── Background ── */
.bg-shape { position: absolute; border-radius: 50%; filter: blur(90px); pointer-events: none; }
.shape-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #1a4a8a 0%, #0a2352 60%, transparent 100%);
    top: -150px; right: -100px; opacity: .7;
    animation: pulse 8s ease-in-out infinite;
}
.shape-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #c9952a 0%, #8b6514 60%, transparent 100%);
    bottom: -80px; left: 30%; opacity: .2;
    animation: pulse 10s ease-in-out infinite reverse;
}
.shape-3 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, #1a4a8a 0%, transparent 100%);
    bottom: 20%; right: 10%; opacity: .3;
    animation: pulse 13s ease-in-out infinite;
}
.bg-grid {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px; pointer-events: none;
}
@keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.08)} }

/* ── Left panel ── */
.left-panel {
    flex: 1; display: flex; align-items: center; justify-content: center;
    padding: 3rem 2rem; position: relative; z-index: 2;
}
.left-content {
    max-width: 320px; text-align: center;
    animation: slideIn .7s cubic-bezier(.22,1,.36,1) both;
}
@keyframes slideIn { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }

.gov-seal {
    display: inline-flex; align-items: center; justify-content: center;
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #1a4a8a, #0d2d5e);
    border: 1px solid rgba(255,255,255,.15); border-radius: 50%;
    margin-bottom: 1.4rem;
    box-shadow: 0 8px 28px rgba(26,74,138,.5), 0 0 0 7px rgba(26,74,138,.12);
}
.gov-title { font-size: 1.6rem; font-weight: 700; color: #fff; line-height: 1.3; letter-spacing: -.3px; margin-bottom: .65rem; }
.gov-desc { font-size: .83rem; color: rgba(255,255,255,.4); line-height: 1.8; margin-bottom: 2rem; }

/* ── Steps ── */
.steps { display: flex; flex-direction: column; gap: 0; margin-bottom: 2rem; text-align: right; }
.step { display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 10px; }
.step-line { width: 1px; height: 10px; background: rgba(255,255,255,.08); margin: 0 auto; }
.step-num {
    width: 28px; height: 28px; min-width: 28px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #d4a843, #f0c862);
    color: #05112b; font-size: .78rem; font-weight: 700;
    border-radius: 50%;
}
.step-text { display: flex; flex-direction: column; gap: 2px; }
.step-title { font-size: .82rem; font-weight: 600; color: #fff; }
.step-desc { font-size: .72rem; color: rgba(255,255,255,.38); }

.login-link {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .78rem; color: rgba(255,255,255,.4);
    text-decoration: none; transition: color .2s;
}
.login-link span { color: #d4a843; font-weight: 600; }
.login-link:hover { color: rgba(255,255,255,.65); }

/* ── Right panel ── */
.right-panel {
    width: 540px; display: flex; align-items: center; justify-content: center;
    padding: 2rem 2rem; position: relative; z-index: 2;
    background: rgba(255,255,255,.025);
    border-right: 1px solid rgba(255,255,255,.07);
    backdrop-filter: blur(10px);
}
.register-card {
    width: 100%; max-width: 460px;
    animation: cardIn .65s cubic-bezier(.22,1,.36,1) .1s both;
}
@keyframes cardIn { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* ── Card header ── */
.card-header { margin-bottom: 1.6rem; }
.badge-official {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; font-size: .68rem; font-weight: 600;
    color: #d4a843; background: rgba(212,168,67,.12);
    border: 1px solid rgba(212,168,67,.25);
    border-radius: 20px; margin-bottom: .9rem; letter-spacing: .3px;
}
.card-title { font-size: 1.5rem; font-weight: 700; color: #fff; letter-spacing: -.4px; margin-bottom: 4px; }
.card-subtitle { font-size: .8rem; color: rgba(255,255,255,.38); }

/* ── Fields ── */
.fields-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.field { display: flex; flex-direction: column; gap: 5px; margin-bottom: .85rem; }
.label { font-size: .76rem; font-weight: 500; color: rgba(255,255,255,.6); }
.input-wrapper { position: relative; }
.input-icon {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,.25); pointer-events: none; transition: color .2s;
}
.input {
    width: 100%; padding: 10px 12px 10px 34px;
    font-size: .82rem; color: #fff;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 9px; outline: none; transition: all .25s; font-family: inherit;
}
.input::placeholder { color: rgba(255,255,255,.18); }
.input:hover { background: rgba(255,255,255,.09); border-color: rgba(255,255,255,.18); }
.input:focus { background: rgba(26,74,138,.25); border-color: rgba(59,130,246,.6); box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
.input-wrapper:focus-within .input-icon { color: rgba(147,197,253,.7); }
.field--error .input { border-color: rgba(248,113,113,.5); background: rgba(248,113,113,.05); }
.field--error .input:focus { border-color: rgba(248,113,113,.7); box-shadow: 0 0 0 3px rgba(248,113,113,.12); }
.error-msg { display: flex; align-items: center; gap: 4px; font-size: .7rem; color: #f87171; }

/* ── Submit ── */
.btn-submit {
    width: 100%; margin-top: .3rem; padding: 12px 20px;
    font-size: .88rem; font-weight: 700; color: #05112b;
    background: linear-gradient(135deg, #d4a843 0%, #f0c862 50%, #d4a843 100%);
    background-size: 200% auto;
    border: none; border-radius: 9px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 20px rgba(212,168,67,.3);
    transition: all .25s; font-family: inherit; letter-spacing: .2px;
}
.btn-submit:hover:not(:disabled) { background-position: right center; box-shadow: 0 6px 28px rgba(212,168,67,.45); transform: translateY(-1px); }
.btn-submit:active:not(:disabled) { transform: translateY(0); }
.btn-submit:disabled { opacity: .55; cursor: not-allowed; }
.btn-loading { display: flex; align-items: center; gap: 7px; }
.spinner { animation: spin .9s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.security-note {
    display: flex; align-items: center; justify-content: center; gap: 5px;
    margin-top: 1.2rem; font-size: .68rem; color: rgba(255,255,255,.22); text-align: center;
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .right-panel { width: 100%; }
    .left-panel { display: none; }
}
@media (max-width: 540px) {
    .fields-row { grid-template-columns: 1fr; }
    .right-panel { padding: 1.5rem; }
}
</style>
