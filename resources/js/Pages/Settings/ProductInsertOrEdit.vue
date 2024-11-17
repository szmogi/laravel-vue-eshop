<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import { reactive, ref } from 'vue';
import { useFilterProduct} from "@/stores/useFilterProduct.js";
import Button from "primevue/button";
import Select from 'primevue/select';

const useFilter = useFilterProduct();
useFilter.initFilter();

import { useProductsStore } from "@/stores/useProducts.js";
import ImageUploader from "@/Components/ImageUploader.vue";

const productsStore = useProductsStore();
productsStore.getMasterIdList();

const props = defineProps({
    product: Object,
});
const product = ref(props.product);

const form = reactive({
    name: props.product.name,
    description: props.product.description ?? null,
    price: props.product.price ?? 0,
    category_id: props.product.category_id ?? null,
    sku: props.product.sku ?? null,
    size_id: props.product.size_id ?? null,
    color_id: props.product.color_id ?? null,
    master_id: props.product.master_id ?? null,
    stock: props.product.stock ?? 0,
    images: props.product.images ?? [],
    listPrice: props.product.listPrice ?? 0,
});


const handleUploadSuccess = (data, type) => {
    data = data.data;


    data.forEach(function(value, key) {
        if(type === 'imageGallery') {
            form.images.push(value);
        } else {
            form.images = form.images.filter(image => image.id !== value.id);
        }
    });

    console.log(data);
};

const handleUploadError = (error) => {
    console.error('Chyba pri nahrávaní obrázka:', error);
};

console.log(product.value);
console.log(productsStore.masterIdList);

</script>

<template>
    <Head title="Products" />
    <AppLayout title="Products insert or edit">
        <template #header>
            <h2 v-if="product" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('productsEditId') }} - {{ product.id }}
            </h2>
            <h2 v-else class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('productsInsert') }}
            </h2>
        </template>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 container px-10 mx-auto">
            <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center" >
            <form @submit.prevent="form.post(route('products.store'))">
                <div class="flex flex-col justify-between items-start">
                    <div class="w-full mx-auto bg-white p-4 rounded-lg shadow-md">
                            <div class="flex flex-col justify-between items-start">
                            <div class="mt-1 w-full flex flex-col items-end justify-between gap-2">
                                <div class="w-full grid grid-cols-2 gap-4 py-4">
                                    <div>
                                        <label for="name" class="block text text-gray-700 font-bold mb-2">{{ $t('masterId') }}</label>
                                        <select v-model="form.master_id" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte názov">
                                            <option value="">{{ $t('select') }}</option>
                                            <option v-for="masterId in productsStore.masterIdList" :key="masterId.id" :value="masterId.master_id">{{ masterId.master_id }}</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <label for="stock" class="block text text-gray-700 font-bold mb-2">{{ $t('stock') }}</label>
                                        <input required v-model="form.stock" type="number" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte názov">
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="name" class="block text text-gray-700 font-bold mb-2">{{ $t('productName') }}</label>
                                    <input required v-model="form.name" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte názov">
                                </div>
                                <div class="w-full">
                                    <label for="description" class="block text text-gray-700 font-bold mb-2">{{ $t('description') }}</label>
                                    <textarea v-model="form.description" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte popis"></textarea>
                                </div>
                                <div class="w-full">
                                    <label for="description" class="block text text-gray-700 font-bold mb-2">{{ $t('images') }}</label>
                                    <ImageUploader :multiSelectImage="true" :maxImagesCount="10" @upload-success="handleUploadSuccess($event, 'imageGallery')" @upload-error="handleUploadError" />
                                    <div v-if="form.images.length > 0 &&  form.images !== []" class="flex py-6 flex-row flex-wrap w-6/12 gap-2">
                                        <li v-for="image in form.images" :key="image.id" class="flex w-20 h-20 relative flex-row items-center justify-between">
                                            <img class="w-full h-20 object-cover mr-2" :src="image.filepath" alt="image.filename" width="25" />
                                            <div @click="form.images = form.images.filter(image => image.id !== image.id)" class="flex flex-row w-6 h-6 cursor-pointer rounded absolute top-0 right-0 bg-red-500 text-white justify-center items-center">
                                                <span class="text-white" >x</span>
                                            </div>
                                        </li>
                                    </div>
                                </div>
                                <div class="w-full grid grid-cols-3 gap-4">
                                    <div class="">
                                        <label for="price" class="block text text-gray-700 font-bold mb-2">{{ $t('price') }}</label>
                                        <input required v-model="form.price" type="number" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte cenu">
                                    </div>
                                    <div class="">
                                        <label for="price" class="block text text-gray-700 font-bold mb-2">{{ $t('listPrice') }}</label>
                                        <input required v-model="form.listPrice" type="number" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte cenu">
                                    </div>
                                    <div class="">
                                        <label for="sku" class="block text text-gray-700 font-bold mb-2">{{ $t('sku') }}</label>
                                        <input required v-model="form.sku" type="text" class="bg-white dark:bg-gray-800 text-start py-2 px-1 w-full rounded-md text-sm font-medium" placeholder="zadajte názov">
                                    </div>
                                    <div class="">
                                        <label for="category_id" class="block text text-gray-700 font-bold mb-2">{{ $t('category') }}</label>
                                        <Select v-if="useFilter.categories.length > 0"
                                                filter
                                                v-model="form.category_id"
                                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.categories]"
                                                optionLabel="name"
                                                optionValue="id"
                                                class="w-full"
                                                :placeholder="$t('color')">
                                        </Select>
                                    </div>
                                    <div class="">
                                        <label for="size_id" class="block text text-gray-700 font-bold mb-2">{{ $t('size') }}</label>
                                        <Select v-if="useFilter.sizes.length > 0"
                                                filter
                                                v-model="form.size_id"
                                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.sizes]"
                                                optionLabel="name"
                                                optionValue="id"
                                                class="w-full"
                                                :placeholder="$t('color')">
                                        </Select>
                                    </div>
                                    <div class="">
                                        <label for="color_id" class="block text text-gray-700 font-bold mb-2">{{ $t('color') }}</label>
                                        <Select v-if="useFilter.colors.length > 0"
                                                filter
                                                v-model="form.color_id"
                                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.colors]"
                                                optionLabel="name"
                                                optionValue="id"
                                                class="w-full"
                                                :placeholder="$t('color')">

                                        </Select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
           </div>
        </div>
    </AppLayout>
</template>
