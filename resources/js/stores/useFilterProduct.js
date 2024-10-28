import { defineStore } from 'pinia';
import axios from 'axios';

// Define store
export const useFilterProduct = defineStore('filterProduct', {
    state: () => ({
        products: [],
        categories: [],
        colors: [],
        sizes: [],
        resultsCount: 0,
    }),
    actions: {

        // Init filter content
        async initFilter() {
            await axios.get(route('filter.content')).then(response => {
                this.setFilterContent(response.data);
            });
        },

        // Set filter content
        setFilterContent($filterContent) {
            this.categories = $filterContent.categories;
            this.colors = $filterContent.colors;
            this.sizes = $filterContent.sizes;
        },

        // Set new products
        setNewProducts(products) {
            this.products = products.products;
            this.resultsCount = products.count;
        },

        // Get filter content
        async getFilterContent( category, color, size) {
            await axios.get(route('products.filter'), {
                params: {
                    category: category,
                    color: color,
                    size: size,
                },
            }).then(response => {
                this.setNewProducts(response.data);
            });
        },
    },
});
