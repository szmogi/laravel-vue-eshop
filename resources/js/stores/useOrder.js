import { defineStore } from 'pinia';
import axios from 'axios';

export const useOrderStore = defineStore('order', {
    state: () => ({
        order: [],
        orderResult: [],
        orderId: null,
        noVat: 0,
        rates: 1.2,
        totalSumOrder: 0,
        totalCountOrder: 0,
        statusShow: [
            { label: 'Vybrat stav', value: '' },
        ],
    }),
    actions: {
        // Nastavenie stavu zobrazenia
        setStatusShow(statusShow) {
            for (const [key, value] of Object.entries(statusShow)) {
                this.statusShow.push({ label: key, value: value });
            }
        },

        // Pre-procesovanie objednávky
        getPreprocessOrder() {
            this.totalSumOrder = this.order.reduce((acc, current) => {
                return acc + Number(current.total);
            }, 0);

            this.totalSumOrder = this.totalSumOrder.toFixed(2);
            this.totalCountOrder = this.order.length;
        },

        // Nastavenie objednávky
        setOrder(order) {
            this.order = order;
        },

        // Nastavenie ceny bez DPH
        setNoVat() {
            if(this.order.total === 0) {
                this.noVat = 0;
                return;
            }
            if(this.order.currencyType === null || this.order.currencyType === 'EUR') {
                let noVat = this.order.total / this.rates;
                this.noVat = noVat.toFixed(2);
            } else {
                this.noVat = this.order.total;
            }
        },

       // Vytvorenie objednávky
       async createOrder(order) {
            await axios.post(route('order.add'), {
                order: order,
            }).then(response => {
                this.setOrder(response.data.order);
                this.orderId = response.data.orderId;
                return response.data;
            });
        },

        // Získa objednávku
        async getOrder(id) {
            await axios.get(route('order.show', id), { params: { json: true } }).then(response => {
                this.setOrder(response.data.data);
                this.orderId = response.data.id;
                this.setNoVat();
            });
        },

        // Získa stavy zobrazenia
        async getStatusShow() {
            await axios.get(route('order.status')).then(response => {
                this.setStatusShow(response.data);
            });
        },

    }
});
