import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        user: [],
        cart: [],
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        addToCart(product) {
            this.user.cart.push(product);
        },
        removeFromCart(product) {
            this.user.cart = this.user.cart.filter((item) => item.id !== product.id);
        },
    },
});
