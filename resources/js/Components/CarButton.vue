<template>
    <button @click="useCar.addToCart(product)" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-md text-sm font-medium">
        {{ $t('AddToCart') }}
    </button>
</template>

<script setup>
import {useCartStore} from "@/stores/useCart.js";
const useCar = useCartStore();



defineProps({
    product: Object,
});

</script>
