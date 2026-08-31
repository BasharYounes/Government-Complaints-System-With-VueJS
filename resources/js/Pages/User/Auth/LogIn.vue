<script setup>
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});
</script>

<template>
    <Head title="تسجيل الدخول — نظام إدارة الشكاوى الحكومية" />

    <div class="login-wrapper">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-grid"></div>

        <div class="left-panel">
            <div class="left-content">
                <div class="gov-seal">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                        <circle cx="28" cy="28" r="26" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                        <circle cx="28" cy="28" r="20" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                        <path d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z" fill="rgba(255,255,255,0.9)"/>
                    </svg>
                </div>
                <h2 class="gov-title">نظام إدارة<br/>الشكاوى الحكومية</h2>
                <p class="gov-desc">منصة رسمية موحّدة لتلقّي الشكاوى<br/>ومتابعتها وضمان حقوق المواطنين</p>

                <div class="stats">
                    <div class="stat">
                        <span class="stat-num">١٢٤٬٠٠٠+</span>
                        <span class="stat-label">شكوى مُعالَجة</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="stat-num">٩٨٪</span>
                        <span class="stat-label">نسبة الرضا</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-card">
                <div class="card-header">
                    <div class="badge-official">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        بوابة رسمية آمنة
                    </div>
                    <h1 class="card-title">تسجيل الدخول</h1>
                    <p class="card-subtitle">أدخل بياناتك للوصول إلى لوحة التحكم</p>
                </div>

                <form @submit.prevent="form.post('/login')" novalidate>
                    <div class="field" :class="{ 'field--error': form.errors.email }">
                        <label for="email" class="label">البريد الإلكتروني</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="email" v-model="form.email" class="input"
                                placeholder="example@gov.sa" autocomplete="email" dir="ltr"/>
                        </div>
                        <span v-if="form.errors.email" class="error-msg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div class="field" :class="{ 'field--error': form.errors.password }">
                        <label for="password" class="label">كلمة المرور</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" id="password" v-model="form.password" class="input"
                                placeholder="••••••••" autocomplete="current-password" dir="ltr"/>
                        </div>
                        <span v-if="form.errors.password" class="error-msg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <!-- رابط هل نسيت كلمة السر -->
                    <div class="forgot-password">
                        <a href="/forget-password" class="forgot-link">
                            هل نسيت كلمة السر؟
                        </a>
                    </div>

                    <button type="submit" class="btn-submit" :disabled="form.processing">
                        <span v-if="!form.processing">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            دخول إلى النظام
                        </span>
                        <span v-else class="btn-loading">
                            <svg class="spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            جارٍ التحقق...
                        </span>
                    </button>
                </form>

                <p class="security-note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    هذه البوابة محمية بتشفير SSL. بياناتك في أمان تام.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.login-wrapper {
    min-height: 100vh;
    display: flex;
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
    top: -150px; right: -100px;
    opacity: 0.7;
    animation: pulse 8s ease-in-out infinite;
}
.shape-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #c9952a 0%, #8b6514 60%, transparent 100%);
    bottom: -80px; left: 30%;
    opacity: 0.25;
    animation: pulse 10s ease-in-out infinite reverse;
}
.bg-grid {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
}
@keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.08)} }

.left-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
}

.left-content {
    max-width: 340px;
    text-align: center;
    animation: slideIn 0.7s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes slideIn { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:translateX(0)} }

.gov-seal {
    display: inline-flex; align-items: center; justify-content: center;
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #1a4a8a, #0d2d5e);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(26,74,138,0.5), 0 0 0 8px rgba(26,74,138,0.15);
}

.gov-title {
    font-size: 1.75rem; font-weight: 700; color: #fff;
    line-height: 1.3; letter-spacing: -0.3px; margin-bottom: 0.75rem;
}
.gov-desc { font-size: 0.875rem; color: rgba(255,255,255,0.45); line-height: 1.8; margin-bottom: 2.5rem; }

.stats {
    display: flex; align-items: center; justify-content: center; gap: 2rem;
    padding: 1.25rem 2rem;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
}
.stat { display: flex; flex-direction: column; align-items: center; gap: 3px; }
.stat-num { font-size: 1.2rem; font-weight: 700; color: #d4a843; letter-spacing: -0.5px; }
.stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.4); }
.stat-divider { width: 1px; height: 36px; background: rgba(255,255,255,0.1); }

.right-panel {
    width: 480px;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem;
    background: rgba(255,255,255,0.025);
    border-right: 1px solid rgba(255,255,255,0.07);
    backdrop-filter: blur(10px);
}
.login-card {
    width: 100%; max-width: 380px;
    animation: cardIn 0.65s cubic-bezier(0.22,1,0.36,1) 0.1s both;
}
@keyframes cardIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

.card-header { margin-bottom: 2rem; }

.badge-official {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; font-size: 0.72rem; font-weight: 600;
    color: #d4a843;
    background: rgba(212,168,67,0.12);
    border: 1px solid rgba(212,168,67,0.25);
    border-radius: 20px; margin-bottom: 1rem; letter-spacing: 0.3px;
}

.card-title { font-size: 1.6rem; font-weight: 700; color: #fff; letter-spacing: -0.4px; margin-bottom: 5px; }
.card-subtitle { font-size: 0.83rem; color: rgba(255,255,255,0.4); }

.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }
.label { font-size: 0.8rem; font-weight: 500; color: rgba(255,255,255,0.65); }
.input-wrapper { position: relative; }
.input-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,0.25); pointer-events: none; transition: color 0.2s;
}
.input {
    width: 100%; padding: 11px 14px 11px 38px;
    font-size: 0.875rem; color: #fff;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px; outline: none;
    transition: all 0.25s; font-family: inherit;
}
.input::placeholder { color: rgba(255,255,255,0.18); }
.input:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.18); }
.input:focus {
    background: rgba(26,74,138,0.25);
    border-color: rgba(59,130,246,0.6);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.input-wrapper:focus-within .input-icon { color: rgba(147,197,253,0.7); }
.field--error .input { border-color: rgba(248,113,113,0.5); background: rgba(248,113,113,0.05); }
.error-msg { display: flex; align-items: center; gap: 5px; font-size: 0.75rem; color: #f87171; }

.btn-submit {
    width: 100%; margin-top: 0.5rem; padding: 12px 20px;
    font-size: 0.9rem; font-weight: 600; color: #05112b;
    background: linear-gradient(135deg, #d4a843 0%, #f0c862 50%, #d4a843 100%);
    background-size: 200% auto;
    border: none; border-radius: 10px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 20px rgba(212,168,67,0.3);
    transition: all 0.25s; font-family: inherit; letter-spacing: 0.2px;
}
.btn-submit:hover:not(:disabled) {
    background-position: right center;
    box-shadow: 0 6px 28px rgba(212,168,67,0.45);
    transform: translateY(-1px);
}
.btn-submit:active:not(:disabled) { transform: translateY(0); }
.btn-submit:disabled { opacity: 0.55; cursor: not-allowed; }
.btn-loading { display: flex; align-items: center; gap: 8px; }
.spinner { animation: spin 0.9s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.security-note {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 1.5rem; font-size: 0.72rem; color: rgba(255,255,255,0.25); text-align: center;
}

/* رابط هل نسيت كلمة السر */
.forgot-password {
    text-align: right;
    margin: 8px 0 18px 0;
}

.forgot-link {
    color: #d4a843;
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.forgot-link:hover {
    color: #f0c862;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .login-wrapper { flex-direction: column; }
    .left-panel { padding: 2.5rem 1.5rem 1rem; }
    .gov-title { font-size: 1.3rem; }
    .right-panel { width: 100%; border-right: none; border-top: 1px solid rgba(255,255,255,0.07); }
}
</style>
