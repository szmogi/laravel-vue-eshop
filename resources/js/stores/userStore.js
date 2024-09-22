import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: 'Juraj',
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
    },
});
