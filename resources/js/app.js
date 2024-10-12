import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18n } from 'vue-i18n';
import { createPinia } from 'pinia';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const PiniaVuePlugin = createPinia();
const i18n = createI18n({
    locale: 'en', // default locale
    fallbackLocale: 'en',
    messages: {
        en: {
            welcome: 'Welcome to our shop!',
            product: 'Product',
            price: 'Price',
            AddToCart: 'Add to cart',
            stock: 'Stock',
        },
        sk: {
            welcome: 'Vitajte v našom obchode!',
            product: 'Produkt',
            price: 'Cena',
            AddToCart: 'Pridať do košíka',
            stock: 'Skladom',
        },
    },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PiniaVuePlugin)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
