<template>
    <h3>{{ $t('uploadImage') }}</h3>
    <div class="file-upload w-full flex  justify-start items-center py-4">
        <FileUpload ref="fileupload" mode="basic" name="demo[]" url="/api/upload" accept="image/*" :maxFileSize="1000000"  @select="onSelect" />
        <Button label="Upload" @click="upload" class="ml-2" severity="secondary" />
        <Button label="Gallery" @click="openGallery" class="ml-2" severity="secondary" />
    </div>
    <div v-if="useUpload.imageGallery.length > 0" class="mt-4 w-full">
        <h3 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
            {{ $t('gallery') }}
        </h3>
        <div v-for="image in useUpload.imageGallery" :key="image.id" class="flex flex-row items-center justify-between">
            <img class="w-10 h-10 mr-2" :src="image.filepath" alt="image.filename" width="25" />
            <div class="flex flex-row items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ image.filename }}</span>
                <Button class="ml-2" severity="danger" @click="removeImage(image.id)" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';

import { useUploadStore } from "@/stores/useUpload.js";
const useUpload = useUploadStore();

const props = defineProps({
    removeId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['upload-success', 'upload-error']);

const selectedFile = ref(null);
const openGallery = () => {
    useUpload.getImageGallery();
}
const onSelect = (event) => {
    // Tu získame vybraný súbor z udalosti
    selectedFile.value = event.files[0]; // alebo event.file
};
const upload = async (event) => {
    if (!selectedFile.value) {
        console.error('Nie je vybraný súbor.');
        emit('upload-error', new Error('Súbor nie je definovaný.'));
        return;
    }
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    formData.append('existing_file_id', props.removeId);
    try {
        // Odoslanie súboru na API endpoint pomocou Axios
        const response = await useUpload.uploadImage(formData);

        // Emitovanie úspešného nahratia súboru
        emit('upload-success', {
            id: response.data.id,
            filepath: response.data.filepath,
        });
    } catch (error) {
        console.error('Chyba pri nahrávaní súboru:', error);
        emit('upload-error', error);
    }
};

</script>

<style scoped>
.file-upload {
    max-width: 100%;
    margin: 0 auto;
}
</style>
