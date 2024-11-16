<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <Head title="Simple pages list" />
    <AppLayout title="Simple pages list">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('simplePagesList') }}
            </h2>
        </template>
        <div>

        </div>
    </AppLayout>
</template>
