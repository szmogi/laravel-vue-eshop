import { defineStore } from 'pinia';
import axios from 'axios';

export const useConfigStore = defineStore('config', {
    state: () => ({
        vat: 0,
        status: [],
        paymentMethod: [],
        shippingMethod: [],
    }),
    actions: {
        // Nastavenie vat
        setVat(vat) {
            this.vat = vat;
        },

        // Nastavenie statusu objednávok
        setStatus(status) {
            this.status.push(status);
        },

        // Nastavenie metód platby
        setPaymentMethod(paymentMethod) {
            this.paymentMethod.push(paymentMethod);
        },

        // Nastavenie dopravného spôsobu
        setShippingMethod(shippingMethod) {
            this.shippingMethod.push(shippingMethod);
        },

        // Odstránenie stavu
        removeStatus(id) {
            this.status = this.status.filter(status => status.id !== id);
        },

        // Odstránenie metódy platby
        removePaymentMethod(id) {
            this.paymentMethod = this.paymentMethod.filter(paymentMethod => paymentMethod.id !== id);
        },

        // Odstránenie dopravného spôsobu
        removeShippingMethod(id) {
            this.shippingMethod = this.shippingMethod.filter(shippingMethod => shippingMethod.id !== id);
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
