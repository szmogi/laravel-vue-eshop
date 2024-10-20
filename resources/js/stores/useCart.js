import { defineStore } from 'pinia';
import axios from 'axios';

export const useCartStore = defineStore('cart', {
    state: () => ({
        user: [],
        cart: [],
        cartArray: [],
        count: 0,
        countAllProducts: 0,
        totalSum: 0,
        noVat: 0,
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

        async clearCart() {
            await axios.post(route('cart.clear')).then(response => {
                this.setCart(response.data.cart);
            });
        },

        setCart(cart) {
            this.count = Object.keys(cart['cart']).length;
            this.cart = cart['cart'];
            this.user.cart = cart['cart'];
            this.totalSum = cart['totalSum'];
            this.noVat = cart['noVat'];

            this.countAllProducts = Object.keys(cart['cart']).reduce((acc, current) => {
                return acc + cart['cart'][current].quantity;
            }, 0);

            this.cartArray = Object.keys(cart['cart']).map(function(key) {
                return cart['cart'][key];
            });
        },

        async setQuantity(id, quantity) {
            await axios.post(route('cart.set.quantity'), {
                id: id,
                quantity: quantity,
            }).then(response => {
                this.setCart(response.data.cart);
                return response.data.cart;
            });
        },
    },
});
