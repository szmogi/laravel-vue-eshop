import { defineStore } from 'pinia';
import axios from 'axios';

export const useOrderStore = defineStore('order', {
    state: () => ({
        order: [],
        orderResult: [],
        orderId: null,
        noVat: 0,
        rates: 1.2
    }),
    actions: {
       async createOrder(order) {
            await axios.post(route('order.add'), {
                order: order,
            }).then(response => {
                this.setOrder(response.data.order);
                this.orderId = response.data.orderId;
                return response.data;
            });
        },

        setOrder(order) {
            this.order = order;
        },

        setNoVat() {
            let noVat = this.order.total / this.rates;
            this.noVat = noVat.toFixed(2);
        },

        async getOrder(id) {
            await axios.get(route('order.show', id)).then(response => {
                console.log(response.data);
                this.setOrder(response.data.data);
                this.orderId = response.data.id;
                this.setNoVat();
            });
        },

    }
});
