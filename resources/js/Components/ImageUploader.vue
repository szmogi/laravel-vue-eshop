<template>
    <h3>{{ $t('uploadImage') }}</h3>
    <div class="file-upload w-full flex  justify-start items-center py-4">
        <FileUpload ref="fileupload" mode="basic" name="demo[]" url="/api/upload" accept="image/*" :maxFileSize="1000000" @upload="onUpload" @select="onSelect" />
        <Button label="Upload" @click="upload" class="ml-2" severity="secondary" />
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import axios from 'axios';

const props = defineProps({
    removeId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['upload-success', 'upload-error']);

const selectedFile = ref(null);
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

    console.log('Vybraný súbor:', selectedFile.value);
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    formData.append('existing_file_id', props.removeId);
    try {
        // Odoslanie súboru na API endpoint pomocou Axios
        const response = await axios.post('/api/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

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

const onUpload = (event) => {
    toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

</script>

<style scoped>
.file-upload {
    max-width: 100%;
    margin: 0 auto;
}
</style>
