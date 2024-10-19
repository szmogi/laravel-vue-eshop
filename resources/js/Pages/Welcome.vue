<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

import ProductList from '@/Pages/Products/ProductList.vue';
import SmallCart from '@/Components/SmallCart.vue';
import PageLayout from '@/Layouts/PageLayout.vue';

import { useI18n } from 'vue-i18n';
import ProductFilter from "@/Components/ProductFilter.vue";
const { t, locale } = useI18n();
const currentLocale = computed(() => locale.value);

const languages = [
    { code: 'sk', label: 'SK' },
    { code: 'en', label: 'EN' },
];

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    products: Object,
});

</script>

<template>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    </head>
    <Head title="Welcome"/>
    <PageLayout>
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex text-5xl text-stone-700 justify-center mt-16 py-8 sm:items-center sm:justify-between ">
                {{ $t('welcome') }}
            </div>
            <ProductFilter />
            <ProductList :products="products" />
        </div>
    </PageLayout>
</template>

<style scoped>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

.active {
    font-weight: bold; /* Highlight the active language button */
    text-decoration: underline; /* Add an underline to the active language button */
}
</style>
