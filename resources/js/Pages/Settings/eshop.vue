<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive , onBeforeMount} from 'vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();
import { useUserStore } from "@/Stores/useUserStore.js";
const userStore = useUserStore();
import { useConfigStore } from "@/Stores/useConfig.js";
const configStore = useConfigStore();

const props = defineProps({
    vat: Number | null,
    status: Array | null,
    paymentMethod: Array,
    shippingMethod: Array,
    user: Object,
});
userStore.setUser(props.user);
userStore.ifAdmin();
configStore.vat = props.vat ;
configStore.status = props.status;
configStore.paymentMethod = props.paymentMethod;
configStore.shippingMethod = props.shippingMethod;

const form = reactive({
    vat: configStore.vat ?? 0,
    status:  [],
    paymentMethod: [],
    shippingMethod:  [],
    eshop: [],
})
const editPaymentMethod = (id, name) => {
    form.paymentMethod.id = id;
    form.paymentMethod.name = name;
};

const editShippingMethod = (id, name, price) => {
    form.shippingMethod.id = id;
    form.shippingMethod.name = name;
    form.shippingMethod.price = price;
};

const editStatus = (status, index) => {
    form.status.name = status;
    form.status.id = index;
};

</script>

<template>
    <Head title="Settings" />
    <PageLayout :visible-banner="false">
    <div id="checkout" class="min-h-screen mt-24 bg-gray-100 dark:bg-gray-900">
         <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center">
            <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-4xl py-8">
                {{ $t('SettingsEshop') }}
            </h1>

            <div class="base-info-eshop">
                <div class="flex flex-col justify-center items-center mt-8">
                    <form @submit.prevent="configStore.setEsop(form.eshop)" class="w-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-start justify-start gap-2">
                                <div class="w-6/12 flex flex-col gap-4">
                                    <div class="flex flex-col w-6/12 gap-2">
                                        <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('title') }}</label>
                                        <input required v-model="form.eshop.name" type="text" class=" bg-white dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" :placeholder="'Zadajte '+ $t('title') ">
                                    </div>
                                    <div class="flex flex-col w-6/12 gap-2">
                                        <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('company') }}</label>
                                        <input required v-model="form.eshop.company" type="text" class=" bg-white dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" :placeholder=" 'Zadajte '+ $t('company') ">
                                    </div>
                                    <div class="flex flex-col w-6/12 gap-2">
                                        <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('email') }}</label>
                                        <input required v-model="form.eshop.email" type="text" class=" bg-white dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" :placeholder=" 'Zadajte '+ $t('email') ">
                                    </div>
                                    <div class="flex flex-col w-6/12 gap-2">
                                        <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('phone') }}</label>
                                        <input required v-model="form.eshop.phone" type="text" class=" bg-white dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" :placeholder=" 'Zadajte '+ $t('phone') ">
                                    </div>
                                </div>
                                <div class="w-4/12 flex flex-col gap-2">
                                    <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('address') }}</label>
                                    <textarea required v-model="form.eshop.address" class=" bg-white h-40 dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" :placeholder=" 'Zadajte '+ $t('address') "></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full flex-col justify-center items-start">
                            <button class="bg-ecoGreen-light w-40 py-2 mx-auto block my-4 text-white rounded-md mb-1 px-2">
                                {{ $t('add') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="grid grid-rows-2 grid-flow-col gap-4 mt-12">
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setVat(form.vat)" class="w-full h-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <h3 class="text-center text-xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsVat') }}
                        </h3>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div>
                                    <label for="vat" class="block text text-gray-700 font-bold mb-2">{{ $t('DPH') }}</label>
                                    <input required v-model="form.vat" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-6/12 rounded-md text-sm font-medium" placeholder="0">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setStatus(form.status)" class="w-full h-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsStatus') }}
                        </h1>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="status" class="block text text-gray-700 font-bold mb-2">{{ $t('status') }}</label>
                                    <input required v-model="form.status.name" type="text" class="bg-white w-full dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" placeholder="zadajte novy status">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul class="mt-4 w-full">
                                <li v-for="(status, index) in configStore.status" :key="status.id" class="flex w-full justify-between flex-row py-0.5 items-center gap-2">
                                    <label class="ml-2 uppercase w-full flex justify-between flex-row" :for="status.id">{{ status }}
                                        <div><span @click="editStatus(status,index)" class="edit-status cursor-pointer">✏️</span>
                                        <span class="remove-status cursor-pointer hover:text-red-500" @click="configStore.removeStatus(status)">x</span></div></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setPaymentMethod(form.paymentMethod)" class="w-full h-full mx-auto bg-white p-8 rounded-lg shadow-md">
                        <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsPaymentMethod') }}
                        </h1>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="paymentMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('paymentMethod') }}</label>
                                    <input required v-model="form.paymentMethod.name" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte novu metodu">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul class="mt-4 w-full">
                                <li v-for="paymentMethod in configStore.paymentMethod" :key="paymentMethod.id" class="flex w-full justify-between flex-row py-0.5 items-center gap-2">
                                    <label class="ml-2 uppercase w-full flex justify-between flex-row" :for="paymentMethod.id">{{ paymentMethod.name }}
                                        <div><span @click="editPaymentMethod(paymentMethod.id, paymentMethod.name)" class="edit-payment-method cursor-pointer">✏️</span>
                                        <span class="remove-payment-method cursor-pointer hover:text-red-500" @click="configStore.removePaymentMethod(paymentMethod)">x</span></div></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setShippingMethod(form.shippingMethod)" class="w-full mx-auto h-full  bg-white p-4 rounded-lg shadow-md">
                        <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsShippingMethod') }}
                        </h1>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                <div class="w-6/12">
                                    <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingMethod') }}</label>
                                    <input required v-model="form.shippingMethod.name" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte novu metodu">
                                </div>
                                <div class="w-4/12">
                                    <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingPrice') }}</label>
                                    <input required v-model="form.shippingMethod.price" type="number" class="bg-white dark:bg-gray-800 text-start  py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte cenu">
                                </div>
                                <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                    {{ $t('add') }}
                                </button>
                            </div>
                            <ul class="mt-4 w-full">
                                <li v-for="shippingMethod in configStore.shippingMethod" :key="shippingMethod.id" class="flex w-full justify-between flex-row py-0.5 items-center gap-2">
                                    <label class="ml-2 uppercase w-full flex justify-between flex-row" :for="shippingMethod.id">{{ shippingMethod.name }} - €{{ shippingMethod.price }}
                                        <div><span @click="editShippingMethod(shippingMethod.id, shippingMethod.name, shippingMethod.price)" class="edit-shipping-method cursor-pointer">✏️</span>
                                        <span class="remove-shipping-method cursor-pointer hover:text-red-500" @click="configStore.removeShippingMethod(shippingMethod)">x</span></div></label>
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
