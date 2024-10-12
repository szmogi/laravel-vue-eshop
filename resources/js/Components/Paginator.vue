<template>
    <div class="pagination py-4">
        <button v-for="link in links" :key="link.label" :disabled="!link.url" @click="goTo(link.url)">
            <span v-html="link.label"></span>
        </button>
    </div>
</template>

<script setup>

defineProps({
    links: Array,
});

const goTo = (url) => {
   window.location.href = url;
};

</script>

<style scoped>
.pagination button {
    margin: 0 5px;
    padding: 5px;
}
</style>
