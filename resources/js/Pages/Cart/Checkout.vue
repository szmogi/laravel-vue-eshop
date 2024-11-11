<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import CartStep from '@/Components/CartStep.vue';
import {onMounted, reactive, ref , watch} from "vue";
import { router } from '@inertiajs/vue3';

import { useCartStore } from "@/stores/useCart.js";
const useCar = useCartStore();
useCar.getVillages();

import { useOrderStore } from "@/stores/useOrder.js";
const useOrder = useOrderStore();

import axios from "axios";
import Button from "@/Components/Button.vue";
import SmallCart from "@/Components/SmallCart.vue";
defineProps({
    step: Number,
    cartList: Object,
});

const form = reactive({
    name: 'tester fester',
    email: 'tester@fester.cz',
    phone: '+420777777777',
    address: 'na rozcestí',
    city: 'brno',
    shippingMethod: null,
    paymentMethod: null,
    isCompany: false,
    companyName: '',
    vatNumber: '',
    zipCode: '93201',
    currencyType: null,
});

const shippingRates = ref([]);
const paymentMethods = ref([]);
const shippingCost = ref(0);
const total = ref(0);
const totalInUSD = ref(0);
const totalInCzk = ref(0);
const image = ref('/default-product.jpg');
const products = ref({});
const valPhone = ref(true);


const validatePhone = () => {
    // Regex for validating different phone formats
    const phoneRegex = /^(?:\+?\d{1,3}[- ]?)?(?:\(?\d{3}\)?[- ]?)?\d{3}[- ]?\d{4}$/;
    if (!phoneRegex.test(form.phone)) {
        valPhone.value = false;
    } else {
        valPhone.value = true;
    }
};

const calculateShipping = () => {
    if (form.shippingMethod) {
        shippingCost.value = form.shippingMethod.price;
        calculateTotal();
        convertTocalCurrency();
    }
};

const calculateTotal = () => {
    total.value = useCar.totalSum + shippingCost.value;
    convertTocalCurrency();
};

const convertTocalCurrency = async () => {
    try {
        let response = JSON.parse(sessionStorage.getItem('rate'));
        if(!response) {
            response = await axios.get(`/api/proxy/exchangerate`);
            sessionStorage.setItem('rate', JSON.stringify(response.data));
            response = response.data;
        }
        const rateUSD = response.rates.USD;
        const rateCzk = response.rates.CZK;
        totalInUSD.value = (total.value * rateUSD).toFixed(2);
        totalInCzk.value = (total.value * rateCzk).toFixed(2);
    } catch (error) {
        console.error('Error fetching exchange rate:', error);
    }
};

const submitOrder = async () => {
    if(valPhone.value) {
      form.totalInUSD = totalInUSD.value;
      form.totalInCzk = totalInCzk.value;
      form.total = total.value;
      form.orderItems = useCar.cart;
      form.noVat = useCar.noVat;
      useOrder.createOrder(form)
   } else {
        console.log('Invalid phone number');
        valPhone.value = true;
    }
};

const setShippingMethod = (rate) => {
    form.shippingMethod = rate;
    calculateShipping();
};

const setPayMethode = (method) => {
    form.paymentMethod = method;
};

const calculateCurrency = (name) => {
    if(name === 'EUR') {
        name = 'EUR';
    } else if(name === 'CZK') {
        name = 'CZK';
    } else if(name === 'USD') {
        name = 'USD';
    }

    useCar.currencyType.forEach(currency => {
        if(currency.name === name) {
            currency.active = true;
        } else {
            currency.active = false;
        }
    });
    convertTocalCurrency();
};

onMounted(() => {
    useCar.getShippingRates();
    useCar.getPaymentMethods();
});

watch(() => useCar.cart, () => {
    products.value = useCar.cart;
    total.value = useCar.totalSum;
    convertTocalCurrency();
    if(shippingCost.value > 0) {
        total.value = useCar.totalSum + shippingCost.value;
    }
});

watch(() => useOrder.orderId, () => {
    if(useOrder.orderId > 0) {
        sessionStorage.setItem('order_id_complete', useOrder.orderId);
        router.get(route('cart.complete'));
    }
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
               <div class="flex flex-row justify-between items-center mt-8">
                <form @submit.prevent="submitOrder" class="w-full">
                    <!-- Customer Details -->
                  <div class="flex flex-row justify-start items-start mb-8" >
                   <div class="w-6/12 mr-4 bg-white p-8 rounded-lg shadow-md">
                    <div class="flex flex-row justify-between items-center py-4 mb-8 border-b border-gray-200 dark:border-gray-700">
                        <div v-if="!$page.props.auth.user">
                            <Link :href="route('login')" class="font-semibold text-ecoBlue hover:underline hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white">{{ $t('login') }}</Link>
                            <Link v-if="!$page.props.auth.user" :href="route('register')" class="ms-6 font-semibold hover:underline text-ecoBlue hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white ">{{ $t('register') }}</Link>
                        </div>
                        <div v-else>
                            <p class="font-semibold text-ecoBlue hover:underline hover:text-ecoBlue-dark dark:text-gray-400 dark:hover:text-white">{{ $page.props.auth.user.name }}</p>
                        </div>
                    </div>
                   <div v-if="!$page.props.auth.user">
                       <p class="font-semibold text-yellow-500 dark:text-gray-400 dark:hover:text-white">{{ $t('notRegisteredCart') }}</p>
                   </div>

                   <!-- Shipping Method -->
                   <div class="mb-4 mt-4">
                       <label for="shipping" class="block text-gray-700 font-bold mb-2">{{ $t('currencyType') }}</label>
                       <div @click="calculateCurrency(currency.name)" v-for="currency in useCar.currencyType" :key="currency.id" class="flex cursor-pointer items-center mb-2">
                           <input
                               type="radio"
                               v-model="form.currencyType"
                               :value="currency.name"
                               class="hidden peer"
                               @change="calculateCurrency(currency.name)"
                               :checked="currency.active"
                               required
                               name="currencyType"
                           />
                           <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                               <!-- Checkmark (conditionally shown) -->
                               <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                   <path d="M5 13l4 4L19 7"/>
                               </svg>
                           </div>
                           <span>{{currency.name}}</span>
                       </div>
                   </div>

                    <div class="mb-4 mt-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">{{ $t('name') }}</label>
                        <input
                            v-model="form.name"
                            id="name"
                            type="text"
                            :placeholder="$t('enterName')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            required
                            name="name"
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
                            name="email"
                        />
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="flex w-full justify-between text-gray-700 font-bold mb-2">{{ $t('phone') }}
                        <span v-if="!valPhone" class="text-red-500 font-bold">{{ $t('invalidPhone') }}</span>
                        </label>
                        <input
                            v-model="form.phone"
                            id="phone"
                            type="tel"
                            :placeholder="$t('enterPhone')"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                            :class="{ 'border-red-500': !valPhone }"
                            required
                            @blur="validatePhone(form.phone)"
                            name="phone"
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
                            name="address"
                        />
                    </div>
                   <!-- Shipping Address -->
                   <div class="mb-4">
                       <label for="address" class="block text-gray-700 font-bold mb-2">{{ $t('zipCode') }}</label>
                       <input
                           v-model="form.zipCode"
                           id="address"
                           type="text"
                           :placeholder="$t('enterZipCode')"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ecoBlue"
                           required
                           @input="useCar.searchVillage(form.zipCode)"
                           name="zipCode"
                       />
                       <div v-if="useCar.villageResult.length > 0" class="flex flex-row w-full mt-1 justify-start flex-wrap  items-center">
                           <div v-for="village in useCar.villageResult" :key="village.id" class="flex flex-row py-1 items-center">
                               <label @click="form.city = village.fullname; form.zipCode = village.zip; " class="mr-2 uppercase cursor-pointer hover:bg-ecoBlue w-full text-sm bg-ecoBlue-light p-1 rounded text-white" :for="village.fullname">{{ village.fullname }}</label>
                           </div>
                       </div>
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
                            name="city"
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
                                required
                                name="companyName"
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
                                required
                                name="vatNumber"
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
                                required
                                name="shippingMethod"
                            />
                            <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                                <!-- Checkmark (conditionally shown) -->
                                <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="flex flex-row items-center">
                                <img class="w-6 h-6 mr-2" v-if="rate.image" :src="rate.imagePath" alt="rate.name" width="25" />
                                {{ rate.name }} - {{ rate.price }} EUR</span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label for="payment" class="block text-gray-700 font-bold mb-2">{{ $t('paymentMethod') }}</label>
                        <div v-for="method in useCar.paymentMethods" :key="method.id" >
                           <div @click="setPayMethode(method)" class="flex cursor-pointer items-center mb-2" >
                               <input
                                   type="radio"
                                   v-model="form.paymentMethod"
                                   :value="method"
                                   class="hidden peer"
                                   name="paymentMethod"
                               />
                               <!-- Custom checkbox -->
                               <div class="w-4 mr-2 mb-0.5 h-4 border border-stone-400 rounded-md peer-checked:bg-ecoBlue peer-checked:border-ecoBlue flex items-center justify-center transition duration-200">
                                   <!-- Checkmark (conditionally shown) -->
                                   <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                       <path d="M5 13l4 4L19 7"/>
                                   </svg>
                               </div>
                               <span class="flex flex-row items-center">
                                <img class="w-6 h-6 mr-2" v-if="method.image" :src="method.imagePath" alt="method.name" width="25" />
                                {{ method.name }}</span>
                           </div>
                            <div v-if="method.active" class="text-sm mt-1.5 text-gray-600 dark:text-gray-400">
                                {{ method.description }}
                            </div>
                        </div>
                       </div>
                      </div>
                       <div class="flex min-h-40 w-6/12 flex-col bg-white p-8 rounded-lg shadow-md">
                           <h2 class="text-md font-bold tracking-tight text-ecoBlue-dark sm:text-xl ">
                               {{ $t('OrderSummary')}}
                           </h2>
                           <nav v-if="Object.keys(products).length > 0" class="flex flex-col gap-2">
                               <li v-for="item in products" :key="item.id" class="flex py-2 items-center justify-between">
                                   <div class="flex items-center">
                                       <img :src="image" class="w-10 h-10 rounded-full object-cover mr-4" alt="">
                                       <div class="flex items-center flex-row">
                                           <span class="text-sm font-bold mr-3">{{ item.quantity }}×</span>
                                           <span class="text-sm font-bold">{{ item.name }}</span>
                                       </div>
                                   </div>
                                   <div class="flex items-center ">
                                       <span class="text-xs text-gray-500">{{ item.price }}</span>
                                   </div>
                                   <div class="flex items-center">
                                       <span class="text-sm font-bold">{{ item.price }} €</span>
                                   </div>
                               </li>
                           </nav>
                       </div>
                   </div>
                    <div class="flex h-fit w-full flex-col justify-between">
                        <!-- Order Summary -->
                        <div  class="flex w-full flex-col mx-auto bg-white p-8 rounded-lg shadow-md">
                            <div class="text-end">
                                <p class="text-gray-700 font-bold">{{ $t('subtotal') }}: <span class="text-gray-900">{{ useCar.totalSum }} EUR</span></p>
                                <p class="text-gray-700 font-bold">{{ $t('shippingCost') }}: <span class="text-gray-900">{{ shippingCost }} EUR</span></p>
                                <p v-if="useCar.currencyType[0].active" class="text-gray-700 font-bold">
                                    {{ $t('total') }}: <span class="text-gray-900">{{ total }} EUR</span></p>
                                <p v-if="useCar.currencyType[0].active" class="text-gray-700 font-bold">
                                    {{ $t('noVat') }}: <span class="text-gray-900">{{ useCar.noVat }} EUR</span></p>
                                <p v-if="useCar.currencyType[1].active" class="text-gray-700 font-bold">{{ $t('totalInCzk') }}: <span class="text-gray-900">{{ totalInCzk }} CZK</span></p>
                                <p v-if="useCar.currencyType[2].active" class="text-gray-700 font-bold">{{ $t('totalInUSD') }}: <span class="text-gray-900">{{ totalInUSD }} USD</span></p>
                            </div>
                            <div class="flex mt-6 w-full justify-between items-center flex-row">
                                <Link class="w-2/12 bg-ecoGreen-light text-white rounded flex justify-center items-center hover:bg-ecoGreen text-md uppercase h-12" :href="route('cart.page')">
                                    {{ $t('backButton') }}
                                </Link>
                                <Button type="button" class=" w-6/12 text-xl uppercase h-16">
                                    {{ $t('submitOrder') }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </form>
               </div>
        </div>
    </div>
    </PageLayout>
</template>
