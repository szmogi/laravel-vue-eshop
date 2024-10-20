<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import CartStep from '@/Components/CartStep.vue';

defineProps({
    step: Number,
    cartList: Object,
});

</script>

<template>
    <Head title="Checkout" />
    <PageLayout :visible-banner="false">`
    <div id="checkout" class="min-h-screen mt-24 bg-gray-100 dark:bg-gray-900">
         <h2 class="text-3xl text-center font-bold tracking-tight text-gray-900 sm:text-4xl py-8">
           {{ $t('checkoutTitle')}}
         </h2>
         <CartStep :step="step" />
    </div>
    </PageLayout>
</template>
