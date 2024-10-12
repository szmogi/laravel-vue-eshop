<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Paginator.vue';
import ProductTeaser from '@/Components/ProductTeaser.vue';
const page = usePage();

const props = defineProps({
    products: Array | Object,
});


console.log(props.products);

</script>

<template>
    <div>
        <div class="grid grid-cols-4 gap-4">
            <div v-for="product in products.data" :key="product.id" :class="'prosuct-'+product.id">
                <ProductTeaser :product="product" />
            </div>
        </div>
        <pagination :links="products.links"></pagination>
    </div>
</template>
