<template>
    <nav>
        <ul class="flex w-8/12 justify-between text-ecoBlue-dark items-center mx-auto">
            <li :class="{ active: step === 1 }"><Link :href="route('cart.page')">
                <span :class="step >= 1 ? 'bg-ecoBlue-dark' : 'bg-ecoBlue'" class=" text-2xl rounded-full flex justify-center items-center text-white
                 w-12 h-12">1</span></Link></li>
            <li :class="step >= 2 ? 'bg-ecoBlue-dark' : 'bg-ecoBlue-light'" class="middle-line w-3/12 h-[0.5px] p-0"></li>
            <li :class="{ active: step === 2 }"><Link :href="route('cart.checkout')">
                <span :class="step >= 2 ? 'bg-ecoBlue-dark' : 'bg-ecoBlue'" class=" text-2xl rounded-full flex justify-center items-center text-white
                 w-12 h-12">2</span>
            </Link></li>
            <li :class="step === 3 ? 'bg-ecoBlue-dark' : 'bg-ecoBlue-light'"  class="middle-line w-3/12 h-[0.5px] p-0"></li>
            <li :class="{ active: step === 3 }"><Link :href="route('cart.complete')">
                <span :class="step === 3 ? 'bg-ecoBlue-dark' : 'bg-ecoBlue'" class=" text-2xl rounded-full flex justify-center items-center text-white
                 w-12 h-12">3</span>
            </Link></li>
        </ul>
    </nav>
</template>

<script setup>
import { Head , Link} from '@inertiajs/vue3';
import PageLayout from '@/Layouts/PageLayout.vue';
import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();


defineProps({
    step: Number,
});

</script>


<style scoped>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

nav ul {
    display: flex;
    list-style: none;
    justify-content: space-between;
}
nav li {
    padding: 10px;
    border-bottom: 2px solid transparent;
}

nav li.middle-line {
    padding: 0;
}

</style>
