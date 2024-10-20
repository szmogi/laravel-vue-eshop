import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18n } from 'vue-i18n';
import { createPinia } from 'pinia';
import { translation } from './translation.js';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const PiniaVuePlugin = createPinia();
const i18n = createI18n({
    locale: localStorage.getItem('locale') || 'en', // default locale
    fallbackLocale: 'en', // fallback locale
    messages: {
        ...translation,
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
            .use(PrimeVue, {   theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'system',
                        cssLayer: false
                    }
                }}, { ripple: true })
            .mount(el);
    },
    progress: {
        color: '#195c72',
    },
});
