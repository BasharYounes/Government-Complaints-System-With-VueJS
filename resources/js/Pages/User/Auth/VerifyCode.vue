<script setup>
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';

const page = usePage();

const props = defineProps({
    email: { type: String, required: true }
});
const email = computed(() => page.props.email || '');

const flash = ref(null);
const flashType = ref('success');

// إعداد النموذج
const form = useForm({
    code: '',
    email: props.email || '',
});

const resendForm = useForm({
    email: props.email,
});

// دالة تحديث الـ Flash (الأهم)
const updateFlash = () => {
    const newFlash = page.props.flash || {};

    if (newFlash.success) {
        flash.value = newFlash.success;
        flashType.value = 'success';
    } else if (newFlash.error) {
        flash.value = newFlash.error;
        flashType.value = 'error';
    } else if (Object.keys(page.props.errors || {}).length > 0) {
        // في حال وجود أخطاء validation
        flash.value = Object.values(page.props.errors)[0];
        flashType.value = 'error';
    }

    // إخفاء الرسالة بعد 5 ثواني
    if (flash.value) {
        setTimeout(() => {
            flash.value = null;
        }, 5000);
    }
};

// تحديث الـ Flash عند أي تغيير في page.props
watch(() => page.props, updateFlash, { deep: true });

// تحديث عند تحميل الصفحة
onMounted(() => {
    form.email = props.email;
    updateFlash();
});

// دالة الإرسال
const submit = () => {
    form.post('/verify-code', {
        preserveScroll: true,
        onSuccess: () => {
            // إذا نجح التحقق، Inertia سيعيد توجيهك عادة، لكن إذا أردت إظهار رسالة:
            console.log('تم التحقق بنجاح');
        },
        onError: () => {
            // سيتم تحديث الـ flash تلقائيًا عبر الـ watch
            console.log('فشل التحقق');
        }
    });
};

// إعادة إرسال الكود
const resendCode = () => {
    resendForm.post('/resend-code', {
        preserveScroll: true,
        onSuccess: () => {
            // يمكنك إظهار رسالة نجاح هنا أيضًا
        }
    });
};
</script>

<template>
    <Head title="التحقق من الرمز — نظام إدارة الشكاوى الحكومية" />

    <Transition name="toast-slide">
        <div v-if="flash" class="toast" :class="flashType === 'error' ? 'toast--error' : 'toast--success'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <template v-if="flashType === 'success'">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </template>
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

        <div class="verify-container">
            <div class="verify-card">

                <div class="card-header">
                    <div class="badge-official">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        تحقق رسمي آمن
                    </div>
                    <h1 class="card-title">التحقق من الرمز</h1>
                    <p class="card-subtitle">
                        تم إرسال رمز التفعيل إلى بريدك الإلكتروني<br>
                        يرجى إدخال الرمز المكون من 6 أرقام
                    </p>
                </div>

                <form @submit.prevent="submit" class="verify-form">

                    <div class="code-field">
                        <label for="code" class="label">رمز التحقق</label>
                        <div class="input-wrapper">
                            <input
                                id="code"
                                v-model="form.code"
                                type="text"
                                maxlength="6"
                                placeholder="123456"
                                class="code-input"
                                dir="ltr"
                                required
                                autocomplete="one-time-code"
                                :disabled="form.processing"
                            />
                        </div>
                        <span v-if="form.errors.code" class="error-msg">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ form.errors.code }}
                        </span>
                    </div>

                    <div class="email-field">
                        <label for="email" class="label">البريد الإلكتروني</label>
                        <div class="input-wrapper">
                            <input
                                id="email"
                                :value="email"
                                type="email"
                                class="code-input email-input"
                                dir="ltr"
                                autocomplete="email"
                            />
                        </div>
                        <span v-if="form.errors.email" class="error-msg">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <button type="submit" class="btn-submit" :disabled="form.processing">
                        <span v-if="!form.processing">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 13l4 4L19 7"/>
                            </svg>
                            تحقق من الرمز
                        </span>
                        <span v-else class="btn-loading">
                            <svg class="spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            جارٍ التحقق...
                        </span>
                    </button>
                </form>

                <div class="resend-section">
                    <p>لم يصلك الرمز؟</p>
                    <button
                        @click="resendCode"
                        class="resend-btn"
                        :disabled="resendForm.processing"
                    >
                        {{ resendForm.processing ? 'جارٍ إرسال الرمز...' : 'إعادة إرسال الرمز' }}
                    </button>
                </div>

                <div class="login-link">
                    <a href="/user-log-in">
                        لديك حساب بالفعل؟
                        <span>تسجيل الدخول</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

* { box-sizing: border-box; margin: 0; padding: 0; }

.page-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    direction: rtl;
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    background: #05112b;
    position: relative;
    overflow: hidden;
}

.bg-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
}
.shape-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #1a4a8a 0%, #0a2352 60%, transparent 100%);
    top: -150px; right: -100px; opacity: .7;
}
.shape-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #c9952a 0%, #8b6514 60%, transparent 100%);
    bottom: -80px; left: 30%; opacity: .2;
}
.shape-3 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, #1a4a8a 0%, transparent 100%);
    bottom: 20%; right: 10%; opacity: .3;
}
.bg-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}

.toast {
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
    z-index: 100;
    display: flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    font-size: 0.82rem; font-weight: 600;
    border-radius: 30px;
    border: 1px solid;
    backdrop-filter: blur(12px);
    direction: rtl;
}
.toast--success { color: #d4a843; background: rgba(212,168,67,0.12); border-color: rgba(212,168,67,0.3); }
.toast--error { color: #f87171; background: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.3); }
.toast-slide-enter-active, .toast-slide-leave-active { transition: all 0.35s ease; }
.toast-slide-enter-from, .toast-slide-leave-to { opacity: 0; transform: translateX(-50%) translateY(-14px); }

/* Card */
.verify-container {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 460px;
    padding: 2rem;
}

.verify-card {
    background: rgba(255,255,255,.025);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 16px;
    padding: 2.5rem 2rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.card-header {
    margin-bottom: 2rem;
    text-align: center;
}

.badge-official {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    font-size: 0.68rem;
    font-weight: 600;
    color: #d4a843;
    background: rgba(212,168,67,.12);
    border: 1px solid rgba(212,168,67,.25);
    border-radius: 20px;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.55rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 8px;
}

.card-subtitle {
    font-size: 0.85rem;
    color: rgba(255,255,255,.45);
    line-height: 1.5;
}

.verify-form {
    margin-bottom: 1.5rem;
}

.label {
    font-size: 0.78rem;
    font-weight: 500;
    color: rgba(255,255,255,.65);
    margin-bottom: 6px;
    display: block;
}

.input-wrapper {
    position: relative;
}

.code-input,
.email-input {
    width: 100%;
    padding: 16px 20px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px;
    color: #fff;
    transition: all 0.25s;
}

.code-input {
    font-size: 1.4rem;
    letter-spacing: 12px;
    text-align: center;
    font-family: monospace;
}

.email-input {
    font-size: 0.95rem;
    letter-spacing: normal;
    text-align: left;
    cursor: default;
}

.code-input:focus,
.email-input:focus {
    outline: none;
    background: rgba(26,74,138,.25);
    border-color: rgba(59,130,246,.7);
    box-shadow: 0 0 0 4px rgba(59,130,246,.15);
}

.code-input::placeholder {
    color: rgba(255,255,255,.25);
    letter-spacing: 2px;
}

.error-msg {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #f87171;
    font-size: 0.73rem;
    margin-top: 6px;
}

.btn-submit {
    width: 100%;
    padding: 14px;
    font-size: 0.95rem;
    font-weight: 700;
    color: #05112b;
    background: linear-gradient(135deg, #d4a843 0%, #f0c862 100%);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s;
    margin-top: 1.2rem;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212,168,67,.4);
}

.btn-submit:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

.btn-loading {
    display: flex;
    align-items: center;
    gap: 8px;
}

.spinner {
    animation: spin 0.9s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.resend-section {
    text-align: center;
    margin: 1.8rem 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,.55);
}

.resend-btn {
    background: none;
    border: none;
    color: #d4a843;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s;
}

.resend-btn:hover:not(:disabled) {
    background: rgba(212,168,67,.1);
    color: #f0c862;
}

.resend-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.login-link {
    text-align: center;
    margin-top: 1rem;
}

.login-link a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    transition: color 0.2s;
}

.login-link a span {
    color: #d4a843;
    font-weight: 600;
}

.login-link a:hover {
    color: rgba(255,255,255,.8);
}

/* Responsive */
@media (max-width: 480px) {
    .verify-card {
        padding: 2rem 1.5rem;
    }
    .code-input {
        font-size: 1.25rem;
        letter-spacing: 8px;
    }
}
</style>
