import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            screens: {
                '2xl': '1400px',
                '3xl': '1536px',
            },
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            height: {
                '128': '32rem',
            },
            fontFamily: {
                sans: ['Poppins', 'sans-serif'], // Môžete zmeniť na akékoľvek písmo
            },
            colors: {
                ecoGreen: {
                    light: '#6CA36C', // svetlá zelená
                    DEFAULT: '#4F8F4F', // základná zelená
                    dark: '#2D5E2D', // tmavá zelená
                },
                ecoGray: {
                    light: '#E0E0E0', // svetlá sivá
                    DEFAULT: '#4F4F4F', // základná sivá
                    dark: '#333333', // tmavá sivá
                },
                ecoBlue: {
                    light: '#59b6d9', // svetlá zelená
                    DEFAULT: '#2d88ad', // základná modrá
                    dark: '#195c72', // tmavá zelená
                },
            },
        },
    },

    plugins: [forms, typography],
};
