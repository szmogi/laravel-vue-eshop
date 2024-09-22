<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { useField } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});
const page = usePage();
const form = useForm({
    initialValues: {
        name: props.product.name,
        description: props.product.description,
        price: props.product.price,
        category_id: props.product.category_id,
        sku: props.product.sku,
        size_id: props.product.size_id,
        color_id: props.product.color_id,
    },
});
const name = useField('name');
const description = useField('description');
const price = useField('price');
const category_id = useField('category_id');
const sku = useField('sku');
const size_id = useField('size_id');
const color_id = useField('color_id');
</script>

<template>
    <Head title="Product" />
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
            <form @submit.prevent="form.post(route('products.store'))">
                <input type="hidden" name="product_id" :value="product.id">
                <fieldset class="mt-8">
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Názov produktu</label>
                            <div class="mt-1">
                                <input id="name" type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" name="name" v-model="name">
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Popis produktu</label>
                            <div class="mt-1">
                                <textarea id="description" rows="3" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" name="description" v-model="description"></textarea>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Cena produktu</label>
                            <div class="mt-1">
                                <input id="price" type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" name="price" v-model="price">
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kategória</label>
                            <div class="mt-1">
                                <select id="category_id" name="category_id" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" v-model="category_id">
                                    <option value="">Vyberte kategóriu</option>
                                    <option v-for="category in $page.props.auth.user.allPermissions.categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Jedinečný identifikátor produktu</label>
                            <div class="mt-1">
                                <input id="sku" type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" name="sku" v-model="sku">
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="size_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Veľkosť</label>
                            <div class="mt-1">
                                <select id="size_id" name="size_id" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" v-model="size_id">
                                    <option value="">Vyberte veľkosť</option>
                                    <option v-for="size in $page.props.auth.user.allPermissions.sizes" :key="size.id" :value="size.id">{{ size.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="color_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Farba</label>
                            <div class="mt-1">
                                <select id="color_id" name="color_id" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" v-model="color_id">
                                    <option value="">Vyberte farbu</option>
                                    <option v-for="color in $page.props.auth.user.allPermissions.colors" :key="color.id" :value="color.id">{{ color.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-ecoGreen text-white px-4 py-2 rounded-md text-sm font-medium">Uložiť</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
