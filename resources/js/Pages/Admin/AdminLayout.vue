<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    pageTitle: {
        type: String,
        default: '',
    },
});

const page = usePage();

/*
|--------------------------------------------------------------------------
| Sidebar collapse (mobile)
|--------------------------------------------------------------------------
*/

const sidebarOpen = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
| One place to add/remove admin sections. `match` is checked against the
| current URL so the active item highlights correctly.
*/

const navItems = [
    {
        label: 'لوحة التحكم',
        href: '/admin/dashboard',
        match: '/admin/dashboard',
        icon: 'grid',
    },
    {
        label: 'الشكاوى',
        href: '/admin/complaints',
        match: '/admin/complaints',
        icon: 'inbox',
    },
    {
        label: 'الموظفون',
        href: '/admin/employees',
        match: '/admin/employees',
        icon: 'users',
    },
    {
        label: 'التقارير',
        href: '/admin/reports',
        match: '/admin/reports',
        icon: 'file-bar',
    },
    {
        label: 'سجل التدقيق',
        href: '/admin/audit-logs',
        match: '/admin/audit-logs',
        icon: 'history',
    },
];

const currentPath = computed(() => page.url.split('?')[0]);

const isActive = (item) => currentPath.value.startsWith(item.match);

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const searchQuery = ref('');

const submitSearch = () => {
    const query = searchQuery.value.trim();

    if (!query) {
        return;
    }

    router.get('/admin/complaints', { keyword: query }, { preserveState: true });
};

/*
|--------------------------------------------------------------------------
| Account
|--------------------------------------------------------------------------
*/

const admin = computed(() => page.props.auth?.admin ?? { name: 'المسؤول' });

const showAccountMenu = ref(false);

const logout = () => router.post('/admin/logout');
</script>

<template>
    <div class="shell">
        <!-- ═══ SIDEBAR ═══ -->
        <aside class="sidebar" :class="{ open: sidebarOpen }">
            <div class="sidebar-brand">
                <div class="brand-seal">
                    <svg width="20" height="20" viewBox="0 0 56 56" fill="none">
                        <circle cx="28" cy="28" r="26" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" />
                        <path d="M28 8L32 20H44L34 27L38 40L28 33L18 40L22 27L12 20H24L28 8Z" fill="rgba(255,255,255,0.95)" />
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-name">الشكاوى الحكومية</span>
                    <span class="brand-tag">لوحة الإدارة</span>
                </div>
            </div>

            <nav class="nav">
                <span class="nav-section-label">الإدارة</span>

                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="nav-item"
                    :class="{ active: isActive(item) }"
                    @click="sidebarOpen = false"
                >
                    <span class="nav-icon">
                        <!-- grid -->
                        <svg v-if="item.icon === 'grid'" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        <!-- inbox -->
                        <svg v-else-if="item.icon === 'inbox'" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                            <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                        </svg>
                        <!-- users -->
                        <svg v-else-if="item.icon === 'users'" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <!-- file-bar (reports) -->
                        <svg v-else-if="item.icon === 'file-bar'" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            <line x1="9" y1="17" x2="9" y2="13"/><line x1="12" y1="17" x2="12" y2="11"/><line x1="15" y1="17" x2="15" y2="15"/>
                        </svg>
                        <!-- history -->
                        <svg v-else width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 16 14"/>
                        </svg>
                    </span>

                    <span>{{ item.label }}</span>
                </Link>
            </nav>

            <div class="sidebar-footer">
                <button type="button" class="logout-link" @click="logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    تسجيل الخروج
                </button>
            </div>
        </aside>

        <!-- Mobile overlay -->
        <div v-if="sidebarOpen" class="overlay" @click="sidebarOpen = false"></div>

        <!-- ═══ MAIN COLUMN ═══ -->
        <div class="main-column">
            <!-- ═══ TOPBAR ═══ -->
            <header class="topbar">
                <div class="topbar-start">
                    <button type="button" class="menu-btn" @click="toggleSidebar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

                    <h1 v-if="pageTitle" class="page-title">{{ pageTitle }}</h1>
                </div>

                <div class="topbar-end">
                    <form class="search-form" @submit.prevent="submitSearch">
                        <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="ابحث عن شكوى، رقم مرجعي..."
                            class="search-input"
                        />
                    </form>

                    <button type="button" class="icon-btn" title="الإشعارات">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </button>

                    <div class="account" @click="showAccountMenu = !showAccountMenu">
                        <div class="account-avatar">{{ admin.name?.charAt(0) ?? 'م' }}</div>
                        <span class="account-name">{{ admin.name }}</span>

                        <Transition name="dropdown">
                            <div v-if="showAccountMenu" class="account-menu" @click.stop>
                                <button type="button" class="account-menu-item" @click="logout">
                                    تسجيل الخروج
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <!-- ═══ CONTENT ═══ -->
            <main class="content">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
* { box-sizing: border-box; }

.shell {
    display: flex;
    min-height: 100vh;
    background: #05112b;
    direction: rtl;
    font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
    color: rgba(255, 255, 255, .88);
}

/* ═════════════ Sidebar ═════════════ */

.sidebar {
    width: 258px;
    min-width: 258px;
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, .025);
    border-left: 1px solid rgba(255, 255, 255, .07);
    position: sticky;
    top: 0;
    height: 100vh;
    z-index: 40;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 1.1rem 1.2rem;
    border-bottom: 1px solid rgba(255, 255, 255, .06);
}

.brand-seal {
    width: 34px;
    height: 34px;
    min-width: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a4a8a, #0d2d5e);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 8px;
}

.brand-text { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
.brand-name { font-size: .8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.brand-tag { font-size: .66rem; color: #d4a843; }

.nav {
    flex: 1;
    overflow-y: auto;
    padding: .9rem .7rem;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.nav-section-label {
    padding: 0 .6rem .4rem;
    font-size: .64rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .28);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 9px;
    color: rgba(255, 255, 255, .55);
    text-decoration: none;
    font-size: .8rem;
    font-weight: 500;
    transition: all .15s;
}

.nav-item:hover {
    background: rgba(255, 255, 255, .045);
    color: rgba(255, 255, 255, .85);
}

.nav-item.active {
    background: rgba(212, 168, 67, .1);
    color: #d4a843;
    font-weight: 600;
}

.nav-icon { display: flex; min-width: 17px; }

.sidebar-footer {
    padding: .8rem;
    border-top: 1px solid rgba(255, 255, 255, .06);
}

.logout-link {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 12px;
    background: transparent;
    border: 0;
    border-radius: 9px;
    color: rgba(248, 113, 113, .75);
    font-family: inherit;
    font-size: .78rem;
    cursor: pointer;
    transition: background .15s;
}

.logout-link:hover { background: rgba(239, 68, 68, .08); }

.overlay {
    display: none;
}

/* ═════════════ Main column ═════════════ */

.main-column {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

/* ═════════════ Topbar ═════════════ */

.topbar {
    height: 58px;
    min-height: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0 1.4rem;
    background: rgba(5, 17, 43, .9);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(255, 255, 255, .06);
    position: sticky;
    top: 0;
    z-index: 30;
}

.topbar-start { display: flex; align-items: center; gap: .9rem; min-width: 0; }

.menu-btn {
    display: none;
    width: 34px;
    height: 34px;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 8px;
    color: rgba(255, 255, 255, .65);
    cursor: pointer;
}

.page-title {
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.topbar-end { display: flex; align-items: center; gap: .6rem; }

.search-form { position: relative; }

.search-icon {
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, .28);
    pointer-events: none;
}

.search-input {
    width: 240px;
    padding: 7px 34px 7px 12px;
    font-size: .78rem;
    color: #fff;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 8px;
    outline: none;
    font-family: inherit;
    transition: all .2s;
}

.search-input::placeholder { color: rgba(255, 255, 255, .22); }
.search-input:focus { border-color: rgba(212, 168, 67, .45); background: rgba(255, 255, 255, .07); }

.icon-btn {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 8px;
    color: rgba(255, 255, 255, .55);
    cursor: pointer;
    transition: all .15s;
}

.icon-btn:hover { background: rgba(255, 255, 255, .09); color: #fff; }

.account {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 10px 4px 4px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 30px;
    cursor: pointer;
}

.account-avatar {
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a4a8a, #d4a843);
    border-radius: 50%;
    font-size: .72rem;
    font-weight: 700;
    color: #fff;
}

.account-name { font-size: .76rem; font-weight: 600; color: rgba(255, 255, 255, .85); white-space: nowrap; }

.account-menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 160px;
    background: #0d2050;
    border: 1px solid rgba(255, 255, 255, .1);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 14px 32px rgba(0, 0, 0, .45);
}

.account-menu-item {
    width: 100%;
    padding: 10px 14px;
    text-align: right;
    background: transparent;
    border: 0;
    color: #f87171;
    font-family: inherit;
    font-size: .76rem;
    cursor: pointer;
}

.account-menu-item:hover { background: rgba(239, 68, 68, .08); }

.dropdown-enter-active, .dropdown-leave-active { transition: all .15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px); }

/* ═════════════ Content ═════════════ */

.content {
    flex: 1;
    padding: 1.4rem;
    max-width: 1440px;
    width: 100%;
    margin: 0 auto;
}

/* ═════════════ Responsive ═════════════ */

@media (max-width: 1024px) {
    .search-input { width: 180px; }
}

@media (max-width: 860px) {
    .sidebar {
        position: fixed;
        right: 0;
        top: 0;
        transform: translateX(100%);
        transition: transform .25s ease;
        box-shadow: -20px 0 40px rgba(0, 0, 0, .4);
    }

    .sidebar.open { transform: translateX(0); }

    .overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 39;
    }

    .menu-btn { display: flex; }

    .search-form { display: none; }

    .account-name { display: none; }

    .content { padding: 1rem; }
}
</style>
