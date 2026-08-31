<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({ title: String })

</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow">
            <Link href="/dashboard"> لوحةالتحكم </Link>
            <Link href="/users">المستخدمون</Link>
            <Link href="/profile"> الملفالشخصي </Link>
        </nav>
        <main class="container mx-auto py-8">
        <slot />
        </main>
    </div>
</template>
