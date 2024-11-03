<script setup>
import { Head , Link} from '@inertiajs/vue3';
import { ref , watch, onMounted} from 'vue';
import { useOrderStore } from "@/stores/useOrder.js";

const props = defineProps({
    orders: Object,
});

const useOrder = useOrderStore();
const orders = ref(props.orders);

console.log(props.orders);


onMounted(() => {
    useOrder.getStatusShow();
    useOrder.setOrder(props.orders);
});

</script>

<template>
    <Head title="Orders" />
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl mb-4 font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $t('order') }}
                </h2>
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $t('orderId') }}: {{ orders.id }}</span>
            </div>
            <div class="flex flex-row gap-4 w-8/12 mx-auto py-12 sm:px-6 lg:px-8">
                <div class="bg-white w-6/12 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <div class="flex flex-col justify-between items-start">
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('name') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.name }}</div>
                        </div>
                        <div v-if="orders.data.companyName" class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('companyName') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.companyName }}</div>
                        </div>
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('email') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.email }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-between items-start">
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('phone') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.phone }}</div>
                        </div>
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('address') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.address }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-between items-start">
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('city') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.city }}</div>
                        </div>
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('zipCode') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.zipCode }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white w-6/12 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <div class="flex flex-col justify-between items-start">
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('currency') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.currencyType ? orders.data.currencyType.name : 'EUR' }}</div>
                        </div>
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('vatNumber') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.vatNumber }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-between items-start">
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('shipping') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.shippingMethod.name }}</div>
                        </div>
                        <div class="flex flex-row align-center items-center gap-2">
                            <div class="text-lg pb-1 font-bold text-gray-900 dark:text-gray-100">{{ $t('payment') }}:</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ orders.data.paymentMethod.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
