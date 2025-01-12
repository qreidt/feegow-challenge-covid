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
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
        './resources/*/.{vue,js,ts,jsx,tsx}',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: {
                    50: '#f0f8fe',
                    100: '#ddeefc',
                    200: '#c3e2fa',
                    300: '#9ad0f6',
                    400: '#6bb6ef',
                    500: '#4898e9',
                    600: '#2a67cb',
                    700: '#2a67cb',
                    800: '#2854a5',
                    900: '#254983',
                    950: '#1b2d50',
                },
            }
        },
    },

    plugins: [forms, typography],
};
