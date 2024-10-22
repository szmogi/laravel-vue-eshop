<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import CartStep from '@/Components/CartStep.vue';
import {onMounted, reactive, ref} from "vue";
import { useCartStore } from "@/stores/useCart.js";
const useCar = useCartStore();

import axios from "axios";

defineProps({
    step: Number,
    cartList: Object,
});

const form = reactive({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    shippingMethod: null,
    paymentMethod: null,
    isCompany: false,
    companyName: '',
    vatNumber: '',
});

const shippingRates = ref([]);
const paymentMethods = ref([]);
const subtotal = ref(100);  // Replace with actual subtotal from cart
const shippingCost = ref(0);
const total = ref(0);
const totalInUSD = ref(0);

const validatePhone = () => {
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(form.phone)) {
        alert('Invalid phone number');
    }
};

const calculateShipping = () => {
    if (form.shippingMethod) {
        shippingCost.value = form.shippingMethod.price;
        calculateTotal();
    }
};

const calculateTotal = () => {
    total.value = subtotal.value + shippingCost.value;
    convertToUSD();
};

const convertToUSD = async () => {
    try {
        const response = await axios.get('https://api.exchangerate-api.com/v4/latest/EUR');
        const rate = response.data.rates.USD;
        totalInUSD.value = (total.value * rate).toFixed(2);
    } catch (error) {
        console.error('Error fetching exchange rate:', error);
    }
};

const submitOrder = async () => {
    try {
        const response = await axios.post('/orders', form);
        console.log('Order submitted', response);
    } catch (error) {
        console.error('Order submission failed', error);
    }
};

const setShippingMethod = (rate) => {
    form.shippingMethod = rate;
    calculateShipping();
};

const setPayMethode = (method) => {
    form.paymentMethod = method;
};

onMounted(() => {
    useCar.getShippingRates();
    useCar.getPaymentMethods();
});

</script>

<template>
    <Head title="Checkout" />
    <PageLayout :visible-banner="false">`
    <div id="checkout" class="min-h-screen mt-24 bg-gray-100 dark:bg-gray-900">
         <CartStep class="pt-16" :step="step" />
        <div class="flex flex-col py-12 w-10/12 mx-auto justify-start align-center">
            <h1 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-4xl py-8">
                {{ $t('OrderDetails')}}
            </h1>
               <div class="flex flex-row  justify-center items-center mt-8">
                <form @submit.prevent="submitOrder" class="max-w-xl w-6/12  mx-auto bg-white p-8 rounded-lg shadow-md">
                    <!-- Customer Details -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">{{ $t('name') }}</label>
                        <input
                            v-model="form.name"
                            id="name"
                            type="text"
                            :placeholder="$t('enterName')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">{{ $t('email') }}</label>
                        <input
                            v-model="form.email"
                            id="email"
                            type="email"
                            :placeholder="$t('enterEmail')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 font-bold mb-2">{{ $t('phone') }}</label>
                        <input
                            v-model="form.phone"
                            id="phone"
                            type="tel"
                            :placeholder="$t('enterPhone')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                            @input="validatePhone"
                        />
                    </div>

                    <!-- Shipping Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 font-bold mb-2">{{ $t('address') }}</label>
                        <input
                            v-model="form.address"
                            id="address"
                            type="text"
                            :placeholder="$t('enterAddress')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700 font-bold mb-2">{{ $t('city') }}</label>
                        <input
                            v-model="form.city"
                            id="city"
                            type="text"
                            :placeholder="$t('enterCity')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                        />
                    </div>

                    <!-- Checkbox for Paying as a Company -->
                    <div class="mb-4">
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                v-model="form.isCompany"
                                class="hidden peer"
                            />
                            <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                                <!-- Checkmark (conditionally shown) -->
                                <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span>{{ $t('ifCompany') }}</span>
                        </label>
                    </div>

                    <!-- Billing Information (Visible only if Pay as a Company is checked) -->
                    <div v-if="form.isCompany" class="border-t pt-4">
                        <div class="mb-4">
                            <label for="companyName" class="block text-gray-700 font-bold mb-2">{{ $t('companyName') }}</label>
                            <input
                                v-model="form.companyName"
                                id="companyName"
                                type="text"
                                placeholder="Company Name"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="mb-4">
                            <label for="vatNumber" class="block text-gray-700 font-bold mb-2">{{ $t('vatNumber') }}</label>
                            <input
                                v-model="form.vatNumber"
                                id="vatNumber"
                                type="text"
                                placeholder="VAT Number"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="mb-4">
                        <label for="shipping" class="block text-gray-700 font-bold mb-2">{{ $t('shippingMethod') }}</label>
                        <div @click="setShippingMethod(rate)" v-for="rate in useCar.shippingRates" :key="rate.id" class="flex cursor-pointer items-center mb-2">
                            <input
                                type="radio"
                                v-model="form.shippingMethod"
                                :value="rate"
                                class="hidden peer"
                                @change="calculateShipping"
                            />
                            <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                                <!-- Checkmark (conditionally shown) -->
                                <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span>{{ rate.name }} - {{ rate.price }} EUR</span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label for="payment" class="block text-gray-700 font-bold mb-2">{{ $t('paymentMethod') }}</label>
                        <div @click="setPayMethode(method)" v-for="method in useCar.paymentMethods" :key="method.id" class="flex cursor-pointer items-center mb-2">
                            <input
                                type="radio"
                                v-model="form.paymentMethod"
                                :value="method"
                                class="hidden peer"
                            />
                            <!-- Custom checkbox -->
                            <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                                <!-- Checkmark (conditionally shown) -->
                                <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span>{{ method.name }}</span>
                        </div>
                    </div>
                </form>
                 <div class="flex w-6/12 flex-col">
                     <div class="flex min-h-40  w-full flex-col mx-auto bg-white p-8 rounded-lg shadow-md">
                         <h2 class="text-md font-bold tracking-tight text-ecoBlue-dark sm:text-xl ">
                             {{ $t('OrderSummary')}}
                         </h2>

                     </div>
                    <!-- Order Summary -->
                       <div  class="flex w-full flex-col mx-auto bg-white p-8 rounded-lg shadow-md">
                           <div class="text-end">
                               <p class="text-gray-700 font-bold">{{ $t('subtotal') }}: <span class="text-gray-900">{{ subtotal }} EUR</span></p>
                               <p class="text-gray-700 font-bold">{{ $t('shippingCost') }}: <span class="text-gray-900">{{ shippingCost }} EUR</span></p>
                               <p class="text-gray-700 font-bold">
                                   {{ $t('total') }}: <span class="text-gray-900">{{ total }} EUR</span></p>
                               <p class="text-gray-700 font-bold">{{ $t('totalInUSD') }}: <span class="text-gray-900">{{ totalInUSD }} USD</span></p>
                           </div>

                           <button type="submit" class="mt-6 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                               {{ $t('submitOrder') }}
                           </button>
                       </div>
                   </div>
               </div>
        </div>
    </div>
    </PageLayout>
</template>
