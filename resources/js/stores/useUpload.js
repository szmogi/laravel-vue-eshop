import { defineStore } from 'pinia';
import axios from 'axios';

export const useUploadStore = defineStore('upload', {
    state: () => ({
        successResponse: false,
        imageGallery: [],
        uploadImage: null,
        gallery: 'Gallery',
        galleryStorage: localStorage.getItem('imageGallery'),
    }),
    getters: {
    },
    actions: {
        // Nahrať obrázok
        async uploadImage(data) {
            await axios.post(route('api.upload'), data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }).then(response => {
                this.successResponse = true;
                this.uploadImage = response.data;
                this.imageGallery.push(response.data);
                localStorage.setItem('imageGallery', JSON.stringify(this.imageGallery) , 'imageGallery', 60 * 24 * 30);
                return response.data;
            });
        },

        // Získa obrázky
        async getImageGallery() {
            if(this.galleryStorage !== [] && this.galleryStorage !== null && this.galleryStorage !== '[]') {
                console.log(this.galleryStorage.length);
                this.imageGallery = JSON.parse(this.galleryStorage);
            } else {

                await axios.get(route('api.upload.gallery')).then(response => {

                    localStorage.setItem('imageGallery', JSON.stringify(response.data), 'imageGallery', 60 * 24 * 30);
                    this.imageGallery = response.data;
                    this.successResponse = true;
                });
            }
        },

    },
});
