<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();
import { useUserStore } from "@/Stores/useUserStore.js";
const userStore = useUserStore();
import { useConfigStore } from "@/Stores/useConfig.js";
const configStore = useConfigStore();
const props = defineProps({
    vat: Number,
    status: Array,
    paymentMethod: Array,
    shippingMethod: Array,
    user: Object,
});

userStore.setUser(props.user);
userStore.ifAdmin();

const form = reactive({
    vat: configStore.vat ?? 0,
    status: configStore.status ?? [],
    paymentMethod: configStore.paymentMethod ?? [],
    shippingMethod: configStore.shippingMethod ?? [],
});

</script>

<template>
    <Head title="Settings" />
    <PageLayout :visible-banner="false">
    <div id="checkout" class="min-h-screen mt-24 bg-gray-100 dark:bg-gray-900">
         <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center">
            <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-4xl py-8">
                {{ $t('SettingsEshop') }}
            </h1>
            <div class="flex flex-col justify-center items-center mt-8">
                <div class="w-6/12 mx-auto bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-center text-xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                        {{ $t('settingsVat') }}
                    </h3>
                    <form @submit.prevent="configStore.setVat(form.vat)" class="w-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div>
                                    <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('vat') }}</label>
                                    <input v-model="form.vat" type="text" class="bg-white dark:bg-gray-800 text-center py-2 px-1 w-6/12 rounded-md text-sm font-medium" placeholder="0">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class=" w-6/12 mx-auto bg-white p-4 rounded-lg shadow-md my-8">
                    <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                        {{ $t('settingsStatus') }}
                    </h1>
                    <form @submit.prevent="configStore.setStatus(form.status)" class="w-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="status" class="block text text-gray-700 font-bold mb-2">{{ $t('status') }}</label>
                                    <input v-model="form.status" type="text" class="bg-white w-full dark:bg-gray-800 text-center py-2 px-1 rounded-md text-sm font-medium" placeholder="zadajte novy status">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul>
                                <li v-for="status in configStore.status" :key="status.id" class="flex flex-row items-center gap-2">
                                    <label class="ml-2 uppercase w-full" :for="status.id">{{ status.label }}<span class="remove-status" @click="configStore.removeStatus(status.id)">x</span></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class=" w-6/12 mx-auto bg-white p-8 rounded-lg shadow-md my-8">
                    <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                        {{ $t('settingsPaymentMethod') }}
                    </h1>
                    <form @submit.prevent="configStore.setPaymentMethod(form.paymentMethod)" class="w-full mx-auto bg-white p-8 rounded-lg shadow-md">
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="paymentMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('paymentMethod') }}</label>
                                    <input v-model="form.paymentMethod.name" type="number" class="bg-white dark:bg-gray-800 text-center py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte novu metodu">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul>
                                <li v-for="paymentMethod in configStore.paymentMethod" :key="paymentMethod.id" class="flex flex-row items-center gap-2">
                                    <label class="ml-2 uppercase w-full" :for="paymentMethod.id">{{ paymentMethod.name }}<span class="remove-payment-method" @click="configStore.removePaymentMethod(paymentMethod.id)">x</span></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class=" w-6/12 mx-auto bg-white p-8 rounded-lg shadow-md my-8">
                    <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-8">
                        {{ $t('settingsShippingMethod') }}
                    </h1>
                    <form @submit.prevent="configStore.setShippingMethod(form.shippingMethod)" class="w- mx-auto bg-white p-4 rounded-lg shadow-md">
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingMethod') }}</label>
                                    <input v-model="form.shippingMethod.name" type="number" class="bg-white dark:bg-gray-800 text-center py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte novu metodu">
                                </div>
                                <div class="w-4/12">
                                    <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingPrice') }}</label>
                                    <input v-model="form.shippingMethod.price" type="number" class="bg-white dark:bg-gray-800 text-center py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte cenu">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul>
                                <li v-for="shippingMethod in configStore.shippingMethod" :key="shippingMethod.id" class="flex flex-row items-center gap-2">
                                    <label class="ml-2 uppercase w-full" :for="shippingMethod.id">{{ shippingMethod.name }}<span class="remove-shipping-method" @click="configStore.removeShippingMethod(shippingMethod.id)">x</span></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </PageLayout>
</template>
