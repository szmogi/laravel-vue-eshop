<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive , onBeforeMount , ref , watch} from 'vue';
import PageLayout from '@/Layouts/PageLayout.vue';
import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();
import { useUserStore } from "@/Stores/useUserStore.js";
const userStore = useUserStore();
import { useConfigStore } from "@/Stores/useConfig.js";
const configStore = useConfigStore();
import ImageUploader from '@/Components/ImageUploader.vue';
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
const editPaymentMethod = (paymentMethod) => {
    form.paymentMethod.id = paymentMethod.id;
    form.paymentMethod.name = paymentMethod.name;
    form.paymentMethod.nameEn = paymentMethod.nameEn;
    form.paymentMethod.description = paymentMethod.description;
    form.paymentMethod.descriptionEn = paymentMethod.descriptionEn;
    form.paymentMethod.active = paymentMethod.active;

    if(paymentMethod.image) {
        form.paymentMethod.image = paymentMethod.image;
        form.paymentMethod.imagePath = paymentMethod.imagePath;
        uploadedFile.id = paymentMethod.image;
        uploadedFile.filepath = paymentMethod.imagePath;
    }
};

const editShippingMethod = (shippingMethod) => {
    form.shippingMethod.id = shippingMethod.id;
    form.shippingMethod.name = shippingMethod.name;
    form.shippingMethod.price = shippingMethod.price;
    form.shippingMethod.nameEn = shippingMethod.nameEn;
    form.shippingMethod.description = shippingMethod.description;
    form.shippingMethod.descriptionEn = shippingMethod.descriptionEn;
    form.shippingMethod.active = shippingMethod.active;

    if(shippingMethod.image) {
        form.shippingMethod.image = shippingMethod.image;
        form.shippingMethod.imagePath = shippingMethod.imagePath;
        uploadedFile.id = shippingMethod.image;
        uploadedFile.filepath = shippingMethod.imagePath;
    }
};

const editStatus = (status, index) => {
    form.status.name = status;
    form.status.id = index;
};

const uploadedFile = ref(null);

const handleUploadSuccess = (data, type) => {
    uploadedFile.value = data;

    if(type === 'paymentMethod') {
        form.paymentMethod.image = data.id;
        form.paymentMethod.imagePath = data.filepath;
    } else if(type === 'shippingMethod') {
        form.shippingMethod.image = data.id;
        form.shippingMethod.imagePath = data.filepath;
    }
};

const handleUploadError = (error) => {
    console.error('Chyba pri nahrávaní obrázka:', error);
};

watch(() => configStore.successResponse, () => {
    if(configStore.successResponse) {
        configStore.successResponse = false;
        uploadedFile.value = null;
    }
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
                    <div class="w-full h-full mx-auto bg-white p-4 rounded-lg shadow-md">
                        <form @submit.prevent="configStore.setVat(form.vat)" class="mb-4 pb-4" >
                            <h3 class="text-center text-xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                                {{ $t('settingsVat') }}
                            </h3>
                            <div class="flex flex-col justify-between items-start">
                                <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                    <div>
                                        <label for="vat" class="block text lowercase text-gray-700 font-bold mb-2">{{ $t('settingsVat') }}</label>
                                        <input required v-model="form.vat" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-6/12 rounded-md text-sm font-medium" placeholder="0">
                                    </div>
                                    <button class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                        {{ $t('add') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form @submit.prevent="configStore.setStatus(form.status)" class="w-full border-t-4 pt-4 mt-4 h-full mx-auto">
                            <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                                {{ $t('settingsStatus') }}
                            </h1>
                            <div class="flex flex-col justify-between items-start">
                                <div class="mt-1 w-full flex flex-row items-end justify-between gap-2">
                                    <div class="w-6/12">
                                        <label for="status" class="block text text-gray-700 font-bold mb-2">{{ $t('status') }}</label>
                                        <input required v-model="form.status.name" type="text" class="bg-white w-full dark:bg-gray-800 text-start py-2 px-1 rounded-md text-sm font-medium" placeholder="zadajte stav">
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
                </div>
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setPaymentMethod(form.paymentMethod)" class="w-full h-full mx-auto bg-white p-8 rounded-lg shadow-md">
                        <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsPaymentMethod') }}
                        </h1>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-col items-end justify-between gap-2">
                                <div class="w-full">
                                    <label for="paymentMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('paymentMethod') }}</label>
                                    <input required v-model="form.paymentMethod.name" type="text" class="bg-white mb-2 dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte nazov">
                                    <label for="paymentMethod" class="block text-sm text-gray-700  mb-2">English name</label>
                                    <input required v-model="form.paymentMethod.nameEn" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="enter payment method name">
                                </div>
                                <div class="w-full">
                                    <label for="paymentMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('paymentDescription') }}</label>
                                    <textarea v-model="form.paymentMethod.description" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte popis"></textarea>
                                    <label for="paymentMethod" class="block text-sm text-gray-700  mb-2">English description</label>
                                    <textarea v-model="form.paymentMethod.descriptionEn" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="enter payment description"></textarea>
                                </div>
                                <div class="w-full justify-start border-t-2 border-b-2 py-4 items-center">
                                    <ImageUploader :remove-id="form.paymentMethod.id ? form.paymentMethod.id : null" @upload-success="handleUploadSuccess($event, 'paymentMethod')" @upload-error="handleUploadError" />
                                    <div v-if="uploadedFile">
                                        <h4>Nahraný súbor:</h4>
                                        <p><strong>ID:</strong> {{ uploadedFile.id }}</p>
                                        <img :src="uploadedFile.filepath" alt="Nahraný obrázok" width="200" />
                                    </div>
                                </div>
                                <div class="w-full flex flex-row items-end justify-between gap-2">
                                    <div class="flex flex-row items-center justify-between">
                                        <label for="paymentMethod" class="block text text-gray-700 font-bold mr-2">{{ $t('active') }}</label>
                                        <input type="checkbox" v-model="form.paymentMethod.active">
                                    </div>
                                    <div class="flex flex-row items-center justify-between">
                                        <button @click="form.paymentMethod = []" class="bg-ecoGreen-light text-white rounded-md py-1 mr-2 mb-1 px-2">
                                            {{ $t('resetSettings') }}
                                        </button>
                                        <button v-if="!form.paymentMethod.id" class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                            {{ $t('add') }}
                                        </button>
                                        <button v-if="form.paymentMethod.id" class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                            {{ $t('edit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <ul class="mt-4 w-full border-t-4 py-4">
                                <li v-for="paymentMethod in configStore.paymentMethod" :key="paymentMethod.id" class="flex w-full justify-between flex-row py-0.5 items-center gap-2">
                                        <label class="ml-2 uppercase w-full flex justify-between flex-row" :for="paymentMethod.id">
                                        <div class="flex flex-row items-center"><img class="mr-4" v-if="paymentMethod.image" :src="paymentMethod.imagePath" alt="paymentMethod.name" width="25" />
                                        {{ paymentMethod.name }}</div>
                                        <div><span @click="editPaymentMethod(paymentMethod)" class="edit-payment-method cursor-pointer">✏️</span>
                                        <span v-if="paymentMethod.custom" class="remove-payment-method cursor-pointer hover:text-red-500" @click="configStore.removePaymentMethod(paymentMethod)">x</span></div></label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="w-full mb-4">
                    <form @submit.prevent="configStore.setShippingMethod(form.shippingMethod); form.shippingMethod = {}" class="w-full mx-auto h-full  bg-white p-4 rounded-lg shadow-md">
                        <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
                            {{ $t('settingsShippingMethod') }}
                        </h1>
                        <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-col items-end justify-between gap-2">
                                <div class="w-full flex flex-row items-end justify-between gap-2">
                                    <div class="w-6/12">
                                        <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingMethod') }}</label>
                                        <input required v-model="form.shippingMethod.name" type="text" class="bg-white dark:bg-gray-800 mb-2 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte nazov">
                                        <label for="shippingMethod" class="block text-sm text-gray-700 mb-2">English name</label>
                                        <input required v-model="form.shippingMethod.nameEn" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="enter shipping method name">
                                    </div>
                                    <div class="w-4/12">
                                        <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingPrice') }}</label>
                                        <input required v-model="form.shippingMethod.price" type="number" class="bg-white dark:bg-gray-800 text-start  py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte cenu">
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="shippingMethod" class="block text text-gray-700 font-bold mb-2">{{ $t('shippingDescription') }}</label>
                                    <textarea v-model="form.shippingMethod.description" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte popis"></textarea>
                                    <label for="shippingMethod" class="block text-sm text-gray-700  mb-2">English description</label>
                                    <textarea v-model="form.shippingMethod.descriptionEn" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="enter shipping description"></textarea>
                                </div>
                                <div class="w-full justify-start border-t-2 border-b-2 py-4 items-center">
                                    <ImageUploader :remove-id="form.shippingMethod.id ? form.shippingMethod.id : null" @upload-success="handleUploadSuccess($event, 'shippingMethod')" @upload-error="handleUploadError" />
                                    <div v-if="uploadedFile">
                                        <h4>Nahraný súbor:</h4>
                                        <p><strong>ID:</strong> {{ uploadedFile.id }}</p>
                                        <img :src="uploadedFile.filepath" alt="Nahraný obrázok" width="200" />
                                    </div>
                                </div>
                                <div class="w-full flex flex-row items-end justify-between gap-2">
                                    <div class="flex flex-row items-center justify-between">
                                        <label for="shippingMethod" class="block text text-gray-700 font-bold mr-2">{{ $t('active') }}</label>
                                        <input type="checkbox" v-model="form.shippingMethod.active">
                                    </div>
                                    <div class="flex flex-row items-center justify-between">
                                        <button @click="form.shippingMethod = []" class="bg-ecoGreen-light text-white rounded-md py-1 mr-2 mb-1 px-2">
                                            {{ $t('resetSettings') }}
                                        </button>
                                        <button v-if="!form.shippingMethod.id" class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                            {{ $t('add') }}
                                        </button>
                                        <button v-if="form.shippingMethod.id" class="bg-ecoGreen-light text-white rounded-md py-1 mb-1 px-2">
                                            {{ $t('edit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <ul class="mt-4 w-full border-t-4 py-4">
                                <li v-for="shippingMethod in configStore.shippingMethod" :key="shippingMethod.id" class="flex w-full justify-between flex-row py-0.5 items-center gap-2">
                                    <label class="ml-2 uppercase w-full flex justify-between flex-row" :for="shippingMethod.id">
                                        <div class="flex flex-row items-center"><img class="mr-4" v-if="shippingMethod.image" :src="shippingMethod.imagePath" alt="shippingMethod.name" width="25" />
                                        {{ shippingMethod.name }} - €{{ shippingMethod.price }}</div>
                                        <div><span @click="editShippingMethod(shippingMethod)" class="edit-shipping-method cursor-pointer">✏️</span>
                                        <span v-if="shippingMethod.custom" class="remove-shipping-method cursor-pointer hover:text-red-500" @click="configStore.removeShippingMethod(shippingMethod)">x</span></div></label>
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
