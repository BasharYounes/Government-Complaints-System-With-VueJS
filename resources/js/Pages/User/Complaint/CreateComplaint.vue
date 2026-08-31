<script setup>
import { onMounted, ref } from 'vue'
import { useForm, usePage, Head, Link } from '@inertiajs/vue3'
import CustomSelect from '@/Components/CustomSelect.vue'

const page = usePage()
const flash = ref(page.props.flash?.error || page.props.flash?.success)
const flashType = ref(page.props.flash?.error ? 'error' : 'success')
const governmentEntities = ref([])
const loadingEntities = ref(false)
const entitiesError = ref('')

const form = useForm({
    government_entity_id: null,
    type: '',
    description: '',
    location: {
        address: '',
        details: '',
    },
    file: null,
})

const loadGovernmentEntities = async () => {
    loadingEntities.value = true
    entitiesError.value = ''

    try {
        const response = await fetch('/government-entities/all-entities', {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })

        // if (response.status === 401) {
        //     window.location.href = '/user-log-in'
        //     return
        // }

        const payload = await response.json()

        if (!response.ok) {
            governmentEntities.value = []
            entitiesError.value = payload?.message || 'تعذر تحميل الجهات الحكومية.'
            return
        }

        governmentEntities.value = payload?.data ?? []

        if (governmentEntities.value.length === 1) {
            form.government_entity_id = governmentEntities.value[0].id
        }
    } catch (error) {
        entitiesError.value = 'تعذر تحميل الجهات الحكومية.'
    } finally {
        loadingEntities.value = false
    }
}

onMounted(() => {
    loadGovernmentEntities()
})

const submit = () => {
    form.post('/complaints/create', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="إنشاء شكوى جديدة — نظام إدارة الشكاوى" />

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
                <Link href="/" class="back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    الرئيسية
                </Link>
            </div>
        </header>

        <!-- ═══ MAIN ═══ -->
        <main class="main">
            <div class="form-layout">
                <!-- Header -->
                <div class="form-header">
                    <div class="form-header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <div class="form-header-text">
                        <h1 class="form-title">إنشاء شكوى جديدة</h1>
                        <p class="form-subtitle">أدخل البيانات المطلوبة وسيتم توجيه شكواك للجهة المختصة فوراً</p>
                    </div>
                </div>

                <!-- Alerts -->
                <Transition name="fade">
                    <div v-if="flash" :class="['alert', flashType === 'error' ? 'alert--error' : 'alert--success']">
                        <svg v-if="flashType === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ flash }}
                    </div>
                </Transition>

                <div v-if="entitiesError" class="alert alert--warning">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    {{ entitiesError }}
                </div>

                <!-- Form Panel -->
                <div class="panel">
                    <form @submit.prevent="submit" enctype="multipart/form-data" class="form-grid">
                        <!-- Government Entity -->
                        <div class="field-group">
                            <label for="government_entity_id" class="field-label">
                                الجهة الحكومية
                                <span class="required">*</span>
                            </label>
                            <CustomSelect
                            v-model="form.government_entity_id"
                            :options="governmentEntities"
                            placeholder="اختر الجهة الحكومية"
                            :disabled="loadingEntities"
                            :loading="loadingEntities"
                            />
                            <div v-if="form.errors.government_entity_id" class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ form.errors.government_entity_id }}
                            </div>
                            <div v-if="loadingEntities" class="field-hint">
                                <span class="spinner"></span>
                                جاري تحميل الجهات...
                            </div>
                        </div>

                        <!-- Complaint Type -->
                        <div class="field-group">
                            <label for="type" class="field-label">
                                نوع الشكوى
                                <span class="required">*</span>
                            </label>
                            <input
                                id="type"
                                type="text"
                                class="field-input"
                                v-model="form.type"
                                placeholder="مثال: كهرباء، طرق، مياه"
                            />
                            <div v-if="form.errors.type" class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ form.errors.type }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="field-group field-group--full">
                            <label for="description" class="field-label">
                                وصف الشكوى
                                <span class="required">*</span>
                            </label>
                            <textarea
                                id="description"
                                class="field-input field-textarea"
                                rows="5"
                                v-model="form.description"
                                placeholder="اكتب تفاصيل الشكوى بشكل واضح ومختصر..."
                            />
                            <div v-if="form.errors.description" class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Location Address -->
                        <div class="field-group">
                            <label for="location_address" class="field-label">الموقع</label>
                            <input
                                id="location_address"
                                type="text"
                                class="field-input"
                                v-model="form.location.address"
                                placeholder="مثال: شارع الملك، الحي الأول"
                            />
                            <span class="field-hint">يمكن إضافة معلومات إضافية داخل حقل التفاصيل.</span>
                        </div>

                        <!-- Location Details -->
                        <div class="field-group">
                            <label for="location_details" class="field-label">تفاصيل الموقع</label>
                            <input
                                id="location_details"
                                type="text"
                                class="field-input"
                                v-model="form.location.details"
                                placeholder="مثال: بالقرب من المدرسة"
                            />
                        </div>

                        <!-- File Upload -->
                        <div class="field-group field-group--full">
                            <label for="file" class="field-label">إرفاق ملف</label>
                            <div class="file-input-wrap">
                                <input
                                    id="file"
                                    type="file"
                                    class="file-input"
                                    @change="form.file = $event.target.files[0]"
                                />
                                <div class="file-fake">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                    </svg>
                                    <span class="file-fake-text">
                                        {{ form.file ? form.file.name : 'اختر ملفاً من جهازك' }}
                                    </span>
                                </div>
                            </div>
                            <!-- <span class="field-hint">اسم الحقل يجب أن يبقى file حتى يصل إلى AttachmentRequest.</span> -->
                            <div v-if="form.errors.file" class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ form.errors.file }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn btn--secondary" @click="form.reset()">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                </svg>
                                مسح الحقول
                            </button>
                            <button type="submit" class="btn btn--primary" :disabled="form.processing || loadingEntities">
                                <span v-if="form.processing" class="spinner spinner--btn"></span>
                                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                {{ form.processing ? 'جاري الإرسال...' : 'إنشاء الشكوى' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
    color: rgba(255,255,255,.85);
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

.back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: .78rem; font-weight: 500;
    color: rgba(255,255,255,.6); text-decoration: none;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 8px; transition: all .2s;
}
.back-btn:hover { background: rgba(255,255,255,.1); color: #fff; }

/* ── Main ── */
.main { position: relative; z-index: 1; padding: 2rem; max-width: 800px; margin: 0 auto; }

/* ── Form Layout ── */
.form-layout { display: flex; flex-direction: column; gap: 1.25rem; }

.form-header {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.5rem 1.75rem;
    background: linear-gradient(135deg, rgba(26,74,138,0.4) 0%, rgba(13,45,94,0.3) 100%);
    border: 1px solid rgba(255,255,255,.08); border-radius: 16px;
    backdrop-filter: blur(8px);
}
.form-header-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(212,168,67,.15); border: 1px solid rgba(212,168,67,.25);
    border-radius: 12px; color: #d4a843;
}
.form-title { font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 3px; }
.form-subtitle { font-size: .82rem; color: rgba(255,255,255,.42); }

/* ── Alerts ── */
.alert {
    display: flex; align-items: center; gap: 10px;
    padding: .9rem 1.1rem; border-radius: 12px;
    font-size: .82rem; font-weight: 500;
    border: 1px solid transparent;
}
.alert--success { background: rgba(34,197,94,.1); color: #4ade80; border-color: rgba(34,197,94,.2); }
.alert--error   { background: rgba(239,68,68,.1);  color: #f87171; border-color: rgba(239,68,68,.2); }
.alert--warning { background: rgba(234,179,8,.1);  color: #facc15; border-color: rgba(234,179,8,.2); }

/* ── Panel ── */
.panel {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08); border-radius: 14px;
    overflow: hidden; backdrop-filter: blur(8px);
    padding: 1.75rem;
}

/* ── Form Grid ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}
.field-group--full { grid-column: 1 / -1; }

.field-label {
    display: block;
    font-size: .8rem; font-weight: 600;
    color: rgba(255,255,255,.7);
    margin-bottom: 6px;
}
.required { color: #f87171; margin-right: 3px; }

.field-input {
    width: 100%; padding: 10px 14px; font-size: .85rem;
    color: #fff; background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1); border-radius: 10px; outline: none;
    font-family: inherit; transition: all .2s;
}
.field-input::placeholder { color: rgba(255,255,255,.2); }
.field-input:focus {
    border-color: rgba(212,168,67,.5);
    box-shadow: 0 0 0 3px rgba(212,168,67,.12);
    background: rgba(255,255,255,.08);
}
.field-input:disabled { opacity: .5; cursor: not-allowed; }

.field-textarea { resize: vertical; min-height: 120px; line-height: 1.6; }

/* Select */
.select-wrap { position: relative; }
.field-select {
    appearance: none; -webkit-appearance: none;
    padding-left: 36px; cursor: pointer;
}
.select-arrow {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,.3); pointer-events: none;
}

/* File Input */
.file-input-wrap { position: relative; }
.file-input {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2;
}
.file-fake {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px;
    background: rgba(255,255,255,.06);
    border: 1px dashed rgba(255,255,255,.15); border-radius: 10px;
    color: rgba(255,255,255,.4); font-size: .85rem;
    transition: all .2s;
}
.file-input-wrap:hover .file-fake {
    border-color: rgba(212,168,67,.4);
    background: rgba(212,168,67,.05);
    color: rgba(255,255,255,.6);
}
.file-fake-text { direction: ltr; text-align: right; }

/* Hints & Errors */
.field-hint {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: rgba(255,255,255,.3);
    margin-top: 5px;
}
.field-error {
    display: flex; align-items: center; gap: 5px;
    font-size: .75rem; color: #f87171;
    margin-top: 5px;
}

/* Spinner */
.spinner {
    display: inline-block; width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,.2);
    border-top-color: #d4a843;
    border-radius: 50%;
    animation: spin .8s linear infinite;
}
.spinner--btn { width: 16px; height: 16px; border-top-color: #05112b; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Buttons ── */
.form-actions {
    grid-column: 1 / -1;
    display: flex; justify-content: flex-end; gap: 10px;
    padding-top: .5rem; margin-top: .5rem;
    border-top: 1px solid rgba(255,255,255,.06);
}

.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 20px; font-size: .85rem; font-weight: 600;
    border-radius: 10px; cursor: pointer; font-family: inherit;
    transition: all .2s; border: none;
}
.btn--primary {
    color: #05112b;
    background: linear-gradient(135deg, #d4a843, #f0c862);
    box-shadow: 0 4px 16px rgba(212,168,67,.3);
}
.btn--primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(212,168,67,.45); }
.btn--primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.btn--secondary {
    color: rgba(255,255,255,.6);
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
}
.btn--secondary:hover { background: rgba(255,255,255,.1); color: #fff; }

/* ── Transitions ── */
.fade-enter-active, .fade-leave-active { transition: all .25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-6px); }

/* ── Responsive ── */
@media (max-width: 640px) {
    .main { padding: 1rem; }
    .form-grid { grid-template-columns: 1fr; }
    .form-header { flex-direction: column; text-align: center; }
    .topbar { padding: 0 1rem; }
    .brand-name { display: none; }
    .form-actions { flex-direction: column-reverse; }
    .btn { justify-content: center; width: 100%; }
}
</style>
