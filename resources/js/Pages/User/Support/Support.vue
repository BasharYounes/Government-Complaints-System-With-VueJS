<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';


const openQuestion = ref(null);


const toggleQuestion = (index) => {
    openQuestion.value =
        openQuestion.value === index
            ? null
            : index;
};


const faqItems = [
    {
        question: 'كيف يمكنني تقديم شكوى جديدة؟',
        answer:
            'يمكنك تقديم شكوى من الصفحة الرئيسية عبر زر "تقديم شكوى جديدة"، ثم اختيار الجهة الحكومية ونوع الشكوى وإضافة الوصف والموقع والمرفقات إن وجدت.',
    },
    {
        question: 'كيف يمكنني متابعة الشكاوى التي قدمتها؟',
        answer:
            'من الصفحة الرئيسية اختر "شكاواي"، وستظهر جميع الشكاوى التي قمت بتقديمها مع حالتها الحالية ورقم التتبع. يمكنك الضغط على أي شكوى لعرض تفاصيلها.',
    },
    {
        question: 'ما هي حالات الشكوى في النظام؟',
        answer:
            'الحالات المعتمدة حاليًا هي: new و in_progress و completed و rejected. وتتغير حالة الشكوى حسب مرحلة معالجتها من الجهة الحكومية.',
    },
    {
        question: 'ما هو رقم التتبع الخاص بالشكوى؟',
        answer:
            'هو رقم مرجعي فريد يتم إنشاؤه عند تقديم الشكوى، ويمكن استخدامه لتمييز الشكوى ومتابعتها داخل النظام.',
    },
    {
        question: 'هل يمكنني إرفاق ملف مع الشكوى؟',
        answer:
            'نعم، عند إنشاء الشكوى يمكنك إضافة مرفق داعم. كما يمكن عرض المرفقات المرتبطة بالشكوى من صفحة تفاصيل الشكوى.',
    },
    {
        question: 'أين يمكنني تعديل معلومات حسابي؟',
        answer:
            'يمكنك الدخول إلى "ملفي الشخصي" من قسم الإجراءات السريعة في الصفحة الرئيسية لتعديل المعلومات المتاحة في حسابك.',
    },
];
</script>


<template>

    <Head title="الدعم والمساعدة — نظام الشكاوى الحكومية" />


    <div class="app">

        <div class="bg-shape s1"></div>
        <div class="bg-shape s2"></div>
        <div class="bg-grid"></div>


        <!-- ═════════════ TOPBAR ═════════════ -->

        <header class="topbar">

            <div class="topbar-brand">

                <div class="brand-seal">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 56 56"
                        fill="none"
                    >
                        <circle
                            cx="28"
                            cy="28"
                            r="26"
                            stroke="rgba(255,255,255,0.3)"
                            stroke-width="1.5"
                        />

                        <path
                            d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z"
                            fill="rgba(255,255,255,0.95)"
                        />
                    </svg>

                </div>


                <span class="brand-name">
                    نظام الشكاوى الحكومية
                </span>

            </div>


            <Link
                href="/home"
                class="back-btn"
            >
                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>

                الرئيسية
            </Link>

        </header>


        <!-- ═════════════ MAIN ═════════════ -->

        <main class="main">


            <!-- Hero -->

            <section class="hero">

                <div class="hero-icon">

                    <svg
                        width="34"
                        height="34"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="10"
                        />

                        <path
                            d="M9.09 9a3 3 0 0 1 5.83 1
                               c0 2-3 3-3 3"
                        />

                        <line
                            x1="12"
                            y1="17"
                            x2="12.01"
                            y2="17"
                        />
                    </svg>

                </div>


                <div class="hero-content">

                    <p class="hero-kicker">
                        مركز المساعدة
                    </p>

                    <h1>
                        كيف يمكننا مساعدتك؟
                    </h1>

                    <p>
                        يمكنك الوصول بسرعة إلى أهم خدمات النظام
                        أو الاطلاع على إجابات الأسئلة الأكثر شيوعًا.
                    </p>

                </div>

            </section>


            <!-- ═════════════ QUICK HELP ═════════════ -->

            <section>

                <div class="section-heading">

                    <div>

                        <span class="section-kicker">
                            الوصول السريع
                        </span>

                        <h2>
                            ماذا تريد أن تفعل؟
                        </h2>

                    </div>

                </div>


                <div class="help-grid">


                    <!-- New complaint -->

                    <Link
                        href="/create-complaint"
                        class="help-card"
                    >

                        <div class="help-icon">

                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <line
                                    x1="12"
                                    y1="5"
                                    x2="12"
                                    y2="19"
                                />

                                <line
                                    x1="5"
                                    y1="12"
                                    x2="19"
                                    y2="12"
                                />
                            </svg>

                        </div>


                        <div class="help-content">

                            <h3>
                                تقديم شكوى
                            </h3>

                            <p>
                                إنشاء شكوى جديدة وإرسالها
                                إلى الجهة الحكومية المختصة.
                            </p>

                        </div>


                        <div class="help-arrow">
                            ‹
                        </div>

                    </Link>


                    <!-- Complaints -->

                    <Link
                        href="/complaints"
                        class="help-card"
                    >

                        <div class="help-icon">

                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M14 2H6
                                       a2 2 0 0 0-2 2
                                       v16
                                       a2 2 0 0 0 2 2
                                       h12
                                       a2 2 0 0 0 2-2
                                       V8z"
                                />

                                <polyline
                                    points="14 2 14 8 20 8"
                                />
                            </svg>

                        </div>


                        <div class="help-content">

                            <h3>
                                متابعة شكاواي
                            </h3>

                            <p>
                                مشاهدة الشكاوى السابقة
                                ومتابعة حالتها وتفاصيلها.
                            </p>

                        </div>


                        <div class="help-arrow">
                            ‹
                        </div>

                    </Link>


                    <!-- Profile -->

                    <Link
                        href="/profile"
                        class="help-card"
                    >

                        <div class="help-icon">

                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M20 21v-2
                                       a4 4 0 0 0-4-4
                                       H8
                                       a4 4 0 0 0-4 4
                                       v2"
                                />

                                <circle
                                    cx="12"
                                    cy="7"
                                    r="4"
                                />
                            </svg>

                        </div>


                        <div class="help-content">

                            <h3>
                                الملف الشخصي
                            </h3>

                            <p>
                                عرض معلومات حسابك
                                وتحديث البيانات الشخصية.
                            </p>

                        </div>


                        <div class="help-arrow">
                            ‹
                        </div>

                    </Link>

                </div>

            </section>


            <!-- ═════════════ FAQ ═════════════ -->

            <section class="faq-section">

                <div class="section-heading">

                    <div>

                        <span class="section-kicker">
                            الأسئلة الشائعة
                        </span>

                        <h2>
                            قد تجد الإجابة هنا
                        </h2>

                    </div>

                </div>


                <div class="faq-list">

                    <div
                        v-for="(item, index) in faqItems"
                        :key="index"
                        class="faq-item"
                        :class="{
                            active:
                                openQuestion === index
                        }"
                    >

                        <button
                            type="button"
                            class="faq-question"
                            @click="toggleQuestion(index)"
                        >

                            <div class="faq-question-content">

                                <div class="question-number">
                                    {{ String(index + 1).padStart(2, '0') }}
                                </div>

                                <span>
                                    {{ item.question }}
                                </span>

                            </div>


                            <svg
                                class="faq-arrow"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <polyline
                                    points="6 9 12 15 18 9"
                                />
                            </svg>

                        </button>


                        <Transition name="answer">

                            <div
                                v-if="openQuestion === index"
                                class="faq-answer"
                            >
                                <p>
                                    {{ item.answer }}
                                </p>
                            </div>

                        </Transition>

                    </div>

                </div>

            </section>


            <!-- ═════════════ INFO ═════════════ -->

            <section class="info-panel">

                <div class="info-icon">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="10"
                        />

                        <line
                            x1="12"
                            y1="16"
                            x2="12"
                            y2="12"
                        />

                        <line
                            x1="12"
                            y1="8"
                            x2="12.01"
                            y2="8"
                        />
                    </svg>

                </div>


                <div>

                    <h3>
                        هل تواجه مشكلة أثناء استخدام النظام؟
                    </h3>

                    <p>
                        تأكد أولًا من أن بياناتك مكتملة وأنك مسجل الدخول،
                        ثم أعد المحاولة. يمكنك أيضًا الرجوع إلى الصفحة
                        الرئيسية للوصول إلى جميع خدمات المواطن.
                    </p>

                </div>


                <Link
                    href="/home"
                    class="home-btn"
                >
                    العودة للرئيسية
                </Link>

            </section>


        </main>

    </div>

</template>


<style scoped>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


.app {

    min-height: 100vh;

    background: #05112b;

    direction: rtl;

    font-family:
        'Segoe UI',
        Tahoma,
        system-ui,
        sans-serif;

    color:
        rgba(255,255,255,.85);

    position: relative;

    overflow-x: hidden;
}


/* ═════════ Background ═════════ */

.bg-shape {

    position: fixed;

    border-radius: 50%;

    filter: blur(100px);

    pointer-events: none;

    z-index: 0;
}


.s1 {

    width: 600px;

    height: 600px;

    background:
        radial-gradient(
            circle,
            #1a4a8a 0%,
            transparent 70%
        );

    top: -220px;

    right: -160px;

    opacity: .5;
}


.s2 {

    width: 430px;

    height: 430px;

    background:
        radial-gradient(
            circle,
            #c9952a 0%,
            transparent 70%
        );

    bottom: -130px;

    left: 15%;

    opacity: .12;
}


.bg-grid {

    position: fixed;

    inset: 0;

    z-index: 0;

    background-image:

        linear-gradient(
            rgba(255,255,255,.02) 1px,
            transparent 1px
        ),

        linear-gradient(
            90deg,
            rgba(255,255,255,.02) 1px,
            transparent 1px
        );

    background-size:
        48px 48px;

    pointer-events: none;
}


/* ═════════ Topbar ═════════ */

.topbar {

    height: 60px;

    padding:
        0 2rem;

    position: sticky;

    top: 0;

    z-index: 50;

    display: flex;

    align-items: center;

    justify-content: space-between;

    background:
        rgba(5,17,43,.88);

    backdrop-filter:
        blur(16px);

    border-bottom:
        1px solid
        rgba(255,255,255,.07);
}


.topbar-brand {

    display: flex;

    align-items: center;

    gap: 10px;
}


.brand-seal {

    width: 36px;

    height: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #1a4a8a,
            #0d2d5e
        );

    border:
        1px solid
        rgba(255,255,255,.15);

    border-radius: 9px;
}


.brand-name {

    color:
        rgba(255,255,255,.85);

    font-size: .85rem;

    font-weight: 600;
}


.back-btn {

    display: flex;

    align-items: center;

    gap: 7px;

    padding:
        8px 13px;

    color:
        rgba(255,255,255,.65);

    background:
        rgba(255,255,255,.05);

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius: 9px;

    text-decoration: none;

    font-size: .78rem;

    transition:
        all .2s;
}


.back-btn:hover {

    color: #fff;

    background:
        rgba(255,255,255,.1);
}


/* ═════════ Main ═════════ */

.main {

    max-width: 1100px;

    margin: 0 auto;

    padding: 2rem;

    position: relative;

    z-index: 1;

    display: flex;

    flex-direction: column;

    gap: 2rem;
}


/* ═════════ Hero ═════════ */

.hero {

    display: flex;

    align-items: center;

    gap: 20px;

    padding:
        1.8rem 2rem;

    background:
        linear-gradient(
            135deg,
            rgba(26,74,138,.38),
            rgba(13,45,94,.25)
        );

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius: 16px;

    backdrop-filter:
        blur(8px);
}


.hero-icon {

    width: 70px;

    height: 70px;

    min-width: 70px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #d4a843;

    background:
        rgba(212,168,67,.1);

    border:
        1px solid
        rgba(212,168,67,.2);

    border-radius: 16px;
}


.hero-kicker {

    margin-bottom: 5px;

    color: #d4a843;

    font-size: .73rem;
}


.hero-content h1 {

    margin-bottom: 6px;

    color: #fff;

    font-size: 1.45rem;
}


.hero-content p {

    max-width: 620px;

    color:
        rgba(255,255,255,.4);

    font-size: .8rem;

    line-height: 1.7;
}


/* ═════════ Heading ═════════ */

.section-heading {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    margin-bottom:
        1rem;
}


.section-kicker {

    display: block;

    margin-bottom: 4px;

    color: #d4a843;

    font-size: .68rem;
}


.section-heading h2 {

    color:
        rgba(255,255,255,.88);

    font-size: 1rem;
}


/* ═════════ Help cards ═════════ */

.help-grid {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 1rem;
}


.help-card {

    display: flex;

    align-items: center;

    gap: 12px;

    min-height: 115px;

    padding:
        1.2rem;

    color: inherit;

    background:
        rgba(255,255,255,.04);

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius: 14px;

    text-decoration: none;

    transition:
        all .2s;
}


.help-card:hover {

    transform:
        translateY(-2px);

    background:
        rgba(255,255,255,.065);

    border-color:
        rgba(212,168,67,.25);
}


.help-icon {

    width: 44px;

    height: 44px;

    min-width: 44px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #d4a843;

    background:
        rgba(212,168,67,.09);

    border-radius: 10px;
}


.help-content {

    flex: 1;
}


.help-content h3 {

    margin-bottom: 5px;

    color: #fff;

    font-size: .82rem;
}


.help-content p {

    color:
        rgba(255,255,255,.34);

    font-size: .69rem;

    line-height: 1.6;
}


.help-arrow {

    color: #d4a843;

    font-size: 1.5rem;

    transition:
        transform .2s;
}


.help-card:hover
.help-arrow {

    transform:
        translateX(-3px);
}


/* ═════════ FAQ ═════════ */

.faq-list {

    display: flex;

    flex-direction: column;

    gap: 8px;
}


.faq-item {

    overflow: hidden;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid
        rgba(255,255,255,.07);

    border-radius: 11px;

    transition:
        border-color .2s;
}


.faq-item.active {

    border-color:
        rgba(212,168,67,.25);
}


.faq-question {

    width: 100%;

    padding:
        14px 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    color:
        rgba(255,255,255,.72);

    background:
        transparent;

    border: 0;

    cursor: pointer;

    text-align: right;

    font-family: inherit;

    font-size: .78rem;
}


.faq-question-content {

    display: flex;

    align-items: center;

    gap: 12px;
}


.question-number {

    color:
        rgba(212,168,67,.65);

    font-family: monospace;

    font-size: .68rem;
}


.faq-arrow {

    color:
        rgba(255,255,255,.3);

    transition:
        transform .2s;
}


.faq-item.active
.faq-arrow {

    transform:
        rotate(180deg);

    color: #d4a843;
}


.faq-answer {

    padding:
        0 48px 15px 17px;
}


.faq-answer p {

    color:
        rgba(255,255,255,.4);

    font-size: .73rem;

    line-height: 1.8;
}


/* transition */

.answer-enter-active,
.answer-leave-active {

    transition:
        all .2s ease;
}


.answer-enter-from,
.answer-leave-to {

    opacity: 0;

    transform:
        translateY(-5px);
}


/* ═════════ Info Panel ═════════ */

.info-panel {

    display: flex;

    align-items: center;

    gap: 14px;

    padding:
        1.2rem 1.4rem;

    background:
        rgba(59,130,246,.06);

    border:
        1px solid
        rgba(59,130,246,.15);

    border-radius: 13px;
}


.info-icon {

    width: 42px;

    height: 42px;

    min-width: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #93c5fd;

    background:
        rgba(59,130,246,.1);

    border-radius: 9px;
}


.info-panel > div:nth-child(2) {

    flex: 1;
}


.info-panel h3 {

    margin-bottom: 4px;

    color:
        rgba(255,255,255,.78);

    font-size: .78rem;
}


.info-panel p {

    max-width: 650px;

    color:
        rgba(255,255,255,.35);

    font-size: .69rem;

    line-height: 1.7;
}


.home-btn {

    padding:
        8px 13px;

    color: #93c5fd;

    background:
        rgba(59,130,246,.08);

    border:
        1px solid
        rgba(59,130,246,.18);

    border-radius: 8px;

    text-decoration: none;

    white-space: nowrap;

    font-size: .7rem;
}


/* ═════════ Responsive ═════════ */

@media
(max-width: 850px) {

    .help-grid {

        grid-template-columns:
            1fr;
    }
}


@media
(max-width: 640px) {

    .main {

        padding:
            1rem;
    }


    .topbar {

        padding:
            0 1rem;
    }


    .brand-name {

        display: none;
    }


    .hero {

        align-items:
            flex-start;

        padding:
            1.4rem;

        flex-direction:
            column;
    }


    .info-panel {

        align-items:
            flex-start;

        flex-direction:
            column;
    }


    .home-btn {

        width: 100%;

        text-align: center;
    }


    .faq-answer {

        padding:
            0 17px 15px;
    }
}

</style>
