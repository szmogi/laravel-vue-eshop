
<template>
    <div class="relative flex flex-col sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <!-- Header -->
        <header>
            <PageHeader/>
        </header>
        <!-- Banner -->
        <PageBanner v-if="visibleBanner" />
        <!-- Main Content -->
        <main class="container mx-auto">
            <slot></slot> <!-- Tu sa bude vkladať obsah stránok -->
        </main>

        <!-- Footer -->
        <footer>
            <p>© 2024 My Application</p>
        </footer>
    </div>
</template>
<script setup>
import PageHeader from "@/Components/PageHeader.vue";
import PageBanner from "@/Components/PageBanner.vue";

defineProps({
    visibleBanner: {
        type: Boolean,
        default: true,
    },
});
</script>
