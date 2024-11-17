import { defineStore } from 'pinia';
import axios from 'axios';

export const useProductsStore = defineStore('products', {
    state: () => ({
        products: [],
        productsId: null,
        masterIdList: [],
    }),
    getters: {
    },
    actions: {
       async getMasterIdList() {
            await axios.get(route('settings.products.master-id-list')).then(response => {
                this.setMasterIdList(response.data);
            });
        },

        setMasterIdList(masterIdList) {
            this.masterIdList = masterIdList;
        },
    }
});
