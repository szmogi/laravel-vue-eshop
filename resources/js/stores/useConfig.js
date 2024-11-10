import { defineStore } from 'pinia';
import axios from 'axios';

export const useConfigStore = defineStore('config', {
    state: () => ({
        vat: 0,
        status: [],
        paymentMethod: [],
        shippingMethod: [],
        eshop: [],
    }),
    actions: {
        // Nastavenie vat
        async setVat(vat) {
            await axios.post(route('settings.eshop.vat'), {
                vat: vat,
            }).then(response => {
                this.vat = response.data.vat;
            });

        },

        // Nastavenie statusu objednávok
        async setStatus(status) {
            let $sendStatus = status.name;

            if(status.id) {
                $sendStatus = {name: status.name, id: status.id};
            }

            await axios.post(route('settings.eshop.status'), {
                status: $sendStatus,
            }).then(response => {
                this.status = response.data;
            });
        },

        // Nastavenie metód platby
        async setPaymentMethod(paymentMethod) {
            await axios.post(route('settings.eshop.payment-method'), {
                paymentMethod: {name: paymentMethod.name, id: paymentMethod.id , nameEn: paymentMethod.nameEn, description: paymentMethod.description, descriptionEn: paymentMethod.descriptionEn, active: paymentMethod.active , image: paymentMethod.image, imagePath: paymentMethod.imagePath},
            }).then(response => {
                this.paymentMethod = response.data;
            });
        },

        // Nastavenie dopravného spôsobu
        async setShippingMethod(shippingMethod) {
            await axios.post(route('settings.eshop.shipping-method'), {
                shippingMethod: {name: shippingMethod.name, id: shippingMethod.id, price: shippingMethod.price, nameEn: shippingMethod.nameEn, description: shippingMethod.description, descriptionEn: shippingMethod.descriptionEn, active: shippingMethod.active , image: shippingMethod.image, imagePath: shippingMethod.imagePath},
            }).then(response => {
                this.shippingMethod = response.data;
            });
        },

        // Odstránenie stavu
        removeStatus($remove) {
            axios.post(route('settings.eshop.status'), {
                status: $remove,
                remove: true,
            }).then(response => {
                this.status = response.data;
            });
        },

        // Odstránenie metódy platby
        removePaymentMethod($remove) {
            axios.post(route('settings.eshop.payment-method'), {
                paymentMethod: {id: $remove.id},
                remove: true,
            }).then(response => {
                this.paymentMethod = response.data;
            });
        },

        // Odstránenie dopravného spôsobu
        removeShippingMethod($remove) {
            axios.post(route('settings.eshop.shipping-method'), {
                shippingMethod: {id: $remove.id},
                remove: true,
            }).then(response => {
                this.shippingMethod = response.data;
            });
        },

        // Získa vat
        async getVat() {
            await axios.get(route('settings.eshop')).then(response => {
                this.setVat(response.data.vat);
            });
        },

        // Získa statusy objednávok
        async getStatus() {
            await axios.get(route('settings.eshop')).then(response => {
                this.setStatus(response.data.status);
            });
        },

        // Získa metódy platby
        async getPaymentMethod() {
            await axios.get(route('settings.eshop')).then(response => {
                this.setPaymentMethod(response.data.paymentMethod);
            });
        },

        // Získa dopravného spôsobu
        async getShippingMethod() {
            await axios.get(route('settings.eshop')).then(response => {
                this.setShippingMethod(response.data.shippingMethod);
            });
        },

    },
});
