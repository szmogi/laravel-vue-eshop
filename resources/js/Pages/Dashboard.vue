<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import OrderaTable from '@/Pages/Orders/OrderTable.vue';
import OrderDetails from '@/Pages/Orders/OrderDetails.vue';

const props = defineProps({
    orders: Object,
    tableOrders: Boolean,
    orderDetails: Boolean,
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 v-if="props.tableOrders" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('orders') }}
            </h2>
            <h2 v-if="props.orderDetails" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('orderDetails') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <OrderaTable v-if="props.tableOrders" :orders="props.orders" />
                    <OrderDetails v-if="props.orderDetails" :orders="props.orders" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
