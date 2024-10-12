<script setup>

import { usePage } from '@inertiajs/vue3';
import CartButton from '@/Components/CarButton.vue';
const page = usePage();

const props = defineProps({
    product: Array | Object,
});

const product = props.product;
const variations = product.variants.filter(variant => variant.size_id === product.size_id);

// Create a new array with unique colors
const uniqueProducts = variations.reduce((acc, current) => {
    // Check if the current color already exists in the accumulator
    const found = acc.find(item => item.color.id === current.color.id);

    // If not found, push the current product to the accumulator
    if (!found && product.color.id !== current.color.id) {
        acc.push(current);
    }

    return acc;
}, []);

const changedProduct = (id) => {

};

</script>

<template>
    <div class="border :hover:scale-105 border-gray-300 dark:border-gray-700 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <img src="/default-product.jpg" alt="product image" class="w-full h-40 object-cover" />
        <div class="p-4 h-30 flex flex-col justify-between">
            <h2 class="text-lg mb-2 uppercase font-bold">{{ product.name }}</h2>
            <p class="h-12 overflow-hidden text-ellipsis">{{ product.description }}</p>
        </div>
        <div class="flex px-4 justify-between">
            <div>{{ product.size.name }}</div>
            <div class="flex items-center gap-2">
                <div :title="product.color.name" class="w-6 h-6 border-2 border-green-900 rounded-full" :style="'background-color:' + product.color.name"></div>
                <div @click="changedProduct(uniqueProducts.id)" :title="color.name" v-for="color in uniqueProducts" :key="color.id" class="w-6 h-6 border-2 cursor-pointer border-gray-300 rounded-full" :style="'background-color:' + color.color.name" ></div>
            </div>
        </div>
        <div class="p-4 flex justify-between">
            <div class="flex flex-col">
                <span  v-if="product.stock > 0" class="text-sm text-green-600 font-bold">{{ $t('stock') }}: {{ product.stock }}</span>
                <span v-else class="text-sm text-green-600">{{ $t('stock') }}: {{ product.stock }}</span>
                <CartButton class="bg-green-600 text-white hover:bg-green-800 " :product="product" />
            </div>
            <p class="text-green-600 text-end text-2xl font-bold">€{{ product.price }}</p>
        </div>
    </div>
</template>
