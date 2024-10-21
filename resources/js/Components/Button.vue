<template>
    <button :class="type === 'danger' ? 'bg-red-500 hover:bg-red-600 py-1 px-1' : '' " class="bg-ecoBlue-light text-white hover:bg-ecoBlue-dark rounded py-2 mx-1 px-3  ">
        <slot />
    </button>
</template>


<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>
