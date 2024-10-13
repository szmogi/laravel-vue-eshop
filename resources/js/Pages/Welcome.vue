<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

import ProductList from '@/Pages/Products/ProductList.vue';
import SmallCart from '@/Components/SmallCart.vue';

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

const sortedLanguages = computed(() => {
    return languages.sort((a, b) => (a.code === currentLocale ? -1 : 1));
});
const switchLanguage = (lang) => {
    locale.value = lang; // Change locale for i18n
    localStorage.setItem('locale', lang);
    visit(window.location.pathname + '?lang=' + lang); // Trigger Laravel localization
};

</script>

<template>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    </head>
    <Head title="Welcome" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed bg-ecoGreen w-full sm:top-0 sm:end-0 p-6 text-end z-10 flex justify-between align-center">
            <div class="flex  justify-start text-ecoGray-light items-center gap-3">
                <div class="text-3xl">
                    Eshopka
                </div>
                <div class="flex ml-8 items-center text-ms gap-3 flex-row">
                    <button
                        v-for="lang in sortedLanguages"
                        :key="lang.code"
                        @click="switchLanguage(lang.code)"
                        :class="{ active: currentLocale === lang.code }">
                        {{ lang.label }}
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold hover:underline text-ecoGray-light hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white ">{{ $t('orders') }}</Link>
                <template v-else>
                        <Link :href="'/'" class="font-semibold flex flex-row items-center hover:underline text-ecoGray-light mr-4 hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white">
                        <SmallCart />
                    </Link>
                    <Link :href="route('login')" class="font-semibold text-ecoGray-light hover:underline hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white">{{ $t('login') }}</Link>
                    <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold hover:underline text-ecoGray-light hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white ">{{ $t('register') }}</Link>
                </template>
            </div>

        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex text-5xl text-stone-700 justify-center mt-16 py-8 sm:items-center sm:justify-between ">
                {{ $t('welcome') }}
            </div>
            <ProductFilter />
            <ProductList :products="products" />
        </div>
    </div>
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
