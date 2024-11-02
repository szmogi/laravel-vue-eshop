<script setup>
import { Head , Link} from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import { ref , onMounted} from 'vue';
import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();
import DataView from 'primevue/dataview';
import Tag from 'primevue/tag';
import Button from '@/Components/Button.vue';
import { useCartStore } from "@/stores/useCart.js";
const useCar = useCartStore();

import CartStep from '@/Components/CartStep.vue';

const props = defineProps({
    cartList: Object,
    step: Number,
});

const quantity = ref(0);

const getSeverity = (item) => {
    if(item.inventoryStatus === false) {
        return 'danger';
    }
    return 'success';
};

const setQuantity = (id, quantity) => {
    useCar.setQuantity(id, quantity);
};

</script>

<template>
    <Head title="Cart" />

    <PageLayout :visible-banner="false">
        <div id="cart" class="min-h-screen mt-24 bg-gray-100 dark:bg-gray-900">
             <CartStep class="pt-16" :step="step" />
            <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center">
                <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-4xl py-8">
                    {{ $t('cartList') }}
                </h1>
                <DataView :value="useCar.cartArray">
                    <template #list="slotProps">
                        <div class="flex flex-col">
                            <div v-for="(item, index) in slotProps.items" :key="index">
                                <div class="flex flex-col relative sm:flex-row sm:items-center p-6 gap-4" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                                    <div class="md:w-40 object-fill relative">
                                        <img class="block xl:block mx-auto rounded w-full" src="/default-product.jpg" :alt="item.name" />
                                        <div class="absolute bg-black/70 rounded-border" style="left: 4px; top: 4px">
                                            <Tag :value="item.inventoryStatus ? $t('stock') : $t('maxStock')" :severity="getSeverity(item)"></Tag>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:flex-row justify-between md:items-center flex-1 gap-6">
                                        <div class="flex flex-row md:flex-col justify-between items-start gap-3">
                                            <div class="min-w-40">
                                                <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ item.category }}</span>
                                                <div class="text-lg text-eco font-medium mt-2">{{ item.name }}</div>
                                            </div>
                                        </div>

                                        <div v-if="item.inventoryStatus" class="flex flex-col md:items-end gap-8">
                                            <div class="flex flex-row-reverse md:flex-row gap-2">
                                            <button @click="setQuantity(item.product_id, item.quantity - 1)" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                                -
                                            </button>
                                               <input :value="item.quantity" type="number" class="bg-white dark:bg-gray-800 text-center py-2 px-1 w-9 rounded-md text-sm font-medium" placeholder="0">
                                            <button @click="setQuantity(item.product_id, item.quantity + 1)" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                                +
                                            </button>
                                            </div>
                                        </div>

                                        <div class="flex flex-col md:items-end gap-8">
                                            <span class="text-xl font-semibold">€{{ item.price }}</span>
                                            <div class="flex flex-row-reverse md:flex-row gap-2 absolute top-2 right-2">
                                                 <Button class="text-sm py-1 px-1" type="danger">
                                                     X
                                                 </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </DataView>

                <div class="flex bg-white mt-8 px-8 flex-col py-8 justify-end items-end gap-3">
                    <div class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-3xl">
                        {{ $t('totalSum') }}: {{ useCar.totalSum }} €
                    </div>
                    <div class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl">
                        {{ $t('noVat') }}: {{ useCar.noVat }} €
                    </div>
                </div>
                <div class="flex mt-4 justify-between items-center gap-3">
                    <Link class="w-2/12 bg-ecoGreen-light text-white rounded flex justify-center items-center hover:bg-ecoGreen text-md uppercase h-12" :href="route('home')">
                        {{ $t('backButton') }}
                    </Link>
                    <Link class="w-4/12" :href="route('cart.checkout')" >
                        <Button class="bg-ecoBlue w-full font-medium text-3xl text-white hover:bg-ecoBlue-dark rounded-md py-4 mx-1 px-4">
                            {{ $t('buyNow') }}
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

</style>
