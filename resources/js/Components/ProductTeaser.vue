<script setup>
import { usePage } from '@inertiajs/vue3';
import CartButton from '@/Components/CarButton.vue';
import { ref , onBeforeMount , computed} from 'vue';
import { useCartStore } from "@/stores/useCart.js";

const page = usePage();
const useCar = useCartStore();

const props = defineProps({
    product: Array | Object,
});

const product = ref();
const variations = ref();
const uniqueProducts = ref();
const quantity = ref(1);
const maxQuantity = ref(false);

onBeforeMount(() => {
    product.value = props.product;
    variations.value = product.value.variants.filter(variant => variant.size_id === product.value.size_id);

    if(variations.value.length > 0) {
        uniqueProducts.value = variations.value.reduce((acc, current) => {
            // Check if the current color already exists in the accumulator
            const found = acc.find(item => item.color.id === current.color.id);
            // If not found, push the current product to the accumulator
            if (!found && product.value.color.id !== current.color.id) {
                acc.push(current);
            }
            return acc;
        }, []);
    }
});

const changedProduct = (uniqueProduct) => {
    uniqueProducts.value = uniqueProducts.value.filter(product => product.id !== uniqueProduct.id);
    uniqueProducts.value.push(product.value);
    product.value = uniqueProduct;
};

const price = computed(() => {

    if(quantity.value === 0 ||
        quantity.value === undefined ||
        quantity.value === null ||
        !quantity.value) {
        quantity.value = 1;
        return product.value.price;
    }

    let sum = product.value.price;
    if(product.value.stock >= quantity.value) {
        sum = product.value.price * quantity.value;
        sum = sum.toFixed(2);
        if(product.value.stock > quantity.value) {
            maxQuantity.value = false;
        }
    } else {
        maxQuantity.value = true;
        quantity.value = product.value.stock;

        setTimeout(() => {
            maxQuantity.value = false;
        }, 2000);
    }

    if(sum) {
       return sum;
    }
});

</script>
<template>
    <div class="border w-full rounded hover:border-green-800 hover:transform hover:scale-105 duration-100 border-gray-300 hover:shadow-green-800  selection:bg-red-500 selection:text-white">
        <img src="/default-product.jpg" alt="product image" class="w-full h-40 object-cover" />
        <div class="p-4 h-30 flex flex-col justify-between">
            <h2 class="text-lg mb-2 uppercase font-bold">{{ product.name }}</h2>
            <p class="h-12 overflow-hidden text-ellipsis">{{ product.description }}</p>
        </div>
        <div class="flex px-4 justify-between">
            <div>{{ product.size.name }}</div>
            <div class="flex items-center gap-2">
                <div :title="product.color.name" class="w-6 h-6 border-2 border-green-900 rounded-full" :style="'background-color:' + product.color.name"></div>
                <div @click="changedProduct(uniqueProduct)" :title="uniqueProduct.name" v-for="uniqueProduct in uniqueProducts" :key="uniqueProduct.id" class="w-6 h-6 border-2 cursor-pointer border-gray-300 rounded-full" :style="'background-color:' + uniqueProduct.color.name" ></div>
            </div>
        </div>
        <div class="p-4 flex justify-between items-center align-center">
            <div class="flex flex-col">
                <span  v-if="product.stock > 0" class="text-sm text-green-600 font-bold">{{ $t('stock') }}: {{ product.stock }}</span>
                <span v-else class="text-sm text-green-600">{{ $t('stock') }}: {{ product.stock }}</span>
            </div>
            <p class="text-green-600 text-end text-2xl font-bold">€{{ price }}</p>
        </div>
        <transition enter-from-class="opacity-0" enter-to-class="opacity-100" enter-active-class="transition ease-in duration-300" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="maxQuantity" class="px-4 w-full mb-4 flex justify-center items-center align-center">
                    <div class="text-sm text-center text-red-600 font-bold">{{ $t('maxStock') }}: {{ product.stock }}</div>
            </div>
        </transition>
        <div class="px-4 w-full mb-4 flex justify-between items-center align-center">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ $t('quantity') }}</label>
            <div class="flex justify-end items-center gap-1">
                <button @click="quantity--" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                    -
                </button>
                <input v-model="quantity" type="number" class="bg-gray-200 dark:bg-gray-800 text-end py-2 px-1 w-9 rounded-md text-sm font-medium" placeholder="0">
                <button @click="quantity++" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                   +
                </button>
            </div>
        </div>
        <div class="px-4 w-full mb-4 flex justify-between">
            <CartButton @click="useCar.addToCart(product, quantity)" class="bg-green-600 text-white hover:bg-green-800 w-full " :product="product" />
        </div>
    </div>
</template>


<style scoped>
.border {
    @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 selection:bg-red-500 selection:text-white;
}
</style>
