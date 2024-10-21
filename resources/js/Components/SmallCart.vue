<template>
   <div @click.prevent="nextCart()" @mouseenter="cartList = true" @mouseleave="cartList = false" class="relative bg-transparent flex -center justify-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather mr-2 feather-shopping-cart">
        <!-- Cart -->
        <circle cx="9" cy="20" r="2"></circle>
        <circle cx="20" cy="20" r="2"></circle>
        <path d="M1 1h4l2 13h13l3-9H6"></path>
        <path d="M1 1h4l2 13h13l3-9H6"></path>
    </svg>
      {{ $t('cart') }}
       <transition enter-from-class="opacity-0" enter-to-class="opacity-100" enter-active-class="transition ease-in duration-300" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
           <span style="color:white; !important;" v-if="useCar.count > 0" class="bg-stone-800 top-[-10px] absolute tex-white text-xs rounded-full px-1.5 py-0.5 mr-5 ">{{ useCar.count }}</span>
       </transition>
       <transition enter-from-class="opacity-0" enter-to-class="opacity-100" enter-active-class="transition ease-in duration-300" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
       <div class="absolute top-0 right-0 pt-7 opacity-100 flex justify-end items-end flex-col bg-transparent rounded text-stone-900 text-xs w-96" v-if="cartList">
           <div v-if="useCar.totalSum > 0" class="flex w-full bg-ecoBlue text-white py-4 px-4 justify-between items-center">
               <span class="text-sm">{{ $t('totalSum') }}</span>
               <span :title="useCar.totalSum.toFixed(2)+' €'" class="text-sm">€{{ useCar.totalSum.toFixed(2) }}</span>
           </div>
           <transition-group v-if="useCar.totalSum > 0" class="flex w-full flex-col bg-white items-center gap-1" tag="ul" enter-from-class="opacity-0" enter-to-class="opacity-100" enter-active-class="transition ease-in duration-300" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
               <li class="flex justify-between px-2 hover:bg-stone-100 w-full border-b last:border-b-0 py-4 items-center" v-for="product in useCar.cart" :key="product.product_id" >
                   <span class="text-xs">{{ product.quantity }} x</span>
                   <span class="text-xs">{{ product.name }}</span>
                   <span class="text-xs">€{{ product.price * product.quantity }}</span>
                   <button @click.prevent="useCar.removeCart(product)" class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded-md text-sm font-medium">
                       {{ $t('remove') }}
                   </button>
               </li>
           </transition-group>
           <div v-if="useCar.totalSum > 0" class="flex w-full opacity-100 bg-ecoBlue hover:bg-ecoBlue-dark uppercase py-4 px-4 justify-center items-end">
               <Link :href="route('cart.page')" class="text-sm w-full text-white text-center  font-medium">
                   {{ $t('pay') }}
               </Link>
           </div>
           <div v-if="useCar.totalSum === 0" class="flex w-48 rounded bg-white text-stone-700 uppercase py-4 px-4 justify-center items-end">
               {{ $t('cartEmpty') }}
           </div>
        </div>
       </transition>
   </div>
</template>

<script setup>
import { useCartStore } from "@/stores/useCart.js";
import {Link, router} from '@inertiajs/vue3';
import { ref } from 'vue';
const useCar = useCartStore();
const cartList = ref(false);
useCar.initCart();

const nextCart = () => {
  console.log('next');
  router.get(route('cart.page'));
};

</script>
