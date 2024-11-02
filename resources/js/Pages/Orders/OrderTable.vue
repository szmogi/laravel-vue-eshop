<script setup>
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import
defineProps({
    orders: Object,
});
</script>

<template>
    <Head title="Orders" />
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $t('orders') }}
                </h2>
                <DataTable :value="orders" tableStyle="min-width: 50rem">
                    <Column field="id" header="Id" sortable style="width: 25%"></Column>
                    <Column field="status" header="Status" sortable style="width: 25%"></Column>
                    <Column field="total" header="Total" sortable style="width: 25%">
                        <template #body="slotProps">
                            {{ slotProps.data.total }}
                            <span class="text-gray-500">€</span>
                        </template>
                    </Column>
                    <Column field="created_at" header="Created at" sortable style="width: 25%"></Column>
                </DataTable>

            </div>
        </div>
    </div>
</template>
