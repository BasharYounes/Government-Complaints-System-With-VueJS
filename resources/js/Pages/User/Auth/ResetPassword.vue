<script setup>
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();

const flash = ref(page.props.flash?.error || page.props.flash?.success);
const flashType = ref(page.props.flash?.error ? 'error' : 'success');

const props = defineProps({
    email: {
        type: String,
        required: true
    }
});

const form = useForm({
    email: props.email || '',
    password: '',
    password_confirmation: '',
});



const submit = () => {
    form.post('/reset-password', {
        onSuccess: () => console.log('تم إعادة تعيين كلمة السر بنجاح'),
        onError: () => {},
    });
};

watch(() => page.props.flash, (newFlash) => {
    flash.value = newFlash?.error || newFlash?.success;
    flashType.value = newFlash?.error ? 'error' : 'success';

    if (flash.value) {
        setTimeout(() => { flash.value = null; }, 5000);
    }
});
</script>

<template>
    <Head title="إعادة تعيين كلمة السر — نظام إدارة الشكاوى الحكومية" />

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
        <!-- Background Elements -->
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-shape shape-3"></div>
        <div class="bg-grid"></div>

        <div class="reset-container">
            <div class="reset-card">
                <!-- Card Header -->
                <div class="card-header">
                    <div class="badge-official">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        إعادة تعيين آمنة
                    </div>
                    <h1 class="card-title">إعادة تعيين كلمة السر</h1>
                    <p class="card-subtitle">
                        أدخل كلمة السر الجديدة لحسابك
                    </p>
                </div>

                <form @submit.prevent="submit" class="reset-form">
                    <!-- Hidden Token -->
                    <input type="hidden" v-model="form.token" />

                    <!-- Email (Readonly) -->
                    <div class="field">
                        <label for="email" class="label">البريد الإلكتروني</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input
                                id="email"
                                :value="email"
                                type="email"
                                class="input"
                                dir="ltr"
                                readonly
                                autocomplete="email"
                            />
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="field">
                        <label for="password" class="label">كلمة السر الجديدة</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                class="input"
                                dir="ltr"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <span v-if="form.errors.password" class="error-msg">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="field">
                        <label for="password_confirmation" class="label">تأكيد كلمة السر الجديدة</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="••••••••"
                                class="input"
                                dir="ltr"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <span v-if="form.errors.password_confirmation" class="error-msg">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ form.errors.password_confirmation }}
                        </span>
                    </div>

                    <button type="submit" class="btn-submit" :disabled="form.processing">
                        <span v-if="!form.processing">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 13l4 4L19 7"/>
                            </svg>
                            تحديث كلمة السر
                        </span>
                        <span v-else class="btn-loading">
                            <svg class="spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            جارٍ التحديث...
                        </span>
                    </button>
                </form>

                <div class="back-to-login">
                    <a href="/user-log-in">
                        تذكرت كلمة السر؟
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
/* نفس الستايلات المستخدمة في باقي الصفحات */
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
.shape-1 { width: 500px; height: 500px; background: radial-gradient(circle, #1a4a8a 0%, #0a2352 60%, transparent 100%); top: -150px; right: -100px; opacity: .7; }
.shape-2 { width: 350px; height: 350px; background: radial-gradient(circle, #c9952a 0%, #8b6514 60%, transparent 100%); bottom: -80px; left: 30%; opacity: .25; }
.shape-3 { width: 200px; height: 200px; background: radial-gradient(circle, #1a4a8a 0%, transparent 100%); bottom: 20%; right: 10%; opacity: .3; }

.bg-grid {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}

/* Toast */
.toast {
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
    z-index: 100; display: flex; align-items: center; gap: 8px;
    padding: 10px 20px; font-size: 0.82rem; font-weight: 600;
    border-radius: 30px; border: 1px solid; backdrop-filter: blur(12px); direction: rtl;
}
.toast--success { color: #d4a843; background: rgba(212,168,67,0.12); border-color: rgba(212,168,67,0.3); }
.toast--error { color: #f87171; background: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.3); }

/* Card */
.reset-container { position: relative; z-index: 2; width: 100%; max-width: 460px; padding: 2rem; }

.reset-card {
    background: rgba(255,255,255,.025);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 16px;
    padding: 2.5rem 2rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 40px rgba(0,0,0,.3);
}

.card-header { margin-bottom: 2rem; text-align: center; }
.badge-official {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; font-size: 0.68rem; font-weight: 600;
    color: #d4a843; background: rgba(212,168,67,.12);
    border: 1px solid rgba(212,168,67,.25); border-radius: 20px; margin-bottom: 1rem;
}
.card-title { font-size: 1.55rem; font-weight: 700; color: #fff; margin-bottom: 8px; }
.card-subtitle { font-size: 0.85rem; color: rgba(255,255,255,.45); line-height: 1.5; }

/* Form Fields */
.field { margin-bottom: 1.4rem; }
.label {
    font-size: 0.78rem; font-weight: 500; color: rgba(255,255,255,.65);
    margin-bottom: 6px; display: block;
}

.input-wrapper { position: relative; }
.input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,.35); pointer-events: none;
}

.input {
    width: 100%; padding: 12px 14px 12px 42px;
    font-size: 0.9rem; color: #fff;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px;
    transition: all 0.25s;
}

.input:focus {
    background: rgba(26,74,138,.25);
    border-color: rgba(59,130,246,.7);
    box-shadow: 0 0 0 4px rgba(59,130,246,.15);
}

.input[readonly] {
    background: rgba(255,255,255,.04);
    color: rgba(255,255,255,.7);
    cursor: default;
}

.error-msg {
    display: flex; align-items: center; gap: 5px;
    color: #f87171; font-size: 0.73rem; margin-top: 6px;
}

/* Button */
.btn-submit {
    width: 100%; padding: 14px; margin-top: 1.2rem;
    font-size: 0.95rem; font-weight: 700; color: #05112b;
    background: linear-gradient(135deg, #d4a843 0%, #f0c862 100%);
    border: none; border-radius: 10px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.25s;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212,168,67,.4);
}

.btn-submit:disabled { opacity: 0.65; cursor: not-allowed; }

.btn-loading { display: flex; align-items: center; gap: 8px; }
.spinner { animation: spin 0.9s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Back Link */
.back-to-login {
    text-align: center;
    margin-top: 2rem;
}

.back-to-login a {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.82rem; color: rgba(255,255,255,.5);
    text-decoration: none; transition: color 0.2s;
}

.back-to-login a span { color: #d4a843; font-weight: 600; }
.back-to-login a:hover { color: rgba(255,255,255,.8); }
</style>
