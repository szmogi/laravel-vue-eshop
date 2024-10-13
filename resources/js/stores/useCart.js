import { defineStore } from 'pinia';
import axios from 'axios';

export const useCartStore = defineStore('cart', {
    state: () => ({
        user: [],
        cart: [],
        count: 0,
        countAllProducts: 0,
        totalSum: 0,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },

        async initCart() {
            await axios.get(route('cart.view')).then(response => {
                this.setCart(response.data.cart);
            });
        },

        async addToCart(product,quantity) {
            await axios.post(route('cart.add'), {
                id: product.id,
                product_id: product.id,
                name: product.name,
                price: product.price,
                quantity: quantity,
            }).then(response => {
                this.setCart(response.data.cart);
            });
        },

        async removeCart(product) {
            await axios.post(route('cart.remove'), {
                id: product.id !== undefined ? product.id : product.product_id,
            }).then(response => {
                this.setCart(response.data.cart);
            });
        },

        setCart(cart) {
            this.count = Object.keys(cart).length;
            this.cart = cart;
            this.user.cart = cart;

            this.countAllProducts = Object.keys(cart).reduce((acc, current) => {
                return acc + cart[current].quantity;
            }, 0);

            this.totalSum = Object.keys(cart).reduce((acc, current) => {
                return acc + cart[current].price * cart[current].quantity;
            }, 0);
        },

    },
});
