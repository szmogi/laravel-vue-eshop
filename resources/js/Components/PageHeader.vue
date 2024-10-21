<template>
  <div class="sm:fixed opacity-90 bg-ecoBlue w-full sm:top-0 sm:end-0 p-6 text-end z-10 flex justify-between align-center">
    <div class="flex  justify-start text-white items-center gap-3">
        <div class="text-3xl">
            <Link :href="route('home')">{{ $t('SiteName') }}</Link>
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
        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold hover:underline text-white hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white ">{{ $t('orders') }}</Link>
        <template v-else>
            <Link :href="'/'" class="font-semibold flex flex-row items-center hover:underline text-white mr-4 hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white">
                <SmallCart class="opacity-100" />
            </Link>
            <Link :href="route('login')" class="font-semibold text-white hover:underline hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white">{{ $t('login') }}</Link>
            <Link v-if="!$page.props.auth.user" :href="route('register')" class="ms-4 font-semibold hover:underline text-white hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white ">{{ $t('register') }}</Link>
        </template>
    </div>
  </div>
</template>

<style scoped>
.active {
    font-weight: bold; /* Highlight the active language button */
    text-decoration: underline; /* Add an underline to the active language button */
}
</style>

<script setup>
import { useI18n } from 'vue-i18n';
import { useCartStore } from "@/stores/useCart.js";
import {computed, ref} from 'vue';
import { Link } from '@inertiajs/vue3';
import SmallCart from "@/Components/SmallCart.vue";

const { t, locale } = useI18n();
const currentLocale = ref(locale.value);

const languages = [
    { code: 'sk', label: 'SK' },
    { code: 'en', label: 'EN' },
];

const sortedLanguages = computed(() => {
    return languages.sort((a, b) => (a.code === currentLocale ? -1 : 1));
});
const switchLanguage = (lang) => {
    locale.value = lang; // Change locale for i18n
    localStorage.setItem('locale', lang);
    currentLocale.value = lang;
    visit(window.location.pathname + '?lang=' + lang); // Trigger Laravel localization
};
useCartStore().initCart();

</script>

