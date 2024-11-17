<template>
    <h3>{{ $t('uploadImage') }}</h3>
    <div class="file-upload w-full flex  justify-start items-center py-4">
        <FileUpload :chooseLabel="$t('browse')" ref="fileupload" mode="basic" name="uploadImage" url="/api/upload" accept="image/*" :maxFileSize="1000000"  @select="onSelect" />
        <Button :label="$t('uploadImage')" @click="upload" class="ml-2" severity="secondary" />
        <Button :label="$t('gallery')" @click="openGallery" class="ml-2" severity="secondary" />
    </div>
    <Dialog header="Vyberte obrázok" v-model:visible="showDialog" :style="{ width: '50vw' }">
        <h3 class="text-center text-2xl font-bold tracking-tight text-ecoBlue-dark sm:text-xl py-2">
            {{ $t('gallery') }}
        </h3>
        <div class="flex flex-wrap justify-center items-center py-8">
            <div
                v-for="image in useUpload.imageGallery"
                :key="image.id"
                @click="toggleSelection(image)"
                class="flex flex-col items-center justify-between cursor-pointer w-24 h-24 m-1 p-1 rounded-lg border-2"
                :class="{'border-ecoBlue-light': isSelected(image), 'border-gray-300': !isSelected(image)}"
            >
                <img
                    class="object-cover w-full h-full rounded-lg"
                    :src="image.filepath"
                    :alt="image.filename"
                />
                <div class="text-xs text-gray-600 dark:text-gray-400 truncate w-full text-center">
                    {{ image.filename }}
                </div>
            </div>
        </div>

        <div class="flex justify-between align-center items-center mt-4">
            <div>
                {{ $t('selectImagesCount') }}: {{ selectedImages.length }}
            </div>
            <div v-if="maxImages" class="text-red-500 font-bold">
                {{ $t('maxLimitImages') }} : {{ maxImagesCount }}
            </div>
            <div class="flex justify-content-end w-56 justify-between">
                <Button :label="$t('exit')" icon="pi pi-times" @click="closeDialog" />
                <Button :label="$t('confirm')" icon="pi pi-check" @click="confirmSelection" />
            </div>
        </div>
    </Dialog>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';

import { useUploadStore } from "@/stores/useUpload.js";
import Dialog from 'primevue/dialog';
const useUpload = useUploadStore();

const props = defineProps({
    removeId: {
        type: Number,
        default: null,
    },
    multiSelectImage: {
        type: Boolean,
        default: false,
    },
    maxImagesCount: {
        type: Number,
        default: 1,
    },
});

const emit = defineEmits(['upload-success', 'upload-error']);
const selectedFile = ref(null);
const showDialog = ref(false);
const selectedImages = ref([]);
const maxImages = ref(false);

const openGallery = () => {
    useUpload.getImageGallery();
    showDialog.value = true;
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
const confirmSelection = ($event) => {
    if(props.multiSelectImage) {
        emit('upload-success', {
            data: selectedImages.value,
        });
    } else {
        emit('upload-success', {
            id: selectedImages.value[0].id,
            filepath: selectedImages.value[0].filepath,
            name: selectedImages.value[0].name,
        });
    }
    // Vráti vybraný obrázok rodičovskej komponenty
    closeDialog();
    selectedImages.value = [];
};

const closeDialog = () => {
    showDialog.value = false;
};

// Funkcia na kontrolu, či je obrázok vybraný
const isSelected = (image) => {
    return selectedImages.value.some((selected) => selected.id === image.id);
};

// Funkcia na pridanie/odstránenie obrázka zo zoznamu vybraných
const toggleSelection = (image) => {
    const index = selectedImages.value.findIndex((selected) => selected.id === image.id);
    if (index === -1) {
        if (!props.multiSelectImage && selectedImages.value.length >= props.maxImagesCount) {
            maxImages.value = true;
            return;
        }
        maxImages.value = false;
        selectedImages.value.push({ id: image.id, filepath: image.filepath , name: image.name });
    } else {
        selectedImages.value.splice(index, 1);
        if (selectedImages.value.length === 0) {
            maxImages.value = false;
        }
    }
};

</script>

<style scoped>
.file-upload {
    max-width: 100%;
    margin: 0 auto;
}
</style>
