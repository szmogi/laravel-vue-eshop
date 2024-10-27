
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import CartStep from '@/Components/CartStep.vue';
import { onMounted, reactive, ref , watch} from "vue";
import { useOrderStore } from "@/stores/useOrder.js";
const useOrder = useOrderStore();

const orderId = ref(sessionStorage.getItem('order_id_complete'));

defineProps({
    step: Number,
    cartList: Object,
});

onMounted(() => {
  if(orderId.value) {
    useOrder.getOrder(orderId.value);
  }
});


</script>

<template>
    <Head title="Complete" />
    <PageLayout :visible-banner="false">
    <div id="checkout" class="min-h-screen mt-24 mb-24 bg-gray-100 dark:bg-gray-900">
        <CartStep class="pt-16" :step="step" />
        <h2 class="text-3xl text-center font-bold tracking-tight text-ecoBlue-dark sm:text-4xl py-8">
            {{ $t('completeTitle')}}
        </h2>
        <div v-if="useOrder.orderId > 0" class="flex flex-row justify-center items-center mt-8">
            <div class="w-6/12 mx-auto p-8 bg-white rounded-lg shadow-md">
                <div class="flex flex-col gap-4">
                    <h3 class="text-3xl font-bold text-center text-ecoBlue-dark">{{ $t('OrderSummary')}}</h3>
                    <div v-if="useOrder.order.date" class="text-sm text-center  text-ecoBlue dark:text-gray-400"> {{ useOrder.order.date }}</div>
                    <div class="flex flex-row border-b-2 py-4 justify-between items-start">
                        <div class="flex flex-row gap-2">
                            <div class="flex flex-col">

                                <div v-if="useOrder.order.name" class="text-lg pb-1 font-bold text-ecoBlue-dark dark:text-gray-100">{{ useOrder.order.name }}</div>
                                <div v-if="useOrder.order.companyName" class="text-sm text-ecoBlue dark:text-gray-400"><span class="font-semibold">{{ $t('companyName') }}:</span> {{ useOrder.order.companyName }}</div>
                                <div v-if="useOrder.order.email" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('email') }}:</span> {{ useOrder.order.email }}</div>
                                <div v-if="useOrder.order.phone" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('phone') }}:</span> {{ useOrder.order.phone }}</div>
                                <div v-if="useOrder.order.address" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('address') }}:</span> {{ useOrder.order.address }}</div>
                                <div v-if="useOrder.order.city" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('city') }}:</span> {{ useOrder.order.city }}</div>
                                <div v-if="useOrder.order.zipCode" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('zipCode') }}:</span> {{ useOrder.order.zipCode }}</div>
                                <div v-if="useOrder.order.vatNumber" class="text-sm text-ecoBlue dark:text-gray-400"> <span class="font-semibold">{{ $t('vatNumber') }}:</span> {{ useOrder.order.vatNumber }}</div>
                            </div>

                        </div>
                        <div class="text-sm text-ecoBlue dark:text-gray-400">{{ $t('orderId') }}: {{ useOrder.orderId }}</div>
                    </div>
                    <div class="mt-4 w-full ">
                        <h3 class="text-xl text-center text-ecoBlue-dark">{{ $t('itemsList')}}</h3>
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex w-full flex-col gap-2">
                                <div v-for="item in useOrder.order.orderItems" :key="item.id" class="flex w-full border-b last:border-b-0 flex-row justify-between items-center">
                                    <div class="flex py-4  w-full items-center justify-between flex-row gap-2">
                                        <div class="flex flex-row items-center gap-2">
                                            <div class="text-sm text-ecoBlue dark:text-gray-400">{{ item.quantity }} x</div>
                                            <div class="text-lg font-bold text-ecoBlue-dark dark:text-gray-100">{{ item.name }}</div>
                                        </div>
                                        <div class="text-sm text-ecoBlue dark:text-gray-400">{{ item.price }} €</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row border-t-2 py-4 justify-between items-center">
                        <div class="flex flex-row w-full gap-2">
                            <div class="flex flex-col w-full justify-end items-end">
                                <div class="text-lg text-ecoBlue-dark py-1 dark:text-gray-100">{{ $t('subtotal') }} : {{ useOrder.order.total - useOrder.order.shippingMethod.price }} €</div>
                                <div class="text-sm text-ecoBlue py-1 dark:text-gray-400">{{ $t('shippingCost') }}: {{ useOrder.order.shippingMethod.price }} €</div>
                                <div v-if="useOrder.order.currencyType === null || useOrder.order.currencyType === 'EUR'" class="text-2xl py-1 font-bold text-ecoBlue-dark dark:text-gray-100">{{ $t('total') }}: {{ useOrder.order.total }} €</div>
                                <div v-if="useOrder.order.currencyType === null || useOrder.order.currencyType === 'EUR'" class="text-md  text-ecoBlue-dark dark:text-gray-100">{{ $t('noVat') }}: {{ useOrder.noVat }} €</div>
                                <div v-if="useOrder.order.currencyType === 'CZK'" class="text-2xl py-1 font-bold text-ecoBlue-dark dark:text-gray-100">{{ $t('total') }}: {{ useOrder.order.total }} Kč</div>
                                <div v-if="useOrder.order.currencyType === 'USD'" class="text-2xl py-1 font-bold text-ecoBlue-dark dark:text-gray-100">{{ $t('total') }}: {{ useOrder.order.total }} $</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </PageLayout>
</template>
