<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head title="Products" />
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed bg-ecoGreen w-full sm:top-0 sm:end-0 p-6 text-end z-10 flex justify-between align-center">
            <div class="flex text-3xl justify-start text-ecoGray-light items-center gap-3">
                Eshopka
            </div>
            <div class="flex items-center gap-3">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold hover:underline text-ecoGray-light hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white ">Objednávky</Link>
                <template v-else>
                    <Link :href="route('cart')" class="font-semibold hover:underline text-ecoGray-light hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white">Kosik</Link>
                    <Link :href="route('login')" class="font-semibold text-ecoGray-light hover:underline hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white">Log in</Link>
                        <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold hover:underline text-ecoGray-light hover:text-ecoGray-dark dark:text-gray-400 dark:hover:text-white ">Register</Link>
                </template>
            </div>
        </div>
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
        </div>
    </div>
</template>
