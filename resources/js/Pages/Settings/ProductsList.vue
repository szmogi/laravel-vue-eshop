<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import { ref , watch} from 'vue';
import IconField from "primevue/iconfield";
import DataTable from "primevue/datatable";
import InputText from "primevue/inputtext";
import Column from "primevue/column";
import InputIcon from "primevue/inputicon";
import Select from "primevue/select";
import { useFilterProduct } from "@/stores/useFilterProduct.js";
const useFilter = useFilterProduct();
useFilter.initFilter();

const props = defineProps({
    products: Object | Array,
});

const products = ref(props.products);
const productsAll = ref(props.products);
const searchId = ref('');
const searchPrice = ref('');
const searchColor = ref('');
const searchSize = ref('');
const searchCategory = ref('');
const searchVariant = ref('');
const visibleVariants = ref(0);

const formatDate = (date) => {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(date).toLocaleDateString('sk-SK', options);
};

watch(
    [searchId, searchPrice, searchColor, searchSize, searchCategory, searchVariant],
    () => {
        products.value = productsAll.value.filter((product) => {
            // Filtrovanie podľa ID
            const matchesId = !searchId.value || String(product.id).includes(searchId.value);

            // Filtrovanie podľa ceny
            const matchesPrice = !searchPrice.value || String(product.price).includes(searchPrice.value);

            // Filtrovanie podľa farby
            const matchesColor = !searchColor.value || String(product.color?.id).includes(searchColor.value);

            // Filtrovanie podľa veľkosti
            const matchesSize = !searchSize.value || String(product.size?.id).includes(searchSize.value);

            // Filtrovanie podľa kategórie
            const matchesCategory = !searchCategory.value || String(product.category?.id).includes(searchCategory.value);

            // Filtrovanie podľa varianty
            const matchesVariant = !searchVariant.value ||
                product.variants?.some((variant) => String(variant.id).includes(searchVariant.value));

            // Vráť iba produkty, ktoré zodpovedajú všetkým filtrom
            return matchesId && matchesPrice && matchesColor && matchesSize && matchesCategory && matchesVariant;
        });
    }
);
</script>

<template>
    <Head title="Products list" />
    <AppLayout title="Products list">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('productsList') }}
            </h2>
        </template>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 container px-10 mx-auto">
           <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center" >
            <DataTable paginator :rows="25"  :value="products" tableStyle="min-width: 50rem" class="w-full ">
                <template #header>
                    <div class="flex flex-row justify-end mb-4 pt-8 items-center">
                        <IconField class="mx-1">
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="searchId" :placeholder=" $t('searchProductId')" />
                        </IconField>
                        <IconField class="mx-1">
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="searchVariant" :placeholder=" $t('searchVariantId')" />
                        </IconField>
                        <IconField class="mx-1">
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="searchPrice" :placeholder=" $t('searchPrice')" />
                        </IconField>
                        <Select v-if="useFilter.colors.length > 0"
                                filter
                                v-model="searchColor"
                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.colors]"
                                optionLabel="name"
                                optionValue="id"
                                class="w-3/12 mx-1"
                                :placeholder="$t('color')">
                        </Select>
                        <Select v-if="useFilter.sizes.length > 0"
                                v-model="searchSize"
                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.sizes]"
                                optionLabel="name"
                                optionValue="id"
                                class="w-3/12 mx-1"
                                :placeholder="$t('size')">
                        </Select>
                        <Select v-if="useFilter.categories.length > 0"
                                v-model="searchCategory"
                                :options=" [{ name: $t('select'), id: 0 }, ...useFilter.categories]"
                                optionLabel="name"
                                optionValue="id"
                                class="w-3/12 mx-1"
                                :placeholder="$t('category')">
                        </Select>
                    </div>
                    <div class="flex flex-row justify-end items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $t('results') }}: {{ products.length }}</span>
                    </div>
                </template>
                <Column field="id" :header="$t('productId')" sortable style="width: 25%"></Column>
                <Column field="name" :header="$t('name')" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ slotProps.data.name }} <br />
                        <span  class="text-sm text-gray-500 dark:text-gray-400 cursor-pointer">{{ $t('variants') }}: {{ slotProps.data.variants.length }}</span>
                        <span class="text-sm text-ecoBlue hover:bg-ecoBlue-light hover:text-white rounded-md py-1 px-2 ml-2 cursor-pointer"
                              :class="visibleVariants === slotProps.data.id ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500'"
                              @click="visibleVariants === slotProps.data.id ? visibleVariants = 0 : visibleVariants = slotProps.data.id">
                            {{ visibleVariants === slotProps.data.id ? $t('hide') : $t('show') }}
                        </span>
                        <ul v-if="visibleVariants === slotProps.data.id" class="flex flex-col gap-2">
                            <li v-for="variant in slotProps.data.variants" :key="variant.id" class="flex flex-row items-center gap-2">
                                <div class="flex flex-row items-center gap-2">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ variant.id }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ variant.name }}</div>
                                    <Link :href="route('products.show', variant.id)" class="text-sm text-ecoBlue hover:bg-ecoBlue-light hover:text-white rounded-md py-1 px-2">
                                        {{ $t('view') }}
                                    </Link>
                                </div>
                            </li>
                        </ul>
                    </template>
                </Column>
                <Column field="category.name" :header="$t('category')" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ slotProps.data.category.name }}
                    </template>
                </Column>
                <Column field="color.name" :header="$t('color')" sortable style="width: 25%">
                    <template #body="slotProps">
                        <div class="flex flex-row items-center gap-2">
                            <span :class="slotProps.data.color.name" class="w-6 h-6 border-2 block" :style="'background:'+slotProps.data.color.name"></span>
                            {{ slotProps.data.color.name }}
                        </div>
                    </template>
                </Column>
                <Column field="size.name" :header="$t('size')" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ slotProps.data.size.name }}
                    </template>
                </Column>
                <Column field="price" :header="$t('price')" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ slotProps.data.price }}
                        <span class="text-gray-500">€</span>
                    </template>
                </Column>
                <Column field="created_at" :header="$t('createdAt')" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ formatDate(slotProps.data.created_at) }}
                    </template>
                </Column>
                <Column class="w-24 !text-end">
                    <template #body="{ data }">
                        <Link :href="route('products.show', data.id)" class="text-sm text-ecoBlue hover:bg-ecoBlue-light hover:text-white rounded-md py-1 px-2">
                            {{ $t('view') }}
                        </Link> <br>
                        <Link :href="route('settings.products.edit.insert', data.id)" class="text-sm text-ecoBlue hover:bg-ecoBlue-light hover:text-white rounded-md py-1 px-2">
                            {{ $t('edit') }}
                        </Link>
                    </template>
                </Column>
            </DataTable>
            </div>
        </div>
    </AppLayout>
</template>
