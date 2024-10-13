<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { watch ,ref , onBeforeMount} from 'vue';
import Pagination from '@/Components/Paginator.vue';
import ProductTeaser from '@/Components/ProductTeaser.vue';
const page = usePage();

import { useFilterProduct } from "@/stores/useFilterProduct.js";
const useFilter = useFilterProduct();

const props = defineProps({
    products: Array | Object,
});

const products = ref([]);

onBeforeMount(() => {
    useFilter.setNewProducts(props.products);
});

watch(() => useFilter.products, () => {
    if(Object.keys(useFilter.products).length > 0) {
        products.value = useFilter.products;
    }
});

</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $t('results') }}: {{ useFilter.resultsCount }}</span>
        </div>
        <div class="grid grid-cols-4 gap-4">
            <div v-for="product in products" :key="product.id" :class="'prosuct-'+product.id">
                <transition-group class="flex flex-col bg-white items-center gap-1" tag="ul" enter-from-class="opacity-0" enter-to-class="opacity-100" enter-active-class="transition ease-in duration-300" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <ProductTeaser :product="product" />
                </transition-group>
            </div>
        </div>
        <pagination :links="products.links"></pagination>
    </div>
</template>
