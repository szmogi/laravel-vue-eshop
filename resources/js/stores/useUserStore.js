import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        admin: false,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },

        ifAdmin() {
            if(this.user === null) {
                return false;
            }

            if(import.meta.env.VITE_ADMIN_USER === this.user.name
                && import.meta.env.VITE_ADMIN_ID == this.user.id
                && import.meta.env.VITE_ADMIN_EMAIL === this.user.email) {
                this.admin = true;
                return true;
            }

            console.log(this.admin);
        },
    },
});
