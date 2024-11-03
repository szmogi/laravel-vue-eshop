<script setup>
import { Head , Link} from '@inertiajs/vue3';
import { ref , watch, onMounted} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import Select from 'primevue/select';
import { useOrderStore } from "@/stores/useOrder.js";

const props = defineProps({
    orders: Object,
});

const useOrder = useOrderStore();
const searchOrderId = ref('');
const searchTotal = ref('');
const orders = ref(props.orders);
const searchStatus = ref('');

onMounted(() => {
    useOrder.getStatusShow();
    useOrder.setOrder(props.orders);
    useOrder.getPreprocessOrder();
});

watch(() => searchOrderId.value, () => {
    if(searchOrderId.value.length > 0) {
        orders.value = props.orders.filter(order => String(order.id).includes(searchOrderId.value));
    } else {
        orders.value = props.orders;
    }
    useOrder.setOrder(orders.value);
    useOrder.getPreprocessOrder();
});

watch(() => searchTotal.value, () => {
    if(searchTotal.value.length > 0) {
        orders.value = props.orders.filter(order => String(order.total).includes(searchTotal.value));
    } else {
        orders.value = props.orders;
    }

    useOrder.setOrder(orders.value);
    useOrder.getPreprocessOrder();
});

watch(() => searchStatus.value, () => {
    if(searchStatus.value !== null && searchStatus.value.length > 0) {
        let status = searchStatus.value.toLowerCase();
        orders.value = props.orders.filter(order => String(order.status).includes(status));
    } else {
        orders.value = props.orders;
    }
    useOrder.setOrder(orders.value);
    useOrder.getPreprocessOrder();
});

</script>

<template>
    <Head title="Orders" />
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl mb-4 font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $t('orders') }}
                </h2>
                <DataTable :value="orders" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-row justify-end mb-4 items-center">
                            <IconField class="mx-1">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="searchOrderId" :placeholder=" $t('searchId')" />
                            </IconField>
                            <IconField class="mx-1">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="searchTotal" :placeholder=" $t('searchTotal')" />
                            </IconField>
                            <Select v-if="useOrder.statusShow.length > 0"
                                    v-model="searchStatus"
                                    :options="useOrder.statusShow"
                                    optionLabel="label"
                                    optionValue="value"
                                    class="w-3/12 mx-1"
                                    :placeholder="$t('status')">
                            </Select>
                        </div>
                        <div class="flex flex-row justify-end items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $t('results') }}: {{ useOrder.totalCountOrder }}</span>
                        </div>
                    </template>
                    <Column field="id" :header="$t('orderId')" sortable style="width: 25%"></Column>
                    <Column field="status" :header="$t('status')" sortable style="width: 25%"></Column>
                    <Column field="total" :header="$t('total')" sortable style="width: 25%">
                        <template #body="slotProps">
                            {{ slotProps.data.total }}
                            <span class="text-gray-500">€</span>
                        </template>
                    </Column>
                    <Column field="created_at" :header="$t('createdAt')" sortable style="width: 25%"></Column>
                    <Column class="w-24 !text-end">
                        <template #body="{ data }">
                            <Link :href="route('order.show', data.id)" class="text-sm text-ecoBlue hover:bg-ecoBlue-light hover:text-white rounded-md py-1 px-2">
                                {{ $t('view') }}
                            </Link>
                        </template>
                    </Column>
                    <template #footer>
                        <div class="flex flex-row justify-end items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $t('allTotalSum') }}: {{ useOrder.totalSumOrder }} €</span>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>
